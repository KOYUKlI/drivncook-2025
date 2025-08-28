<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFranchiseApplicationRequest;
use App\Models\FranchiseApplication;
use App\Models\FranchiseApplicationDocument;
use App\Models\FranchiseApplicationEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FranchiseApplicationController extends Controller
{
    /**
     * Show the application creation form.
     */
    public function create()
    {
        return view('public.applications.create');
    }

    /**
     * Save a draft application (minimal info, status = draft).
     */
    public function draft(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'territory' => 'nullable|string|max:255',
        ]);

        $app = new FranchiseApplication();
        $app->id = (string) Str::ulid();
        $app->full_name = trim(($data['first_name'] ?? '').' '.($data['last_name'] ?? '')) ?: null;
        $app->email = $data['email'] ?? '';
        $app->phone = $data['phone'] ?? null;
        $app->desired_area = $data['territory'] ?? null;
        $app->status = 'draft';
        $app->save();

        FranchiseApplicationEvent::create([
            'id' => (string) Str::ulid(),
            'franchise_application_id' => $app->id,
            'from_status' => null,
            'to_status' => 'draft',
            'message' => 'Brouillon créé',
        ]);

        return redirect()->route('public.applications.show', $app->id)
            ->with('success', __('Votre brouillon a été enregistré.'));
    }

    /**
     * Store a newly created application.
     */
    public function store(StoreFranchiseApplicationRequest $request)
    {
        $validated = $request->validated();
        $app = new FranchiseApplication();
        $app->id = (string) Str::ulid();
        $app->full_name = trim(($validated['first_name'] ?? '').' '.($validated['last_name'] ?? ''));
        $app->email = $validated['email'];
        $app->phone = $validated['phone'] ?? null;
        $app->desired_area = $validated['territory'] ?? null;
        $app->status = 'submitted';
        $app->save();

        // Uploads to public disk
        foreach (['cv' => 'cv', 'identity' => 'identity'] as $field => $kind) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store("applications/{$app->id}", 'public');
                FranchiseApplicationDocument::create([
                    'id' => (string) Str::ulid(),
                    'franchise_application_id' => $app->id,
                    'kind' => $kind,
                    'path' => $path,
                ]);
            }
        }

        FranchiseApplicationEvent::create([
            'id' => (string) Str::ulid(),
            'franchise_application_id' => $app->id,
            'from_status' => null,
            'to_status' => 'submitted',
            'message' => 'Soumission de la candidature',
        ]);

        return redirect()->route('public.applications.show', $app->id)
            ->with('success', __('Votre candidature a été soumise.'));
    }

    /**
     * Display the specified application.
     */
    public function show(string $id)
    {
    $application = FranchiseApplication::with(['documents', 'events'])->findOrFail($id);
        return view('public.applications.show', compact('application'));
    }
}
