<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', "Driv'n Cook") }} — Franchise de food trucks</title>
    <meta name="description" content="Driv'n Cook, la franchise de food trucks clé en main : concept, accompagnement, approvisionnement, outils digitaux et data pour développer votre activité.">
    <meta property="og:title" content="Driv'n Cook — Franchise de food trucks">
    <meta property="og:description" content="Rejoignez une franchise de food trucks moderne et rentable. Concept éprouvé, accompagnement 360°, app de pilotage.">
    <meta property="og:type" content="website">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>/* Fallback minimal si Vite indisponible */ body{font-family:ui-sans-serif,system-ui,sans-serif;margin:0;color:#0f172a} .container{max-width:1200px;margin:0 auto;padding:0 1rem} .btn{display:inline-flex;align-items:center;gap:.5rem;border-radius:.75rem;font-weight:600;padding:.9rem 1.1rem;text-decoration:none} .btn-primary{background:#f59e0b;color:#111827} .btn-secondary{background:#111827;color:#fff} .muted{color:#64748b} header,section{padding:4.5rem 0} .grid{display:grid;gap:1.75rem} .cards{grid-template-columns:repeat(auto-fit,minmax(260px,1fr))} .card{background:#fff;border:1px solid #e5e7eb;border-radius:1rem;padding:1.25rem} nav{border-bottom:1px solid #e5e7eb} .hero{padding:6rem 0;background:linear-gradient(180deg,#fff, #fff7ed)} footer{border-top:1px solid #e5e7eb;padding:2rem 0} </style>
    @endif
</head>
<body class="bg-white text-gray-900">
    <!-- Header -->
    <nav class="bg-white/80 backdrop-blur border-b border-gray-200">
        <div class="container h-16 flex items-center justify-between">
            <a href="{{ auth()->check() ? (auth()->user()->role === 'admin' ? route('admin.dashboard') : route('franchise.dashboard')) : url('/') }}" class="flex items-center gap-2 font-semibold">
                <span class="inline-block h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                <span>Driv'n Cook</span>
            </a>
            <div class="hidden md:flex items-center gap-6 text-sm">
                <a href="#concept" class="text-gray-600 hover:text-gray-900">Concept</a>
                <a href="#avantages" class="text-gray-600 hover:text-gray-900">Avantages</a>
                <a href="#franchise" class="text-gray-600 hover:text-gray-900">La franchise</a>
                <a href="#temoignages" class="text-gray-600 hover:text-gray-900">Témoignages</a>
                <a href="#contact" class="text-gray-600 hover:text-gray-900">Contact</a>
            </div>
            <div class="flex items-center gap-3 text-sm">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Accéder au dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary">Se connecter</a>
                    <a href="{{ route('franchise.apply') }}" class="btn btn-primary">Devenir franchisé</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <header class="hero relative overflow-hidden py-24">
        <div class="container grid md:grid-cols-2 gap-10 items-center">
            <div>
                <p class="text-amber-600 font-semibold mb-2">Franchise de food trucks</p>
                <h1 class="text-4xl md:text-5xl font-bold leading-tight">Lancez votre activité avec un concept clé en main</h1>
                <p class="mt-4 text-gray-600 max-w-xl">Concept éprouvé, accompagnement opérationnel, approvisionnement centralisé et outils digitaux pour piloter votre flotte en temps réel.</p>
                @guest
                <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('franchise.apply') }}" class="btn btn-primary">Devenir franchisé</a>
                    <a href="#contact" class="btn btn-secondary">Parler à un expert</a>
                </div>
                @endguest
                <div class="mt-6 text-xs text-gray-500">Contrat transparent • Implantation accompagnée • Formation initiale et continue</div>
            </div>
            <div class="relative rounded-xl overflow-hidden shadow-xl border border-gray-200">
                <img
                    src="https://images.unsplash.com/photo-1514933651103-005eec06c04b?q=80&w=1600&auto=format&fit=crop"
                    alt="Food truck moderne et convivial"
                    class="w-full h-full object-cover aspect-[16/10]">
            </div>
        </div>
    </header>

    <!-- Le concept -->
    <section id="concept" class="bg-gray-50 py-24">
        <div class="container grid md:grid-cols-2 gap-10 items-center">
            <div class="order-2 md:order-1">
                <div class="rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                    <img
                        src="https://images.unsplash.com/photo-1559339352-11d035aa65de?q=80&w=1600&auto=format&fit=crop"
                        alt="Préparation et service rapide en food truck"
                        class="w-full h-full object-cover aspect-[16/10]">
                </div>
            </div>
            <div class="order-1 md:order-2">
                <h2 class="text-2xl md:text-3xl font-bold">Un modèle pensé pour l’itinérance</h2>
                <ul class="mt-4 space-y-3 text-gray-700">
                    <li>• Planning des emplacements et déploiement des camions</li>
                    <li>• Carte maîtrisée, fiches techniques et coûts matières</li>
                    <li>• Commandes clients et paiements suivis au siège</li>
                    <li>• Maintenance et conformité centralisées</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Avantages clés -->
    <section id="avantages" class="bg-white py-24">
        <div class="container">
            <h2 class="text-2xl md:text-3xl font-bold text-center">Pourquoi Driv'n Cook ?</h2>
            <p class="text-center text-gray-600 mt-3 max-w-2xl mx-auto">Des outils professionnels pour une exploitation sereine et rentable.</p>
            <div class="max-w-3xl mx-auto mt-12 space-y-8">
                <div class="flex items-start gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="text-amber-600 w-6 h-6 shrink-0"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd"/></svg>
                    <div>
                        <h3 class="font-semibold text-lg">Pilotage en temps réel</h3>
                        <p class="mt-2 text-gray-600">Tableau de bord, KPIs, ventes, coûts matières, suivi maintenance et conformité.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="text-amber-600 w-6 h-6 shrink-0"><path d="M11.7 2.004a.75.75 0 0 1 .6 0l8.25 3.75a.75.75 0 0 1 0 1.372l-8.25 3.75a.75.75 0 0 1-.6 0l-8.25-3.75a.75.75 0 0 1 0-1.372l8.25-3.75Z"/><path d="M3.26 10.227 11.1 13.8a1.5 1.5 0 0 0 1.2 0l7.84-3.573V15a.75.75 0 0 1-.45.69l-8.25 3.75a.75.75 0 0 1-.6 0l-8.25-3.75A.75.75 0 0 1 3 15v-4.773c.084.02.172.02.26 0Z"/></svg>
                    <div>
                        <h3 class="font-semibold text-lg">Approvisionnement centralisé</h3>
                        <p class="mt-2 text-gray-600">Catalogue validé, commandes stock simplifiées, traçabilité des lots.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="text-amber-600 w-6 h-6 shrink-0"><path fill-rule="evenodd" d="M7.5 6.75a4.5 4.5 0 1 1 9 0v1.5h.75A2.25 2.25 0 0 1 19.5 10.5v7.125A2.25 2.25 0 0 1 17.25 19.875H6.75A2.25 2.25 0 0 1 4.5 17.625V10.5a2.25 2.25 0 0 1 2.25-2.25H7.5v-1.5Zm1.5 1.5v-1.5a3 3 0 1 1 6 0v1.5H9Z" clip-rule="evenodd"/></svg>
                    <div>
                        <h3 class="font-semibold text-lg">Support & formation</h3>
                        <p class="mt-2 text-gray-600">Onboarding, formations régulières, accompagnement marketing et opérationnel.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="text-amber-600 w-6 h-6 shrink-0"><path d="M9 2.25A.75.75 0 0 1 9.75 3v3.75a.75.75 0 0 1-1.5 0V3A.75.75 0 0 1 9 2.25Z"/><path fill-rule="evenodd" d="M6.25 6.75A3.75 3.75 0 0 1 10 3h4a3.75 3.75 0 0 1 3.75 3.75v11A2.25 2.25 0 0 1 15.5 20H8.5a2.25 2.25 0 0 1-2.25-2.25v-11Zm5.25 6a.75.75 0 0 0-1.5 0v3a.75.75 0 0 0 1.5 0v-3Z" clip-rule="evenodd"/></svg>
                    <div>
                        <h3 class="font-semibold text-lg">Technologie intégrée</h3>
                        <p class="mt-2 text-gray-600">Application web moderne, rôles Admin/Franchise, sécurité et performance.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- La franchise -->
    <section id="franchise" class="bg-white py-24">
        <div class="container">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold">La franchise Driv'n Cook</h2>
                <p class="mt-3 text-gray-600">Accédez à une marque forte, une centrale d’achat et une suite logicielle pour piloter votre activité où que vous soyez.</p>
            </div>
            <div class="max-w-3xl mx-auto mt-12 space-y-8">
                <div class="flex items-start gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="text-amber-600 w-6 h-6 shrink-0"><path d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75S6.615 21.75 12 21.75 21.75 17.385 21.75 12 17.385 2.25 12 2.25Z"/><path fill-rule="evenodd" d="M12 6.75a.75.75 0 0 1 .75.75v6.69l3.03 1.746a.75.75 0 1 1-.75 1.298l-3.375-1.944a.75.75 0 0 1-.375-.649V7.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd"/></svg>
                    <div>
                        <h3 class="font-semibold text-lg">Marque & marketing</h3>
                        <p class="mt-2 text-gray-600">Identité, kit de communication, campagnes locales et digitales.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="text-amber-600 w-6 h-6 shrink-0"><path fill-rule="evenodd" d="M12 3.75A8.25 8.25 0 1 0 20.25 12 8.259 8.259 0 0 0 12 3.75Zm3.53 6.22a.75.75 0 1 0-1.06-1.06L11 12.38l-1.47-1.47a.75.75 0 0 0-1.06 1.06l2 2a.75.75 0 0 0 1.06 0l4-4Z" clip-rule="evenodd"/></svg>
                    <div>
                        <h3 class="font-semibold text-lg">Outils & data</h3>
                        <p class="mt-2 text-gray-600">Dashboard unifié, reporting, indicateurs de performance clés.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="text-amber-600 w-6 h-6 shrink-0"><path d="M2.25 6.75A2.25 2.25 0 0 1 4.5 4.5h15a2.25 2.25 0 0 1 2.25 2.25v3A2.25 2.25 0 0 1 19.5 12H4.5a2.25 2.25 0 0 1-2.25-2.25v-3Z"/><path d="M2.25 14.25A2.25 2.25 0 0 1 4.5 12h8.25a2.25 2.25 0 0 1 2.25 2.25v3A2.25 2.25 0 0 1 12.75 19.5H4.5a2.25 2.25 0 0 1-2.25-2.25v-3Z"/></svg>
                    <div>
                        <h3 class="font-semibold text-lg">Achats & logistique</h3>
                        <p class="mt-2 text-gray-600">Référencement fournisseurs, prix négociés, qualité assurée.</p>
                    </div>
                </div>
            </div>
        <!-- CTA supprimée ici pour éviter les doublons avec le hero et le footer -->
        </div>
    </section>

    <!-- Témoignages -->
    <section id="temoignages" class="bg-gray-50 py-24">
        <div class="container">
            <h2 class="text-2xl md:text-3xl font-bold text-center">Ils en parlent</h2>
            <div class="max-w-3xl mx-auto mt-10 space-y-10">
                <figure>
                    <blockquote class="text-gray-700 italic text-lg">“En 6 mois, j’ai ouvert mon premier truck et atteint mes objectifs. L’app simplifie tout.”</blockquote>
                    <figcaption class="mt-3 text-sm text-gray-500">— Laura, franchisée à Lyon</figcaption>
                </figure>
                <figure>
                    <blockquote class="text-gray-700 italic text-lg">“Le pilotage des stocks et la centrale d’achat sont des game changers.”</blockquote>
                    <figcaption class="mt-3 text-sm text-gray-500">— Karim, franchisé à Lille</figcaption>
                </figure>
                <figure>
                    <blockquote class="text-gray-700 italic text-lg">“Accompagnement sérieux et marque forte, on se sent moins seuls.”</blockquote>
                    <figcaption class="mt-3 text-sm text-gray-500">— Clara, franchisée à Bordeaux</figcaption>
                </figure>
            </div>
        </div>
    </section>

    <!-- Contact / CTA -->
    <section id="contact" class="bg-amber-50 py-24">
        <div class="container text-center">
            <h2 class="text-2xl md:text-3xl font-bold">Prêt à démarrer ?</h2>
            <p class="mt-2 text-gray-700">Parlez à un expert pour étudier votre projet d’implantation.</p>
            @guest
            <div class="mt-6 flex gap-3 justify-center">
                <a href="mailto:contact@drivncook.example" class="btn btn-secondary">Nous contacter</a>
            </div>
            @endguest
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200">
        <div class="container flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-500">© {{ date('Y') }} Driv'n Cook. Tous droits réservés.</div>
            <div class="flex items-center gap-4 text-sm">
                <a href="#" class="text-gray-600 hover:text-gray-900">Mentions légales</a>
                <a href="#" class="text-gray-600 hover:text-gray-900">Confidentialité</a>
            </div>
        </div>
    </footer>
</body>
</html>

