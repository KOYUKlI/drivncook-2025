<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    /**
     * Assign a role to a user
     */
    public function assignRole(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'No authenticated user found'], 401);
        }
        
        // Check if the fleet role exists, create it if not
        $fleetRole = Role::where('name', 'fleet')->first();
        if (!$fleetRole) {
            $fleetRole = Role::create(['name' => 'fleet', 'guard_name' => 'web']);
        }
        
        // Assign the fleet role to the user
        $user->assignRole('fleet');
        
        return response()->json([
            'message' => 'Fleet role assigned successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames()
            ]
        ]);
    }
}
