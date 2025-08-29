<x-guest-layout>
    @php
        $user = \App\Models\User::where('email', $request->email)->first();
        $isFranchisee = $user && $user->hasRole('franchisee');
        $franchisee = $isFranchisee ? $user->franchisee : null;
    @endphp

    <div class="mb-6 text-center">
        @if($isFranchisee && $franchisee)
            <h2 class="text-2xl font-bold text-gray-900 mb-2">🎉 Bienvenue {{ $franchisee->name }} !</h2>
            <p class="text-gray-600 mb-4">Définissez votre mot de passe sécurisé pour accéder à votre espace franchisé Driv'n Cook.</p>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <p class="text-blue-800 text-sm">
                    <strong>🚚 Franchisé DrivnCook</strong><br>
                    Votre compte a été créé suite à l'approbation de votre candidature.
                </p>
            </div>
        @else
            <h2 class="text-xl font-bold text-gray-900 mb-2">{{ __('Reset Password') }}</h2>
            <p class="text-gray-600 mb-4">Définissez un nouveau mot de passe pour votre compte.</p>
        @endif
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" readonly />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="$isFranchisee ? 'Votre mot de passe' : __('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            @if($isFranchisee)
                <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères, incluez majuscules, minuscules et chiffres</p>
            @endif
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="$isFranchisee ? 'Confirmez votre mot de passe' : __('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button>
                @if($isFranchisee)
                    🔐 Définir mon mot de passe
                @else
                    {{ __('Reset Password') }}
                @endif
            </x-primary-button>
        </div>

        @if($isFranchisee)
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">
                    Après avoir défini votre mot de passe, vous pourrez accéder à votre espace franchisé complet.
                </p>
            </div>
        @endif

        <!-- Success Messages -->
        @if (session('status'))
            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-green-700 text-sm font-medium">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Global Error Messages -->
        @if ($errors->any() && !$errors->has('email') && !$errors->has('password') && !$errors->has('password_confirmation'))
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-red-700 text-sm font-medium">Erreur lors de la définition du mot de passe</p>
                        <ul class="text-red-600 text-sm mt-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </form>

    <script>
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            const isLongEnough = password.length >= 8;
            
            let strength = 0;
            if (hasUpper) strength++;
            if (hasLower) strength++;
            if (hasNumber) strength++;
            if (hasSpecial) strength++;
            if (isLongEnough) strength++;
            
            // Remove existing indicator
            const existingIndicator = document.getElementById('password-strength');
            if (existingIndicator) {
                existingIndicator.remove();
            }
            
            if (password.length > 0) {
                const indicator = document.createElement('div');
                indicator.id = 'password-strength';
                indicator.className = 'mt-2 text-xs';
                
                let color, text;
                if (strength < 3) {
                    color = 'text-red-600';
                    text = 'Mot de passe faible';
                } else if (strength < 4) {
                    color = 'text-orange-600';
                    text = 'Mot de passe moyen';
                } else {
                    color = 'text-green-600';
                    text = 'Mot de passe fort';
                }
                
                indicator.className += ' ' + color;
                indicator.textContent = text;
                
                e.target.parentNode.appendChild(indicator);
            }
        });
    </script>
</x-guest-layout>
