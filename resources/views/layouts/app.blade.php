<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Ecommerce L8') }}</title>
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  @stack('styles')
</head>
<body>
  <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm mb-3">
    <div class="container">
      <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Ecommerce L8') }}</a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item"><a class="nav-link" href="{{ route('catalog.index') }}">Katalog</a></li>
          @auth
            <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}">Pesanan Saya</a></li>
          @endauth
          <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}">Keranjang</a></li>
        </ul>

        <ul class="navbar-nav ml-auto">
          @guest
            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
          @else
            @if(auth()->user()->role === 'admin')
              <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Admin</a></li>
            @endif
            <li class="nav-item dropdown">
              <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ Auth::user()->name }}
              </a>

              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              </div>
            </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>

  <main class="py-3">@yield('content')</main>

  <script src="{{ mix('js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>