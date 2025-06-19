@extends('layouts.app')

@section('title', 'Каталог товарів')

@section('content')
  {{-- ▸ Пошук і фільтр --}}
  <form method="GET" action="{{ route('catalog') }}" class="search-filter">
      <div class="form-group">
          <label>Пошук</label>
          <input type="text" name="search" value="{{ $search }}" placeholder="За назвою…">
      </div>

      <div class="form-group">
          <label>Категорія</label>
          <select name="category">
              <option value="">Всі категорії</option>
              @foreach ($allCategories as $cat)
                  <option value="{{ $cat->id }}" {{ $categoryId==$cat->id?'selected':'' }}>
                      {{ $cat->name }}
                  </option>
              @endforeach
          </select>
      </div>

      <button class="btn">Застосувати</button>

      @if ($search || $categoryId)
          <a href="{{ route('catalog') }}" class="btn-secondary">Скинути</a>
      @endif
  </form>

  {{-- ▸ Список категорій (лише для admin) --}}
  @auth
      @if (auth()->user()->role === 'admin')
          <ul class="category-list">
              @foreach ($allCategories as $cat)
                  <li title="{{ $cat->description }}">
                      <a href="{{ route('categories.edit',$cat) }}" class="action">{{ $cat->name }}</a>
                      <form action="{{ route('categories.destroy',$cat) }}" method="POST" class="inline">
                          @csrf @method('DELETE')
                          <button class="delete-btn" aria-label="Видалити {{ $cat->name }}">&times;</button>
                      </form>
                  </li>
              @endforeach
          </ul>
      @endif
  @endauth

  {{-- ▸ Грід товарів 4 × 3 (12 на сторінку) --}}
  <div class="grid-auto">
      @foreach ($productsQuery->chunk(3) as $row)
          <div class="flex space-x-4 mb-6">
              @foreach ($row as $prod)
                  <div class="card-rz w-1/4">
                      {{-- фото веде на сторінку товару --}}
                      <a href="{{ route('products.show',$prod) }}" class="img-box h-40 block">
                          @if ($prod->photo)
                              <img src="{{ asset('storage/'.$prod->photo) }}" alt="{{ $prod->name }}"
                                   class="w-full h-full object-cover">
                          @else
                              <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500">
                                  Немає&nbsp;фото
                              </div>
                          @endif
                      </a>

                      <div class="txt p-4">
                          <div class="cat text-sm text-gray-600 mb-1">{{ $prod->category->name }}</div>

                          {{-- назва теж посилання на сторінку --}}
                          <a href="{{ route('products.show',$prod) }}" class="title font-semibold mb-2 hover:underline">
                              {{ $prod->name }}
                          </a>

                          {{-- ціна + кнопка купити (для всіх) --}}
                          <div class="price-action">
                              <span class="price-action__text">₴{{ number_format($prod->price,2) }}</span>
                              <form action="{{ route('cart.add',$prod) }}" method="POST">
                                  @csrf
                                  <button class="btn price-action__btn" type="submit">Купити</button>
                              </form>
                          </div>

                          {{-- admin-кнопки --}}
                          @auth
                              @if (auth()->user()->role === 'admin')
                                  <div class="actions flex justify-between mt-3">
                                      <a href="{{ route('products.edit',$prod) }}" class="action text-blue-600 hover:underline">
                                          Редагувати
                                      </a>
                                      <form action="{{ route('products.destroy',$prod) }}" method="POST"
                                            onsubmit="return confirm('Видалити {{ $prod->name }}?')">
                                          @csrf @method('DELETE')
                                          <button class="text-red-600 hover:underline">&times;</button>
                                      </form>
                                  </div>
                              @endif
                          @endauth
                      </div>
                  </div>
              @endforeach

              {{-- якщо у рядку <4 карток – додаємо порожні блоки для вирівнювання --}}
              @for ($i=$row->count(); $i<3; $i++)
                  <div class="w-1/4"></div>
              @endfor
          </div>
      @endforeach
  </div>

  {{-- ▸ Пейджер --}}
  <div class="pager-wrap">
      @if ($productsQuery->currentPage() > 1)
          <a href="{{ $productsQuery->previousPageUrl() }}" class="pager-btn load-prev">← Назад</a>
      @endif

      @if ($productsQuery->hasMorePages())
          <a href="{{ $productsQuery->nextPageUrl() }}" class="pager-btn load-more">Завантажити ще →</a>
      @endif
  </div>
@endsection
