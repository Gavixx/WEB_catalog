<!DOCTYPE html>
<html lang="uk">

<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Мій Каталог')</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

  {{-- header показуємо ТІЛЬКИ коли користувач залогінений --}}
  @if(session()->has('user_id'))
    <header class="site-header">
    <div class="container">
      <h1 class="logo-wrap">
      {{-- окреме посилання-картинка --}}
      <a href="{{ route('catalog') }}" class="logo-img-link">
        <img src="{{ asset('images/logo.svg') }}" alt="Логотип" class="logo-img">
      </a>

      {{-- окреме посилання-текст --}}
      <a href="{{ route('catalog') }}" class="logo-text-link">
        Турбо-Комфорт
      </a>
      </h1>


      {{-- (необов’язково) навігаційні лінки прямо в header --}}
      <nav class="main-nav">
      <a href="{{ route('catalog') }}">Каталог</a>
      <a href="{{ route('cart.index') }}">Корзина</a>
      @if(auth()->user()->role === 'admin')
      <a href="{{ route('products.create') }}">Додати товар</a>
      <a href="{{ route('categories.create') }}">Додати категорію</a>
    @endif
      </nav>

      <div class="user-area">
      <span>{{ auth()->user()->name }}</span>

      <form action="{{ route('logout') }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="btn-secondary">Вийти</button>
      </form>
      </div>
    </div>
    </header>
  @endif


  {{-- top-bar теж тільки для залогінених (у вас вже так було) --}}
  <!-- @if(session()->has('user_id'))
    <nav class="topbar">
    <a href="{{ route('catalog') }}">Каталог</a>

    @if(auth()->check() && auth()->user()->role === 'admin')
    <a href="{{ route('products.create') }}">Додати товар</a>
    <a href="{{ route('categories.create') }}" class="btn">Додати категорію</a>
    @endif

    <span style="margin-left:auto;font-weight:600;">
      {{ auth()->check() ? auth()->user()->name : '' }}
    </span>

    <form action="{{ route('logout') }}" method="POST" class="inline">
      @csrf
      <button type="submit" class="btn-secondary">Вийти</button>
    </form>
    </nav>
  @endif -->

  <main class="px-4 py-3">
    @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
  @endif

    @yield('content')
  </main>

  {{-- footer показуємо лише коли користувач залогінений --}}
  @if(session()->has('user_id'))
    {{-- site-footer.blade.php (можна просто вставити у layouts.app) --}}
    <footer class="site-footer">
    <div class="container footer-grid">
      {{-- Колонка 1 --}}
      <div>
      <h3 class="footer-title">Турбо-Комфорт</h3>
      <p class="footer-text">
        Онлайн-майданчик, де ви можете знайти техніку й аксесуари за
        найкращими цінами. Працюємо щодня 8:00-22:00.
      </p>
      </div>

      {{-- Колонка 2 --}}
      <nav class="footer-nav">
      <h4 class="footer-subtitle">Швидкі посилання</h4>
      <ul>
        <li><a href="{{ route('catalog') }}">Каталог</a></li>
        @auth
        @if(auth()->user()->role === 'admin')
      <li><a href="{{ route('products.create') }}">Додати товар</a></li>
      <li><a href="{{ route('categories.create') }}">Додати категорію</a></li>
      @endif
      @endauth
        <li><a href="mailto:rzhvanskoy@gmail.com">Підтримка</a></li>
      </ul>
      </nav>

      {{-- Колонка 3 --}}
      <div>
      <h4 class="footer-subtitle">Ми в соцмережах</h4>
      <div class="social-wrap">
        <a href="https://www.facebook.com/groups/catsofficial/" aria-label="Facebook" class="social-icon">Fb</a>
        <a href="https://www.instagram.com/cats_of_instagram/" aria-label="Instagram" class="social-icon">Ig</a>
        <a href="https://t.me/s/cats_housewtf" aria-label="Telegram" class="social-icon">Tg</a>
      </div>
      <p class="footer-copy">
        © {{ now()->year }} Турбо-Комфорт
      </p>
      </div>
    </div>
    </footer>

  @endif

  {{-- confirm-delete скрипт залишився без змін --}}
  <script>
    document.querySelectorAll('form button').forEach(btn => {
      if (
        btn.closest('form')?.getAttribute('method') === 'POST' &&
        btn.textContent.trim() === '×'
      ) {
        btn.addEventListener('click', e => {
          e.preventDefault();
          if (confirm('Ви впевнені?')) btn.closest('form').submit();
        });
      }
    });
  </script>
</body>

</html>