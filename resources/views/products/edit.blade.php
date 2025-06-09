@extends('layouts.app')

@section('title','Редагувати товар')

@section('content')
<div style="max-width:600px;margin:auto">
  <h1>Редагувати: {{ $product->name }}</h1>
  @if($errors->any())
    <div class="alert-danger">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('products.update',$product) }}" method="POST" enctype="multipart/form-data">
      @csrf @method('PUT')
      <div class="form-group">
          <label>Назва</label>
          <input type="text" name="name" value="{{ old('name',$product->name) }}" required>
      </div>
      <div class="form-group">
          <label>Опис</label>
          <textarea name="description">{{ old('description',$product->description) }}</textarea>
      </div>
      <div class="form-group">
          <label>Ціна</label>
          <input type="number" step="0.01" name="price" value="{{ old('price',$product->price) }}" required>
      </div>
      <div class="form-group">
          <label>Категорія</label>
          <select name="category_id" required>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ $cat->id==$product->category_id?'selected':'' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
      </div>
      <div class="form-group">
          <label>Поточне фото</label>
          <div class="img-box">
            <img src="{{ asset('storage/'.$product->photo) }}" alt="{{ $product->name }}">
          </div>
      </div>
      <div class="form-group">
          <label>Нове фото (опційно)</label>
          <input type="file" name="photo" accept="image/*">
      </div>
      <button class="btn" type="submit">Оновити</button>
  </form>
</div>
@endsection
