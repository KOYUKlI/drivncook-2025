<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FranchiseUserController extends Controller
{
    public function create(Franchise $franchise): View
    {
        return view('admin.franchisees.users.create', compact('franchise'));
    }

    public function store(Request $request, Franchise $franchise): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')],
            'role' => ['required', Rule::in(['franchise','employee'])],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'franchise_id' => $franchise->id,
            'password' => bcrypt(str()->random(16)),
        ]);

        Password::sendResetLink(['email' => $user->email]);

        return redirect()->route('admin.franchisees.show', $franchise)->with('success', 'User created and invitation sent.');
    }
}
