@extends('layouts.app')
@section('title','Увійти')
@section('content')
<div style="max-width:400px;margin:2rem auto;background:#fff;padding:1.5rem;border-radius:.5rem;box-shadow:var(--shadow);">
    <h1>Увійти</h1>
    @if(session('error'))
      <div class="alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input id="password" type="password" name="password" required>
        </div>
        <button class="btn" type="submit">Увійти</button>
        <a href="{{ route('register') }}" class="action">Реєстрація</a>
    </form>
</div>
@endsection
