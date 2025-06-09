@extends('layouts.app')

@section('title','Редагувати категорію')

@section('content')
<div style="max-width:400px;margin:auto">
  <h1>Редагувати: {{ $category->name }}</h1>
  @if($errors->any())
    <div class="alert-danger">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('categories.update',$category) }}" method="POST">
      @csrf @method('PUT')
      <div class="form-group">
          <label>Назва</label>
          <input type="text" name="name" value="{{ old('name',$category->name) }}" required>
      </div>
      <div class="form-group">
          <label>Опис</label>
          <input type="text" name="description" value="{{ old('description',$category->description) }}">
      </div>
      <button class="btn" type="submit">Зберегти</button>
  </form>
</div>
@endsection
