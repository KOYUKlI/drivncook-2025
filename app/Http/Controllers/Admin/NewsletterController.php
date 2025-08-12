<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsletterRequest;
use App\Http\Requests\UpdateNewsletterRequest;
use App\Models\Newsletter;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    public function index(): View
    {
        $newsletters = Newsletter::orderByDesc('created_at')->get();
        return view('admin.newsletters.index', compact('newsletters'));
    }

    public function create(): View
    {
        return view('admin.newsletters.create');
    }

    public function store(StoreNewsletterRequest $request): RedirectResponse
    {
        Newsletter::create($request->validated());
        return redirect()->route('admin.newsletters.index')->with('success', 'Newsletter created.');
    }

    public function show(Newsletter $newsletter): View
    {
        $newsletter->loadCount('recipients');
        return view('admin.newsletters.show', compact('newsletter'));
    }

    public function edit(Newsletter $newsletter)
    {
        if ($newsletter->sent_at) {
            return redirect()->route('admin.newsletters.show', $newsletter)->with('error','Sent newsletters can\'t be edited.');
        }
        return view('admin.newsletters.edit', compact('newsletter'));
    }

    public function update(UpdateNewsletterRequest $request, Newsletter $newsletter): RedirectResponse
    {
        if ($newsletter->sent_at) {
            return redirect()->route('admin.newsletters.show', $newsletter)->with('error','Sent newsletters can\'t be edited.');
        }
        $newsletter->update($request->validated());
        return redirect()->route('admin.newsletters.show', $newsletter)->with('success','Newsletter updated.');
    }

    public function destroy(Newsletter $newsletter): RedirectResponse
    {
        if ($newsletter->sent_at) {
            return redirect()->route('admin.newsletters.index')->with('error','Sent newsletters can\'t be deleted.');
        }
        $newsletter->delete();
        return redirect()->route('admin.newsletters.index')->with('success','Newsletter deleted.');
    }

    public function send(Newsletter $newsletter): RedirectResponse
    {
        if ($newsletter->sent_at) {
            return redirect()->route('admin.newsletters.show', $newsletter)->with('error','Already sent.');
        }
        $users = User::where('newsletter_opt_in', true)->pluck('id');
        if ($users->isNotEmpty()) {
            $newsletter->recipients()->syncWithoutDetaching($users);
        }
        $newsletter->sent_at = now();
        $newsletter->save();
        return redirect()->route('admin.newsletters.show', $newsletter)->with('success','Newsletter sent to recipients.');
    }
}
