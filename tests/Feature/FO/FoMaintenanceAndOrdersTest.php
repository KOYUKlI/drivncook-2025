<?php

use App\Models\Franchisee;
use App\Models\MaintenanceLog;
use App\Models\PurchaseOrder;
use App\Models\Truck;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // optional: use sqlite file already configured; avoid RefreshDatabase to keep speed
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'warehouse']);
    Role::firstOrCreate(['name' => 'fleet']);
    Role::firstOrCreate(['name' => 'franchisee']);

    $this->frUser = User::factory()->create();
    $this->frUser->assignRole('franchisee');
    // Franchisee relation is via email mapping
    $this->fr = Franchisee::factory()->create(['email' => $this->frUser->email]);

    // Current truck owned by franchisee
    $this->truck = Truck::factory()->create();
    // Ownership uses Truck model boot hook or relation; ensure current owner
    if (method_exists($this->truck, 'ownerships')) {
        $this->truck->ownerships()->create([
            'franchisee_id' => $this->fr->id,
            'started_at' => now()->subDay(),
        ]);
    } else {
        $this->truck->franchisee_id = $this->fr->id;
        $this->truck->save();
    }
});

it('lists FO orders for franchisee and shows one order', function () {
    // Create an order belonging to franchisee
    /** @var PurchaseOrder $po */
    $po = PurchaseOrder::factory()->create([
        'kind' => 'Replenishment',
        'franchisee_id' => $this->fr->id,
        'status' => 'Delivered',
    ]);

    $res = $this->actingAs($this->frUser)->get(route('fo.orders.index'));
    $res->assertOk();
    $res->assertSee((string)($po->reference ?? $po->id));

    $show = $this->actingAs($this->frUser)->get(route('fo.orders.show', $po->id));
    $show->assertOk();
    $show->assertSee((string)($po->reference ?? $po->id));
});

it('blocks franchisee from viewing other franchisee order', function () {
    $otherFr = Franchisee::factory()->create();
    $po = PurchaseOrder::factory()->create([
        'kind' => 'Replenishment',
        'franchisee_id' => $otherFr->id,
        'status' => 'Delivered',
    ]);

    $res = $this->actingAs($this->frUser)->get(route('fo.orders.show', $po->id));
    $res->assertStatus(403);
});

it('allows BL download when shipped/delivered and blocks missing files', function () {
    Storage::fake('public');

    $po = PurchaseOrder::factory()->create([
        'kind' => 'Replenishment',
        'franchisee_id' => $this->fr->id,
        'status' => 'Shipped',
        'shipped_at' => now(),
    ]);

    $path = 'replenishments/'.$po->id.'/BL-'.($po->reference ?? $po->id).'.pdf';
    Storage::disk('public')->put($path, 'PDF');

    $ok = $this->actingAs($this->frUser)->get(route('fo.orders.delivery-note', $po->id));
    $ok->assertOk();

    Storage::disk('public')->delete($path);
    $missing = $this->actingAs($this->frUser)->get(route('fo.orders.delivery-note', $po->id));
    $missing->assertNotFound();
});

it('lists maintenance logs and creates a maintenance request via truck endpoint with private attachment', function () {
    Storage::fake('private');

    // Pre-existing log bound to the truck
    MaintenanceLog::factory()->create([
        'truck_id' => $this->truck->id,
        'status' => MaintenanceLog::STATUS_PLANNED,
    ]);

    $index = $this->actingAs($this->frUser)->get(route('fo.maintenance.index'));
    $index->assertOk();

    // Create via TruckController single endpoint (no duplicate under /maintenance)
    $file = UploadedFile::fake()->create('issue.jpg', 10, 'image/jpeg');
    $create = $this->actingAs($this->frUser)->post(route('fo.truck.maintenance-request'), [
        'title' => 'Door latch broken',
        'type' => 'corrective',
        'description' => 'Back door latch is broken and won\'t close properly.',
        'attachment' => $file,
    ]);
    $create->assertRedirect(route('fo.truck.show'));
    $create->assertSessionHas('success');
});

it('allows viewing own maintenance log and forbids others\' logs', function () {
    $own = MaintenanceLog::factory()->create([
        'truck_id' => $this->truck->id,
        'status' => MaintenanceLog::STATUS_OPEN,
    ]);

    $otherFr = Franchisee::factory()->create();
    $otherTruck = Truck::factory()->create();
    if (method_exists($otherTruck, 'ownerships')) {
        $otherTruck->ownerships()->create(['franchisee_id' => $otherFr->id, 'started_at' => now()->subDay()]);
    } else {
        $otherTruck->franchisee_id = $otherFr->id; $otherTruck->save();
    }
    $foreign = MaintenanceLog::factory()->create([
        'truck_id' => $otherTruck->id,
        'status' => MaintenanceLog::STATUS_OPEN,
    ]);

    $ok = $this->actingAs($this->frUser)->get(route('fo.maintenance.show', $own));
    $ok->assertOk();

    $forbidden = $this->actingAs($this->frUser)->get(route('fo.maintenance.show', $foreign));
    $forbidden->assertStatus(403);
});

it('allows secure download of own maintenance attachment and forbids others', function () {
    Storage::fake('private');

    // Own log + attachment
    $own = MaintenanceLog::factory()->create([
        'truck_id' => $this->truck->id,
        'status' => MaintenanceLog::STATUS_OPEN,
    ]);

    $path = 'maintenance/'.$own->id.'/photo.jpg';
    Storage::disk('private')->put($path, 'IMG');

    $attachment = \App\Models\MaintenanceAttachment::create([
        'maintenance_log_id' => $own->id,
        'label' => 'photo.jpg',
        'path' => $path,
        'mime_type' => 'image/jpeg',
        'size_bytes' => 3,
        'uploaded_by' => $this->frUser->id,
    ]);

    $ok = $this->actingAs($this->frUser)->get(route('fo.maintenance.attachment', [$own, $attachment]));
    $ok->assertOk();

    // Foreign log + attachment
    $otherFr = Franchisee::factory()->create();
    $otherTruck = Truck::factory()->create();
    if (method_exists($otherTruck, 'ownerships')) {
        $otherTruck->ownerships()->create(['franchisee_id' => $otherFr->id, 'started_at' => now()->subDay()]);
    } else {
        $otherTruck->franchisee_id = $otherFr->id; $otherTruck->save();
    }
    $foreign = MaintenanceLog::factory()->create([
        'truck_id' => $otherTruck->id,
        'status' => MaintenanceLog::STATUS_OPEN,
    ]);
    $foreignAttachment = \App\Models\MaintenanceAttachment::create([
        'maintenance_log_id' => $foreign->id,
        'label' => 'secret.jpg',
        'path' => 'maintenance/'.$foreign->id.'/secret.jpg',
        'mime_type' => 'image/jpeg',
        'size_bytes' => 5,
        'uploaded_by' => $this->frUser->id,
    ]);
    Storage::disk('private')->put($foreignAttachment->path, 'IMG2');

    $forbidden = $this->actingAs($this->frUser)->get(route('fo.maintenance.attachment', [$foreign, $foreignAttachment]));
    $forbidden->assertStatus(403);
});
