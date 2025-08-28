<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FranchiseApplication;
use App\Models\FranchiseApplicationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(15);

        $statuses = ['draft', 'submitted', 'prequalified', 'interview', 'approved', 'rejected'];

        return view('admin.applications.index', compact('applications', 'statuses'));
    }

    public function show(FranchiseApplication $application)
    {
        $this->authorize('view', $application);

        $application->load(['documents', 'events' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        // Available transitions based on current status
        $availableTransitions = $this->getAvailableTransitions($application->status);

        return view('admin.applications.show', compact('application', 'availableTransitions'));
    }

    public function updateStatus(Request $request, FranchiseApplication $application)
    {
        $this->authorize('update', $application);

        $request->validate([
            'status' => 'required|in:prequalified,interview,approved,rejected',
            'comment' => 'nullable|string|max:1000'
        ]);

        $newStatus = $request->status;
        $oldStatus = $application->status;

        // Validate transition
        if (!$this->isValidTransition($oldStatus, $newStatus)) {
            return back()->with('error', __('ui.flash.invalid_transition'));
        }

        $application->update(['status' => $newStatus]);

        // Create event
        $application->events()->create([
            'event_type' => 'status_changed',
            'description' => $request->comment,
            'data' => [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'user_id' => Auth::id()
            ]
        ]);

        // Send notification email
        try {
            Mail::to($application->email)->send(
                new \App\Mail\FranchiseApplicationStatusChanged(
                    $application, 
                    $oldStatus, 
                    $newStatus, 
                    $request->comment
                )
            );
        } catch (\Exception $e) {
            Log::error('Failed to send status change email: ' . $e->getMessage());
        }

        return back()->with('success', __('ui.flash.status_updated'));
    }

    public function downloadDocument(FranchiseApplicationDocument $document)
    {
        $this->authorize('view', $document->application);

        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, __('ui.flash.file_not_found'));
        }

        return response()->download(
            Storage::disk('local')->path($document->file_path),
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
            'rejected' => []
        ];

        return $transitions[$currentStatus] ?? [];
    }

    private function isValidTransition(string $from, string $to): bool
    {
        $validTransitions = $this->getAvailableTransitions($from);
        return in_array($to, $validTransitions);
    }
}
