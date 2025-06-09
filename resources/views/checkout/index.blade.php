@extends('layouts.app')

@section('title', 'Оформлення замовлення')

@section('content')
  <div class="checkout-page">
    <h1 class="checkout-title">Оформлення замовлення</h1>

    @if($items->isEmpty())
      <p class="checkout-empty">Ваша корзина порожня.</p>
      <p><a href="{{ route('catalog') }}" class="btn">Повернутися до каталогу</a></p>
    @else
      <ul class="checkout-list">
        @foreach($items as $item)
          <li class="checkout-item">
            {{ $item->product->name }} × {{ $item->quantity }}
            — ₴{{ number_format($item->product->price * $item->quantity,2) }}
          </li>
        @endforeach
      </ul>
      <p class="checkout-total"><strong>Всього: ₴{{ number_format($total,2) }}</strong></p>

      <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <button class="btn" type="submit">Підтвердити та оплатити</button>
        <a href="{{ route('cart.index') }}" >Назад до корзини</a>
      </form>
    @endif
  </div>
@endsection
