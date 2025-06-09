@extends('layouts.app')

@section('title','Реєстрація')

@section('content')
<div style="max-width:400px;margin:2rem auto;background:#fff;padding:1.5rem;border-radius:.5rem;box-shadow:var(--shadow);">
    <h1>Реєстрація</h1>
    @if($errors->any())
      <div class="alert-danger">
        <ul>
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Ім’я</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input id="password" type="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Підтвердження</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>
        <button class="btn" type="submit">Зареєструватися</button>
        <a href="{{ route('login') }}" class="action">Увійти</a>
    </form>
</div>
@endsection
