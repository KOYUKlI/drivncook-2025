<?php

namespace App\Http\Controllers\FO;

use App\Http\Controllers\Controller;
use App\Http\Requests\FO\AccountUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AccountController extends Controller
{
    /**
     * Show the account edit form.
     */
    public function edit(Request $request)
    {
        return view('fo.account.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's account information.
     */
    public function update(AccountUpdateRequest $request)
    {
        $user = $request->user();
        
        $user->update($request->validated());

        // Update locale for the current session if it was changed
        if ($request->has('locale')) {
            app()->setLocale($user->locale);
            session()->put('locale', $user->locale);
        }

        return Redirect::route('fo.account.edit')->with('status', 'account-updated');
    }
}
