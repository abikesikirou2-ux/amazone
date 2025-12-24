<x-guest-layout>
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-2xl shadow mb-6 p-6">
        <h1 class="text-2xl font-bold">Connexion</h1>
        <p class="text-blue-100">Accédez à votre espace client Mini Amazon</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Adresse e-mail -->
            <div>
                <x-input-label for="email" value="E-mail" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Mot de passe -->
            <div>
                <x-input-label for="password" value="Mot de passe" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        Mot de passe oublié ?
                    </a>
                @endif
                <a class="underline text-sm text-blue-600 hover:text-blue-800" href="{{ route('register') }}">
                    Créer un compte client
                </a>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-2.5 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition">
                Se connecter
            </button>

            <div class="flex items-center justify-between mt-2">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ url('/livreur/login') }}">
                    Espace livreur
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
