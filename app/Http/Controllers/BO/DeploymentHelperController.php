<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Models\TruckDeployment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DeploymentHelperController extends Controller
{
    public function showOpenForm(string $deployment)
    {
        // First ensure the deployment exists
        $deployment = TruckDeployment::findOrFail($deployment);
        
        // Basic check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to continue');
        }
        
        // Check if deployment is in the right state
        if ($deployment->status !== TruckDeployment::STATUS_PLANNED) {
            return redirect()->back()->with('error', __('deployment.errors.invalid_transition'));
        }
        
        return view('bo.trucks.deployment_open_helper', compact('deployment'));
    }
}
