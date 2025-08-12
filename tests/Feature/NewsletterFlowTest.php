<?php

use App\Models\User;
use App\Models\Newsletter;
use function Pest\Laravel\{actingAs, post, get};

it('allows admin to create and send a newsletter', function() {
    $admin = User::factory()->create(['role'=>'admin']);
    actingAs($admin);
    // create
    $resp = post(route('admin.newsletters.store'), [
        'subject' => 'Promo',
        'body' => 'Hello world',
        'scheduled_at' => null,
        '_token' => csrf_token()
    ]);
    $resp->assertRedirect(route('admin.newsletters.index'));
    $newsletter = Newsletter::first();
    expect($newsletter)->not()->toBeNull();
    // opt-in user
    $u1 = User::factory()->create(['newsletter_opt_in'=>true]);
    // send
    $sendResp = post(route('admin.newsletters.send',$newsletter), ['_token'=>csrf_token()]);
    $sendResp->assertRedirect(route('admin.newsletters.show',$newsletter));
    $newsletter->refresh();
    expect($newsletter->sent_at)->not()->toBeNull();
    expect($newsletter->recipients()->count())->toBe(1);
});
