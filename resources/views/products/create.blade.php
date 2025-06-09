@extends('layouts.app')

@section('title','Додати товар')

@section('content')
<div style="max-width:600px;margin:auto">
  <h1>Додати новий товар</h1>
  @if($errors->any())
    <div class="alert-danger">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
          <label>Назва</label>
          <input type="text" name="name" value="{{ old('name') }}" required>
      </div>
      <div class="form-group">
          <label>Опис</label>
          <textarea name="description">{{ old('description') }}</textarea>
      </div>
      <div class="form-group">
          <label>Ціна</label>
          <input type="number" step="0.01" name="price" value="{{ old('price') }}" required>
      </div>
      <div class="form-group">
          <label>Категорія</label>
          <select name="category_id" required>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
          </select>
      </div>
      <div class="form-group">
          <label>Фото</label>
          <input type="file" name="photo" accept="image/*" required>
      </div>
      <button class="btn" type="submit">Створити</button>
  </form>
</div>
@endsection
