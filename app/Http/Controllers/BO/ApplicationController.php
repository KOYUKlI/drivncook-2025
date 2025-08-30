<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationTransitionRequest;
use App\Mail\FranchiseApplicationStatusChanged;
use App\Models\FranchiseApplication;
use App\Models\FranchiseApplicationDocument;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    /**
     * Display a listing of franchise applications.
     */
    public function index()
    {
        $this->authorize('viewAny', FranchiseApplication::class);

        $applications = FranchiseApplication::query()
            ->with(['events', 'documents'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('bo.applications.index', compact('applications'));
    }

    /**
     * Display the specified application with workflow status.
     */
    public function show(string $id)
    {
        $application = FranchiseApplication::with(['events', 'documents'])->findOrFail($id);
        $this->authorize('view', $application);

        return view('bo.applications.show', compact('application'));
    }

    /**
     * Transition application to prequalified status.
     */
    public function prequalify(ApplicationTransitionRequest $request, string $id)
    {
        $application = FranchiseApplication::findOrFail($id);
        $this->authorize('update', $application);

        $fromStatus = $application->status;
        $toStatus = 'prequalified';
        $message = $request->input('message');

        // Update application status
        $application->update(['status' => $toStatus]);

        // Create event log
        $application->events()->create([
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'message' => $message,
        ]);

        // Send notification email
        Mail::to($application->email)->send(
            new FranchiseApplicationStatusChanged($application, $fromStatus, $toStatus, $message)
        );

        return redirect()
            ->route('bo.applications.show', $id)
            ->with('success', 'Candidature pré-qualifiée avec succès. Email de notification envoyé.');
    }

    /**
     * Transition application to interview status.
     */
    public function interview(ApplicationTransitionRequest $request, string $id)
    {
        $application = FranchiseApplication::findOrFail($id);
        $this->authorize('update', $application);

        $fromStatus = $application->status;
        $toStatus = 'interview';
        $message = $request->input('message');

        // Update application status
        $application->update(['status' => $toStatus]);

        // Create event log
        $application->events()->create([
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'message' => $message,
        ]);

        // Send notification email
        Mail::to($application->email)->send(
            new FranchiseApplicationStatusChanged($application, $fromStatus, $toStatus, $message)
        );

        return redirect()
            ->route('bo.applications.show', $id)
            ->with('success', 'Entretien planifié avec succès. Email de notification envoyé.');
    }

    /**
     * Approve application and transition to contract phase.
     */
    public function approve(ApplicationTransitionRequest $request, string $id)
    {
        $application = FranchiseApplication::findOrFail($id);
        $this->authorize('update', $application);

        $fromStatus = $application->status;
        $toStatus = 'approved';
        $adminMessage = $request->input('message');

        // Update application status
        $application->update(['status' => $toStatus]);

        // Create event log
        $application->events()->create([
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'message' => $adminMessage,
        ]);

        // Send notification email
        Mail::to($application->email)->send(
            new FranchiseApplicationStatusChanged($application, $fromStatus, $toStatus, $adminMessage)
        );

        return redirect()
            ->route('bo.applications.show', $id)
            ->with('success', 'Candidature approuvée ! Email de félicitations envoyé.');
    }

    /**
     * Reject application with reason.
     */
    public function reject(ApplicationTransitionRequest $request, string $id)
    {
        $application = FranchiseApplication::findOrFail($id);
        $this->authorize('update', $application);

        $fromStatus = $application->status;
        $toStatus = 'rejected';
        $reason = $request->input('reason');

        // Update application status
        $application->update(['status' => $toStatus]);

        // Create event log
        $application->events()->create([
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'message' => $reason,
        ]);

        // Send notification email
        Mail::to($application->email)->send(
            new FranchiseApplicationStatusChanged($application, $fromStatus, $toStatus, $reason)
        );

        return redirect()
            ->route('bo.applications.index')
            ->with('success', 'Candidature rejetée. Email de notification envoyé.');
    }

    /**
     * Download application document (BO-only, secure).
     */
    public function downloadDocument(string $documentId)
    {
        $document = FranchiseApplicationDocument::findOrFail($documentId);

        // Check policy
        $this->authorize('view', $document->application);

        if (! Storage::exists($document->path)) {
            abort(404, 'Document not found');
        }

        return Storage::download($document->path, $document->kind.'_'.$document->id.'.'.pathinfo($document->path, PATHINFO_EXTENSION));
    }
}
