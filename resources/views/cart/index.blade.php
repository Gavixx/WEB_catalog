{{-- resources/views/cart/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Ваша корзина')

@section('content')
  <div class="cart-page">
    <h1 class="cart-title">Корзина</h1>

    @if($items->isEmpty())
      <p class="cart-empty">Ваша корзина порожня.</p>
    @else
      <div class="cart-table-wrapper">
        <table class="table cart-table">
          <thead>
            <tr>
              <th>Товар</th>
              <th>Ціна</th>
              <th>Кількість</th>
              <th>Сума</th>
              <th>Дія</th>
            </tr>
          </thead>
          <tbody>
            @foreach($items as $item)
              <tr>
                <td class="cart-product-cell">
                  <img
                    src="{{ asset('storage/'.$item->product->photo) }}"
                    alt="{{ $item->product->name }}"
                    class="cart-product-img"
                  >
                  <span>{{ $item->product->name }}</span>
                </td>
                <td>₴{{ number_format($item->product->price, 2) }}</td>
                <td>
                  <form
                    action="{{ route('cart.update', $item) }}"
                    method="POST"
                    class="cart-quantity-form"
                  >
                    @csrf
                    @method('PUT')
                    <input
                      type="number"
                      name="quantity"
                      value="{{ $item->quantity }}"
                      min="1"
                      class="cart-quantity-input"
                    >
                    <button
                      type="submit"
                      class="btn-secondary cart-quantity-btn"
                    >OK</button>
                  </form>
                </td>
                <td>₴{{ number_format($item->product->price * $item->quantity, 2) }}</td>
                <td>
                  <form
                    action="{{ route('cart.remove', $item) }}"
                    method="POST"
                    class="cart-remove-form"
                  >
                    @csrf
                    @method('DELETE')
                    <button
                      type="submit"
                      class="cart-remove-btn"
                      aria-label="Видалити {{ $item->product->name }}"
                    >&times;</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3" class="text-right"><strong>Разом:</strong></td>
              <td colspan="2"><strong>₴{{ number_format($total, 2) }}</strong></td>
            </tr>
          </tfoot>
        </table>
      </div>

      <div class="cart-actions">
        <span class="total">Разом: ₴{{ number_format($total, 2) }}</span>

        {{-- Кнопка "Оплатити" --}}
        <a href="{{ route('checkout') }}" class="btn">
          Оплатити
        </a>

        {{-- Кнопка очищення корзини --}}
        <form action="{{ route('cart.clear') }}" method="POST" class="cart-clear-form">
          @csrf
          <button class="btn-secondary btn-clear" type="submit">
            Очистити корзину
          </button>
        </form>
      </div>
    @endif
  </div>
@endsection
