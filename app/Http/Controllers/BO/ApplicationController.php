<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationTransitionRequest;
use App\Mail\ApplicationStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ApplicationController extends Controller
{
    /**
     * Display a listing of franchise applications.
     */
    public function index()
    {
        // Mock data
        $applications = [
            [
                'id' => 1,
                'name' => 'Jean Dupont',
                'email' => 'jean.dupont@email.fr',
                'territory' => 'Nice Centre',
                'status' => 'in_review',
                'step' => 'documents',
                'submitted_at' => '2024-08-20',
            ],
            [
                'id' => 2,
                'name' => 'Marie Martin',
                'email' => 'marie.martin@email.fr',
                'territory' => 'Toulouse Nord',
                'status' => 'approved',
                'step' => 'contract',
                'submitted_at' => '2024-08-15',
            ],
            [
                'id' => 3,
                'name' => 'Pierre Durand',
                'email' => 'pierre.durand@email.fr',
                'territory' => 'Bordeaux Est',
                'status' => 'rejected',
                'step' => 'interview',
                'submitted_at' => '2024-08-10',
            ],
        ];

        return view('bo.applications.index', compact('applications'));
    }

    /**
     * Display the specified application with workflow status.
     */
    public function show(string $id)
    {
        // Mock data
        $application = [
            'id' => $id,
            'name' => 'Jean Dupont',
            'email' => 'jean.dupont@email.fr',
            'phone' => '+33 6 12 34 56 78',
            'territory' => 'Nice Centre',
            'status' => 'in_review',
            'current_step' => 'documents',
            'submitted_at' => '2024-08-20',
            'workflow_steps' => [
                ['step' => 'application', 'status' => 'completed', 'date' => '2024-08-20'],
                ['step' => 'documents', 'status' => 'in_progress', 'date' => null],
                ['step' => 'interview', 'status' => 'pending', 'date' => null],
                ['step' => 'contract', 'status' => 'pending', 'date' => null],
            ],
            'documents' => [
                ['name' => 'Pièce d\'identité', 'status' => 'uploaded'],
                ['name' => 'Justificatif de revenus', 'status' => 'pending'],
                ['name' => 'Business plan', 'status' => 'uploaded'],
            ],
        ];

        return view('bo.applications.show', compact('application'));
    }

    /**
     * Transition application to prequalified status.
     */
    public function prequalify(ApplicationTransitionRequest $request, string $id)
    {
        // Mock application data - in real app, would fetch from database
        $application = [
            'id' => $id,
            'name' => 'Jean Dupont',
            'email' => 'jean.dupont@email.fr',
            'territory' => 'Nice Centre',
            'status' => 'submitted',
        ];

        $fromStatus = $application['status'];
        $toStatus = 'prequalified';
        $message = $request->input('message');

        // Send notification email
        Mail::to($application['email'])->send(
            new ApplicationStatusChanged($application, $fromStatus, $toStatus, $message)
        );

        // In real app: Update database status and create audit log

        return redirect()
            ->route('bo.applications.show', $id)
            ->with('success', 'Candidature pré-qualifiée avec succès. Email de notification envoyé.');
    }

    /**
     * Transition application to interview status.
     */
    public function interview(ApplicationTransitionRequest $request, string $id)
    {
        // Mock application data
        $application = [
            'id' => $id,
            'name' => 'Jean Dupont',
            'email' => 'jean.dupont@email.fr',
            'territory' => 'Nice Centre',
            'status' => 'prequalified',
        ];

        $fromStatus = $application['status'];
        $toStatus = 'interview';
        $message = $request->input('message');

        // Send notification email
        Mail::to($application['email'])->send(
            new ApplicationStatusChanged($application, $fromStatus, $toStatus, $message)
        );

        // In real app: Update database and schedule interview

        return redirect()
            ->route('bo.applications.show', $id)
            ->with('success', 'Entretien planifié avec succès. Email de notification envoyé.');
    }

    /**
     * Approve application and transition to contract phase.
     */
    public function approve(ApplicationTransitionRequest $request, string $id)
    {
        // Mock application data
        $application = [
            'id' => $id,
            'name' => 'Jean Dupont',
            'email' => 'jean.dupont@email.fr',
            'territory' => 'Nice Centre',
            'status' => 'interview',
        ];

        $fromStatus = $application['status'];
        $toStatus = 'approved';
        $message = $request->input('message');

        // Send notification email
        Mail::to($application['email'])->send(
            new ApplicationStatusChanged($application, $fromStatus, $toStatus, $message)
        );

        // In real app: Update database, generate contract, create franchisee record

        return redirect()
            ->route('bo.applications.show', $id)
            ->with('success', 'Candidature approuvée ! Email de félicitations envoyé.');
    }

    /**
     * Reject application with reason.
     */
    public function reject(ApplicationTransitionRequest $request, string $id)
    {
        // Mock application data
        $application = [
            'id' => $id,
            'name' => 'Jean Dupont',
            'email' => 'jean.dupont@email.fr',
            'territory' => 'Nice Centre',
            'status' => 'in_review',
        ];

        $fromStatus = $application['status'];
        $toStatus = 'rejected';
        $reason = $request->input('reason');

        // Send notification email
        Mail::to($application['email'])->send(
            new ApplicationStatusChanged($application, $fromStatus, $toStatus, $reason)
        );

        // In real app: Update database and create audit log

        return redirect()
            ->route('bo.applications.index')
            ->with('success', 'Candidature rejetée. Email de notification envoyé.');
    }
}
