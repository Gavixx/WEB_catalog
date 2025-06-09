@extends('layouts.app')

@section('title','Додати категорію')

@section('content')
<div style="max-width:400px;margin:2rem auto;background:#fff;padding:1.5rem;border-radius:.5rem;box-shadow:var(--shadow);">
  <h1>Нова категорія</h1>

  {{-- Помилки валідації --}}
  @if($errors->any())
    <div class="alert-danger">
      <ul>
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('categories.store') }}" method="POST">
    @csrf
    <div class="form-group">
      <label>Назва</label>
      <input type="text" name="name" value="{{ old('name') }}" required>
    </div>
    <div class="form-group">
      <label>Опис</label>
      <input type="text" name="description" value="{{ old('description') }}">
    </div>
    <button class="btn" type="submit">Створити</button>
    <a href="{{ route('catalog') }}" class="btn-secondary" style="margin-left:.5rem;">Відмінити</a>
  </form>
</div>
@endsection
