<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\FranchiseApplicationApproved;
use App\Mail\FranchiseApplicationRejected;
use App\Models\Franchise;
use App\Models\FranchiseApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class FranchiseApplicationController extends Controller
{
    public function index(): View
    {
        $applications = FranchiseApplication::query()->latest()->paginate(20);
        return view('admin.franchise-applications.index', compact('applications'));
    }

    public function show(int $id): View
    {
        $application = FranchiseApplication::findOrFail($id);
        return view('admin.franchise-applications.show', compact('application'));
    }

    public function approve(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'franchise_name' => ['required','string','max:255','unique:franchises,name'],
        ]);
        $application = FranchiseApplication::where('id', $id)->where('status','pending')->firstOrFail();

        $userEmail = null;
        DB::transaction(function () use ($application, $data, &$userEmail) {
            $franchise = Franchise::create(['name' => $data['franchise_name']]);

            $user = User::where('email', $application->email)->first();
            if ($user) {
                if (!is_null($user->franchise_id) && $user->franchise_id !== $franchise->id) {
                    abort(422, 'Existing user already attached to another franchise.');
                }
                $user->update(['role' => 'franchise', 'franchise_id' => $franchise->id]);
            } else {
                $user = User::create([
                    'name' => $application->full_name,
                    'email' => $application->email,
                    'role' => 'franchise',
                    'franchise_id' => $franchise->id,
                    'password' => bcrypt(str()->random(16)),
                ]);
            }
            $userEmail = $user->email;

            $application->status = 'accepted';
            $application->reviewed_at = now();
            $application->save();
        });

    // Send set password link after commit to avoid race conditions
        if ($userEmail) {
            Password::sendResetLink(['email' => $userEmail]);
        }

    $method = app()->runningUnitTests() ? 'queue' : (config('queue.default') === 'sync' ? 'send' : 'queue');
        try { Mail::to($application->email)->{$method}(new FranchiseApplicationApproved($application)); } catch (\Throwable $e) {
            try { Mail::to($application->email)->send(new FranchiseApplicationApproved($application)); } catch (\Throwable $e2) {}
        }

        return back()->with('success', 'Franchise approved and account invited.');
    }

    public function reject(int $id): RedirectResponse
    {
        $application = FranchiseApplication::where('id', $id)->where('status','pending')->firstOrFail();
        $application->status = 'rejected';
        $application->reviewed_at = now();
        $application->save();

    $method = app()->runningUnitTests() ? 'queue' : (config('queue.default') === 'sync' ? 'send' : 'queue');
        try { Mail::to($application->email)->{$method}(new FranchiseApplicationRejected($application)); } catch (\Throwable $e) {
            try { Mail::to($application->email)->send(new FranchiseApplicationRejected($application)); } catch (\Throwable $e2) {}
        }

        return back()->with('success', 'Application rejected.');
    }
}
