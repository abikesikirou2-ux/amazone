<div class="bg-white rounded-xl shadow p-4">
    <div class="font-bold mb-2">Espace client</div>
    <ul class="space-y-2 text-sm">
        <li>
            <a href="{{ route('compte') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-user mr-2"></i> Mon compte
            </a>
        </li>
        <li>
            <a href="{{ route('commandes') }}" class="inline-flex items-center text-gray-700 hover:text-gray-900">
                <i class="fas fa-box mr-2"></i> Mes commandes
            </a>
        </li>
        <li>
            <a href="{{ route('panier') }}" class="inline-flex items-center text-gray-700 hover:text-gray-900">
                <i class="fas fa-shopping-cart mr-2"></i> Mon panier
            </a>
        </li>
        <li>
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center text-gray-700 hover:text-gray-900">
                <i class="fas fa-user-cog mr-2"></i> Profil
            </a>
        </li>
    </ul>
</div>
