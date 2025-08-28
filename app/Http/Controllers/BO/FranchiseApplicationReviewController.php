<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationTransitionRequest;
use App\Models\FranchiseApplication;
use App\Models\FranchiseApplicationEvent;
use App\Models\Franchisee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Mail\ApplicationStatusChanged;

class FranchiseApplicationReviewController extends Controller
{
	public function index()
	{
		return app(ApplicationController::class)->index();
	}

	public function show(string $id)
	{
		return app(ApplicationController::class)->show($id);
	}

	public function prequalify(ApplicationTransitionRequest $request, string $id): RedirectResponse
	{
		return app(ApplicationController::class)->prequalify($request, $id);
	}

	public function interview(ApplicationTransitionRequest $request, string $id): RedirectResponse
	{
		return app(ApplicationController::class)->interview($request, $id);
	}

	public function approve(ApplicationTransitionRequest $request, string $id): RedirectResponse
	{
		$app = FranchiseApplication::findOrFail($id);

		// Create franchisee
		$franchisee = new Franchisee();
		$franchisee->id = (string) Str::ulid();
		$franchisee->name = $app->company_name ?? $app->full_name;
		$franchisee->email = $app->email;
		$franchisee->phone = $app->phone;
		$franchisee->save();

		// Link user if exists
		if ($app->user_id) {
			/** @var User $user */
			$user = User::find($app->user_id);
			if ($user) {
				$user->franchisee_id = $franchisee->id;
				$user->save();
				if (method_exists($user, 'assignRole')) {
					$user->assignRole('franchisee');
				}
			}
		}

		// Log event
		FranchiseApplicationEvent::create([
			'id' => (string) Str::ulid(),
			'franchise_application_id' => $app->id,
			'from_status' => $app->status,
			'to_status' => 'approved',
			'message' => $request->input('message'),
		]);

		$app->status = 'approved';
		$app->save();

		// Send decision email
		Mail::to($app->email)->send(new ApplicationStatusChanged(
			['id' => $app->id, 'name' => $app->full_name, 'email' => $app->email],
			$app->getOriginal('status') ?? 'submitted',
			'approved',
			$request->input('message')
		));

		return redirect()->route('bo.applications.show', $id)
			->with('success', __('Candidature approuvée et franchisé créé.'));
	}

	public function reject(ApplicationTransitionRequest $request, string $id): RedirectResponse
	{
		return app(ApplicationController::class)->reject($request, $id);
	}
}
