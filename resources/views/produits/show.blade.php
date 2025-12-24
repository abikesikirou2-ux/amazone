@extends('layouts.store')

@section('title', $produit->nom . ' - Mini Amazon')

@section('content')
<div class="container mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <div>
            <div class="relative">
                <div id="carousel" class="w-full overflow-hidden rounded-xl shadow-lg">
                    @php($images = $produit->images->count() ? $produit->images : collect([[ 'url' => $produit->image_url ]]))
                    @foreach($images as $idx => $img)
                        @php($url = is_array($img) ? $img['url'] : $img->url)
                        <img data-slide="{{ $idx }}" src="{{ $url }}" alt="{{ $produit->nom }} {{ $idx+1 }}" class="w-full h-auto object-cover {{ $idx === 0 ? '' : 'hidden' }}">
                    @endforeach
                </div>
                @if($images->count() > 1)
                <div class="flex gap-3 mt-3">
                    @foreach($images as $idx => $img)
                        @php($url = is_array($img) ? $img['url'] : $img->url)
                        <img data-thumb="{{ $idx }}" src="{{ $url }}" class="w-20 h-16 object-cover rounded-lg border cursor-pointer {{ $idx === 0 ? 'ring-2 ring-blue-500' : '' }}" alt="thumb {{ $idx+1 }}">
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        <div>
            <a href="{{ route('produits') }}" class="text-sm text-gray-600 hover:text-blue-600"><i class="fas fa-angle-left mr-1"></i> Retour au catalogue</a>
            <h1 class="text-3xl font-bold mt-2">{{ $produit->nom }}</h1>
            <div class="text-gray-600">{{ $produit->categorie->nom }}</div>

            <div class="flex items-center mt-3">
                @php($moyenne = $produit->moyenneNotes())
                <div class="flex text-yellow-400 mr-2">
                    @for($i=1;$i<=5;$i++)
                        @if($i <= floor($moyenne))
                            <i class="fas fa-star"></i>
                        @elseif($i == ceil($moyenne) && $moyenne - floor($moyenne) >= 0.5)
                            <i class="fas fa-star-half-alt"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </div>
                <span class="text-gray-600 text-sm">({{ $produit->nombreAvis() }} avis)</span>
            </div>

            <div class="text-4xl font-bold text-blue-600 mt-6">
                <span id="prix-affiche">@fcfa($produit->prix)</span>
            </div>

            <p class="text-gray-700 mt-6">{{ $produit->description }}</p>

            <div class="mt-6">
                <form action="{{ route('panier.ajouter') }}" method="POST" class="flex items-center gap-3" id="form-ajout-panier">
                    @csrf
                    <input type="hidden" name="produit_id" value="{{ $produit->id }}">
                    <input type="hidden" name="variante_id" id="variante_id" value="">
                    @if($produit->categorie && \Illuminate\Support\Str::contains(\Illuminate\Support\Str::slug($produit->categorie->nom), 'mode'))
                        @php($variantes = $produit->variantes)
                        @if($variantes->isNotEmpty())
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Taille</label>
                            <select id="select-taille" class="px-3 py-2 border rounded-lg">
                                <option value="" data-prix="{{ number_format((float)$produit->prix, 0, ',', ' ') }}">Sélectionner</option>
                                @foreach($variantes as $v)
                                    <option value="{{ $v->id }}" data-prix="{{ number_format((float)$v->prix, 0, ',', ' ') }}">{{ $v->taille }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    @endif
                    <input type="number" name="quantite" value="1" min="1" max="{{ $produit->stock }}" class="w-24 px-3 py-2 border rounded-lg">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition" {{ $produit->stock == 0 ? 'disabled' : '' }}>
                        <i class="fas fa-cart-plus mr-2"></i> Ajouter au panier
                    </button>
                </form>
                @if($produit->stock > 0)
                    <div class="text-sm text-green-700 mt-2"><i class="fas fa-check-circle mr-1"></i> En stock ({{ $produit->stock }})</div>
                @else
                    <div class="text-sm text-red-600 mt-2"><i class="fas fa-times-circle mr-1"></i> Rupture de stock</div>
                @endif
            </div>
        </div>
    </div>

    @if($produitsLies->isNotEmpty())
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-4">Produits similaires</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($produitsLies as $p)
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <img src="{{ $p->image_url }}" class="w-full h-48 object-cover" alt="{{ $p->nom }}">
                <div class="p-4">
                    <a class="text-gray-600 text-xs" href="{{ route('produit.details', $p->id) }}">{{ $p->categorie->nom }}</a>
                    <h3 class="font-bold"><a href="{{ route('produit.details', $p->id) }}" class="hover:text-blue-600">{{ $p->nom }}</a></h3>
                    <div class="text-blue-600 font-bold">@fcfa($p->prix)</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Carousel thumbs
    document.querySelectorAll('[data-thumb]')?.forEach(function(thumb) {
        thumb.addEventListener('click', function() {
            const idx = this.getAttribute('data-thumb');
            document.querySelectorAll('[data-slide]')?.forEach(function(slide){
                slide.classList.add('hidden');
            });
            const active = document.querySelector('[data-slide="' + idx + '"]');
            if (active) active.classList.remove('hidden');
            document.querySelectorAll('[data-thumb]')?.forEach(function(t){ t.classList.remove('ring-2','ring-blue-500');});
            this.classList.add('ring-2','ring-blue-500');
        });
    });

    // Taille → prix dynamique (FR)
    const selectTaille = document.getElementById('select-taille');
    const spanPrix = document.getElementById('prix-affiche');
    const inputVariante = document.getElementById('variante_id');
    if (selectTaille && spanPrix) {
        selectTaille.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            const prix = opt ? opt.getAttribute('data-prix') : null;
            const id = this.value || '';
            if (prix) {
                spanPrix.textContent = prix + ' FCFA';
            }
            inputVariante.value = id;
        });
    }

    const form = document.getElementById('form-ajout-panier');
    if (form && selectTaille) {
        form.addEventListener('submit', function(e){
            if (!selectTaille.value) {
                e.preventDefault();
                alert('Veuillez sélectionner une taille.');
            }
        });
    }
});
</script>
@endsection
