@extends('layouts.app')
@section('title', $product->name)

@section('content')
{{-- ADMIN бачить форму редагування та видалення --}}
@auth
@if(auth()->user()->role === 'admin')
    <h1 class="mb-4 text-xl font-bold">Редагувати товар</h1>
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="grid gap-4 max-w-xl">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Назва</label>
            <input type="text" name="name" value="{{ old('name',$product->name) }}" required>
        </div>
        <div class="form-group">
            <label>Ціна</label>
            <input type="number" step="0.01" name="price" value="{{ old('price',$product->price) }}" required>
        </div>
        <div class="form-group">
            <label>Категорія</label>
            <select name="category_id">
                @foreach(\App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}" {{ $product->category_id==$cat->id?'selected':'' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Фото</label>
            <input type="file" name="photo">
        </div>
        <div class="form-group">
            <label>Опис</label>
            <textarea name="description" rows="4">{{ old('description',$product->description) }}</textarea>
        </div>
        <button class="btn w-max">Зберегти</button>
        <form action="{{ route('products.destroy',$product) }}" method="POST" onsubmit="return confirm('Видалити?')" class="inline">
            @csrf @method('DELETE')
            <button class="btn-secondary" style="background:#dc3545;color:#fff">Видалити</button>
        </form>
    </form>

@else  {{-- ===== ЗВИЧАЙНИЙ КОРИСТУВАЧ ===== --}}
    <div class="product-page">
        <div class="img-big">
            @if($product->photo)
                <img src="{{ asset('storage/'.$product->photo) }}" alt="{{ $product->name }}">
            @else
                <div class="placeholder">Немає&nbsp;фото</div>
            @endif
        </div>

        <div class="info">
            <h1 class="name">{{ $product->name }}</h1>
            <p class="cat">Категорія: {{ $product->category->name }}</p>
            <p class="price">₴{{ number_format($product->price,2) }}</p>

            @if($product->description)
                <h3>Опис</h3>
                <p class="desc">{{ $product->description }}</p>
            @endif
            <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-4">
                @csrf
                <button class="btn">Купити</button>
            </form>
        </div>
    </div>
@endif
@endauth
@endsection
