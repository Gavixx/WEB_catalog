@extends('layouts.app')

@section('title', 'Каталог товарів')

@section('content')
  {{-- Пошук + фільтрація --}}
  <form method="GET" action="{{ route('catalog') }}" class="search-filter">
    <div class="form-group">
      <label>Пошук</label>
      <input
        type="text"
        name="search"
        value="{{ $search }}"
        placeholder="За назвою…"
      >
    </div>
    <div class="form-group">
      <label>Категорія</label>
      <select name="category">
        <option value="">Всі категорії</option>
        @foreach($allCategories as $cat)
          <option
            value="{{ $cat->id }}"
            {{ $categoryId == $cat->id ? 'selected' : '' }}
          >
            {{ $cat->name }}
          </option>
        @endforeach
      </select>
    </div>
    <button class="btn" type="submit">Застосувати</button>
    @if($search || $categoryId)
      <a href="{{ route('catalog') }}" class="btn-secondary">Скинути</a>
    @endif
  </form>

  

  {{-- Список категорій для редагування --}}
  {{-- Список категорій для редагування (бачить лише admin) --}}
  @auth
    @if(auth()->user()->role === 'admin')
      <ul class="category-list">
        @foreach($allCategories as $cat)
          <li title="{{ $cat->description }}">
            <a href="{{ route('categories.edit', $cat) }}" class="action">
              {{ $cat->name }}
            </a>
            <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="inline">
              @csrf @method('DELETE')
              <button type="submit" class="delete-btn" aria-label="Видалити {{ $cat->name }}">&times;</button>
            </form>
          </li>
        @endforeach
      </ul>
    @endif
  @endauth


  {{-- Грід товарів (4 у рядку × 3 ряди = 12) --}}
  <div class="grid-auto">
    @foreach($productsQuery->chunk(3) as $chunk)
      <div class="flex space-x-4 mb-6">
        @foreach($chunk as $prod)
          <div class="card-rz w-1/4 bg-white shadow rounded overflow-hidden">
            <div class="img-box h-40">
              @if($prod->photo)
                <img
                  src="{{ asset('storage/'.$prod->photo) }}"
                  alt="{{ $prod->name }}"
                  class="w-full h-full object-cover"
                >
              @else
                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500">
                  Немає фото
                </div>
              @endif
            </div>
            <div class="txt p-4">
              <div class="cat text-sm text-gray-600 mb-1">{{ $prod->category->name }}</div>
              <div class="title font-semibold mb-2">{{ $prod->name }}</div>
              <div class="price-action">
                <span class="price-action__text">
                  ₴{{ number_format($prod->price, 2) }}
                </span>
                <form action="{{ route('cart.add', $prod) }}" method="POST">
                  @csrf
                  <button class="btn price-action__btn" type="submit">Купити</button>
                </form>
              </div>


              <div class="actions flex justify-between">
                @auth
                  @if(auth()->user()->role === 'admin')
                    <a href="{{ route('products.edit', $prod) }}" class="action text-blue-600 hover:underline">
                      Редагувати
                    </a>
                    <form action="{{ route('products.destroy', $prod) }}" method="POST"
                          onsubmit="return confirm('Видалити {{ $prod->name }}?')">
                      @csrf @method('DELETE')
                      <button class="text-red-600 hover:underline">&times;</button>
                    </form>
                  @endif
                @endauth
            </div>
            </div>
          </div>
        @endforeach

        {{-- Якщо у цьому рядку менше за 3 товари — додаємо порожні блоки, щоб вирівняти сітку --}}
        @if($chunk->count() < 3)
          @for($i = $chunk->count(); $i < 4; $i++)
            <div class="w-1/4"></div>
          @endfor
        @endif
      </div>
    @endforeach
  </div>

  {{-- Пейджер унизу --}}
  <div class="pager-wrap">

      {{-- ← Назад --}}
      @if($productsQuery->currentPage() > 1)
          <a  href="{{ $productsQuery->previousPageUrl() }}"
              class="pager-btn load-prev">
              ← Назад
          </a>
      @endif

      {{-- → Завантажити ще --}}
      @if($productsQuery->hasMorePages())
          <a  href="{{ $productsQuery->nextPageUrl() }}"
              class="pager-btn load-more">
              Завантажити ще →
          </a>
      @endif
  </div>
@endsection
