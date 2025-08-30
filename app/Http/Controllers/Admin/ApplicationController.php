<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\FranchiseApplicationStatusChanged;
use App\Mail\FranchiseeOnboardingWelcome;
use App\Models\FranchiseApplication;
use App\Models\FranchiseApplicationDocument;
use App\Models\Franchisee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', FranchiseApplication::class);

        $query = FranchiseApplication::with(['documents'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(15);

        $statuses = ['draft', 'submitted', 'prequalified', 'interview', 'approved', 'rejected'];

    return view('bo.applications.index', compact('applications', 'statuses'));
    }

    public function show(FranchiseApplication $application)
    {
        $this->authorize('view', $application);

        $application->load(['documents', 'events' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        // Available transitions based on current status
        $availableTransitions = $this->getAvailableTransitions($application->status);

        return view('bo.applications.show', compact('application', 'availableTransitions'));

    }

    /**
     * Shortcut actions mapping to updateStatus for specific transitions
     */
    public function prequalify(Request $request, FranchiseApplication $application)
    {
        $request->merge(['status' => 'prequalified']);
        return $this->updateStatus($request, $application);
    }

    public function interview(Request $request, FranchiseApplication $application)
    {
        $request->merge(['status' => 'interview']);
        return $this->updateStatus($request, $application);
    }

    public function approve(Request $request, FranchiseApplication $application)
    {
        $request->merge(['status' => 'approved']);
        return $this->updateStatus($request, $application);
    }

    public function reject(Request $request, FranchiseApplication $application)
    {
        $request->merge(['status' => 'rejected']);
        return $this->updateStatus($request, $application);
    }

    public function updateStatus(Request $request, FranchiseApplication $application)
    {
        $this->authorize('update', $application);

        $request->validate([
            'status' => 'required|in:prequalified,interview,approved,rejected',
            'comment' => 'nullable|string|max:1000',
        ]);

        $newStatus = $request->status;
        $oldStatus = $application->status;

        // Validate transition
        if (! $this->isValidTransition($oldStatus, $newStatus)) {
            return back()->with('error', __('ui.flash.invalid_transition'));
        }

        $application->update(['status' => $newStatus]);

        // Create event (schema: from_status/to_status/message/user_id)
        $application->events()->create([
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'message' => $request->comment,
            'user_id' => Auth::id(),
        ]);

        // Send notification email
        try {
            Mail::to($application->email)->send(
                new FranchiseApplicationStatusChanged(
                    $application,
                    $oldStatus,
                    $newStatus,
                    $request->comment
                )
            );

            // If approved, create franchisee and send onboarding email
            if ($newStatus === 'approved') {
                $this->handleApprovedApplication($application);
            }

        } catch (\Exception $e) {
            Log::error('Failed to send status change email', [
                'application_id' => $application->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('success', __('ui.flash.status_updated'));
    }

    public function downloadDocument(FranchiseApplicationDocument $document)
    {
        $this->authorize('view', $document->application);

        if (! Storage::disk('local')->exists($document->path)) {
            abort(404, __('ui.flash.file_not_found'));
        }

        return response()->download(
            Storage::disk('local')->path($document->path),
            $document->original_name ?? 'document.pdf'
        );
    }

    private function getAvailableTransitions(string $currentStatus): array
    {
        $transitions = [
            'draft' => [],
            'submitted' => ['prequalified', 'rejected'],
            'prequalified' => ['interview', 'rejected'],
            'interview' => ['approved', 'rejected'],
            'approved' => [],
            'rejected' => [],
        ];

        return $transitions[$currentStatus] ?? [];
    }

    private function isValidTransition(string $from, string $to): bool
    {
        $validTransitions = $this->getAvailableTransitions($from);

        return in_array($to, $validTransitions);
    }

    /**
     * Handle approved application: create franchisee account and send onboarding email
     */
    private function handleApprovedApplication(FranchiseApplication $application): void
    {
        try {
            // Check if franchisee already exists
            $existingFranchisee = Franchisee::where('email', $application->email)->first();
            if ($existingFranchisee) {
                Log::info('Franchisee already exists for approved application', [
                    'application_id' => $application->id,
                    'franchisee_id' => $existingFranchisee->id,
                ]);

                return;
            }

            // Create user account with empty password (to be set via reset link)
            $user = User::create([
                'name' => $application->full_name,
                'email' => $application->email,
                'password' => Hash::make(Str::random(32)), // Temporary random password
                'email_verified_at' => now(),
            ]);

            // Create franchisee record
            $franchisee = Franchisee::create([
                'id' => (string) Str::ulid(),
                'name' => $application->full_name,
                'email' => $application->email,
                'phone' => $application->phone,
                'royalty_rate' => 8.00, // Default royalty rate
            ]);

            // Assign franchisee role if using Spatie Permission
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('franchisee');
            }

            // Generate password reset token
            $token = Password::createToken($user);
            $resetUrl = url(route('password.reset', ['token' => $token, 'email' => $user->email], false));

            // Send onboarding welcome email with password setup link
            Mail::to($application->email)->send(
                new FranchiseeOnboardingWelcome($franchisee, $resetUrl)
            );

            Log::info('Franchisee account created and onboarding email sent', [
                'application_id' => $application->id,
                'franchisee_id' => $franchisee->id,
                'user_id' => $user->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to handle approved application', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
