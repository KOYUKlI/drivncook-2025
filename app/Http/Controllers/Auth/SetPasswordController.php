<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class SetPasswordController extends Controller
{
    public function show(Request $request)
    {
        $request->validate(['id' => ['required','integer']]);
        if (!$request->hasValidSignature()) {
            abort(403, 'Link expired or invalid');
        }
        $user = User::findOrFail((int)$request->query('id'));
        if (!is_null($user->email_verified_at)) {
            abort(410, 'Link already used');
        }
        return view('auth.set-password', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => ['required','integer'],
            'password' => ['required','confirmed','min:8'],
        ]);
        if (!$request->hasValidSignature()) {
            abort(403, 'Link expired or invalid');
        }
        $user = User::findOrFail((int)$request->query('id'));
        if (!is_null($user->email_verified_at)) {
            abort(410, 'Link already used');
        }
        $user->password = Hash::make($request->string('password'));
        $user->email_verified_at = now();
        $user->save();
    \Illuminate\Support\Facades\Auth::login($user);
        return redirect()->to($user->role === 'admin' ? route('admin.dashboard') : route('franchise.dashboard'));
    }
}
