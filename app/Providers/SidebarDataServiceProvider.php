<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\Franchisee;
use App\Models\Truck;

class SidebarDataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Injecter des données dans la sidebar pour les utilisateurs authentifiés
        View::composer('layouts.partials.sidebar', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                
                // Données pour la sidebar (seulement pour les admins pour éviter les requêtes inutiles)
                $sidebarData = [];
                
                if ($user->hasRole('admin')) {
                    try {
                        // Compter les applications en attente
                        $sidebarData['applications_count'] = Application::whereIn('status', ['pending', 'prequalified'])->count();
                        
                        // Compter les franchisés actifs
                        $sidebarData['franchisees_count'] = Franchisee::whereHas('user')->count();
                        
                        // Compter les trucks
                        $sidebarData['trucks_count'] = Truck::count();
                    } catch (\Exception $e) {
                        // En cas d'erreur de base de données, valeurs par défaut
                        $sidebarData['applications_count'] = 0;
                        $sidebarData['franchisees_count'] = 0;
                        $sidebarData['trucks_count'] = 0;
                    }
                } else {
                    // Pour les autres rôles, données basiques
                    $sidebarData['applications_count'] = 0;
                    $sidebarData['franchisees_count'] = 0;
                    $sidebarData['trucks_count'] = 0;
                }
                
                $view->with('sidebarData', $sidebarData);
            }
        });
    }
}
