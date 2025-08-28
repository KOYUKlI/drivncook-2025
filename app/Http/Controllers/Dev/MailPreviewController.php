<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Mail\FranchiseApplicationNewAdminAlert;
use App\Mail\FranchiseApplicationStatusChanged;
use App\Mail\FranchiseApplicationSubmitted;
use App\Mail\FranchiseeOnboardingWelcome;
use App\Mail\MonthlySalesReportReady;
use App\Mail\PurchaseOrderCreated;
use App\Mail\PurchaseOrderStatusUpdated;
use App\Mail\TruckMaintenanceClosed;
use App\Mail\TruckMaintenanceOpened;
use App\Models\FranchiseApplication;
use App\Models\Franchisee;
use App\Models\MaintenanceLog;
use App\Models\PurchaseOrder;
use App\Models\Truck;
use Illuminate\Http\Request;

class MailPreviewController extends Controller
{
    public function __construct()
    {
        abort_unless(app()->environment('local'), 404);
        $this->middleware('auth');
    }

    public function index()
    {
        $mailables = [
            'franchise-application-submitted' => 'Candidature soumise',
            'franchise-application-status-changed' => 'Statut candidature modifié',
            'franchisee-onboarding-welcome' => 'Bienvenue franchisé',
            'franchise-application-admin-alert' => 'Alerte admin nouvelle candidature',
            'purchase-order-created' => 'Commande créée',
            'purchase-order-status-updated' => 'Statut commande mis à jour',
            'truck-maintenance-opened' => 'Maintenance ouverte',
            'truck-maintenance-closed' => 'Maintenance fermée',
            'monthly-sales-report-ready' => 'Rapport mensuel disponible',
        ];

        return view('dev.mail.index', compact('mailables'));
    }

    public function preview(Request $request, string $mailable)
    {
        $mail = match ($mailable) {
            'franchise-application-submitted' => $this->getApplicationSubmittedMail(),
            'franchise-application-status-changed' => $this->getApplicationStatusChangedMail(),
            'franchisee-onboarding-welcome' => $this->getOnboardingWelcomeMail(),
            'franchise-application-admin-alert' => $this->getApplicationAdminAlertMail(),
            'purchase-order-created' => $this->getPurchaseOrderCreatedMail(),
            'purchase-order-status-updated' => $this->getPurchaseOrderStatusUpdatedMail(),
            'truck-maintenance-opened' => $this->getTruckMaintenanceOpenedMail(),
            'truck-maintenance-closed' => $this->getTruckMaintenanceClosedMail(),
            'monthly-sales-report-ready' => $this->getMonthlySalesReportReadyMail(),
            default => abort(404),
        };

        return $mail->render();
    }

    private function getApplicationSubmittedMail()
    {
        $application = new FranchiseApplication([
            'id' => '01K3QFJH3MX58A0RQCK42EAJTT',
            'full_name' => 'Alex Martin',
            'email' => 'alex.martin@example.com',
            'phone' => '06 12 34 56 78',
            'desired_area' => 'Paris 15ème',
            'status' => 'submitted',
        ]);
        $application->setCreatedAt(now());

        return new FranchiseApplicationSubmitted($application);
    }

    private function getApplicationStatusChangedMail()
    {
        $application = new FranchiseApplication([
            'id' => '01K3QFJH3MX58A0RQCK42EAJTT',
            'full_name' => 'Alex Martin',
            'email' => 'alex.martin@example.com',
            'phone' => '06 12 34 56 78',
            'desired_area' => 'Paris 15ème',
            'status' => 'prequalified',
        ]);
        $application->setCreatedAt(now());

        return new FranchiseApplicationStatusChanged(
            $application,
            'submitted',
            'prequalified',
            'Votre profil correspond à nos critères. Nous vous contacterons prochainement pour un entretien.'
        );
    }

    private function getOnboardingWelcomeMail()
    {
        $franchisee = new Franchisee([
            'id' => '01K3QFJH3MX58A0RQCK42EAJTT',
            'name' => 'Alex Martin',
            'email' => 'alex.martin@drivncook.com',
            'phone' => '06 12 34 56 78',
            'billing_address' => '123 Rue de la Paix, 75001 Paris',
        ]);

        return new FranchiseeOnboardingWelcome($franchisee, 'TempPassword123!');
    }

    private function getApplicationAdminAlertMail()
    {
        $application = new FranchiseApplication([
            'id' => '01K3QFJH3MX58A0RQCK42EAJTT',
            'full_name' => 'Sophie Dubois',
            'email' => 'sophie.dubois@example.com',
            'phone' => '06 98 76 54 32',
            'desired_area' => 'Lyon Centre',
            'status' => 'submitted',
        ]);
        $application->setCreatedAt(now());

        return new FranchiseApplicationNewAdminAlert($application);
    }

    private function getPurchaseOrderCreatedMail()
    {
        // Mock purchase order with lines
        $po = new PurchaseOrder([
            'id' => '01K3QFJH3MX58A0RQCK42EAJTT',
            'number' => 'PO-2025-001',
            'total_cents' => 125000,
            'status' => 'pending',
        ]);
        $po->setCreatedAt(now());

        return new PurchaseOrderCreated($po);
    }

    private function getPurchaseOrderStatusUpdatedMail()
    {
        $po = new PurchaseOrder([
            'id' => '01K3QFJH3MX58A0RQCK42EAJTT',
            'number' => 'PO-2025-001',
            'total_cents' => 125000,
            'status' => 'approved',
        ]);

        return new PurchaseOrderStatusUpdated($po, 'pending', 'approved', 'Commande validée par l\'équipe logistique');
    }

    private function getTruckMaintenanceOpenedMail()
    {
        $truck = new Truck([
            'id' => '01K3QFJH3MX58A0RQCK42EAJTT',
            'plate' => 'AA-123-DC',
            'status' => 'InMaintenance',
        ]);

        $maintenanceLog = new MaintenanceLog([
            'id' => '01K3QFJH3MX58A0RQCK42EAJTT',
            'kind' => 'preventive',
            'description' => 'Révision 30 000 km',
        ]);
        $maintenanceLog->started_at = now();
        $maintenanceLog->setRelation('truck', $truck);

        return new TruckMaintenanceOpened($maintenanceLog);
    }

    private function getTruckMaintenanceClosedMail()
    {
        $truck = new Truck([
            'id' => '01K3QFJH3MX58A0RQCK42EAJTT',
            'plate' => 'AA-123-DC',
            'status' => 'Active',
        ]);

        $maintenanceLog = new MaintenanceLog([
            'id' => '01K3QFJH3MX58A0RQCK42EAJTT',
            'kind' => 'preventive',
            'description' => 'Révision 30 000 km',
            'completed_work' => 'Vidange moteur, remplacement filtres, contrôle freins, diagnostic électronique complet.',
        ]);
        $maintenanceLog->started_at = now()->subDay();
        $maintenanceLog->closed_at = now();
        $maintenanceLog->setRelation('truck', $truck);

        return new TruckMaintenanceClosed($maintenanceLog);
    }

    private function getMonthlySalesReportReadyMail()
    {
        $franchisee = new Franchisee([
            'id' => '01K3QFJH3MX58A0RQCK42EAJTT',
            'name' => 'Alex Martin',
            'email' => 'alex.martin@drivncook.com',
        ]);

        return new MonthlySalesReportReady($franchisee, 'Janvier 2025', 'secure-token-123456');
    }
}
