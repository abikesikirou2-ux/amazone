<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Livreur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen grid place-items-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-2xl shadow mb-6 p-6 text-center">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-white/20"><i class="fas fa-motorcycle"></i></div>
            <h1 class="text-2xl font-bold mt-2">Connexion Livreur</h1>
            <p class="text-blue-100">Accédez à votre espace de livraison</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-3 py-2 rounded mb-3 text-sm">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('livreur.login.submit') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input type="password" name="password" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300">
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg py-2.5 font-semibold hover:from-blue-700 hover:to-purple-700 transition">Se connecter</button>
        </form>
        <div class="text-center mt-4 text-sm">
            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Se connecter en tant que client/admin</a>
        </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
