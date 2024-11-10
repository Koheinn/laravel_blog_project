<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                        <li class="nav-item">
                            <a href="{{url("/articles/add")}}" class="nav-link text-success">+ New Article</a>
                        </li>
                        @endauth
                        <!-- Categories Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Categories
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                                <li><a class="dropdown-item" href="{{ url('/articles') }}">All</a></li>
                                @foreach(App\Models\Category::all() as $category)
                                    <li><a class="dropdown-item" href="{{ url('/articles/category/'.$category->id) }}">{{ $category->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
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

        <main class="py-4">
            <div class="container">
                <div class="row">
                    <!-- Check if the current route is NOT login or register before showing the widget -->
                    @if (!Route::is('login') && !Route::is('register') && !Route::is('home'))
                    <div class="col-md-3">
                        <!-- Sidebar Widget -->
                        <div class="card">
                            <div class="card-body">
                                <h5>Search Authors</h5>
                                <input type="text" id="author-search" class="form-control mb-3" placeholder="Search author...">
                                <ul id="author-list" class="list-group" style="display:none">
                                    @foreach($authors as $author)
                                        <li class="list-group-item">
                                            <a href="{{ url('/articles/author/'.$author->id) }}" class="author-link">
                                                {{ $author->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                    @else
                    <div class="py-4"> <!-- Center the content when on login or register -->
                    @endif
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </div>
    <!-- Author Search Script -->
    <script>
        document.getElementById('author-search').addEventListener('input', function() {
            let searchQuery = this.value.toLowerCase();
            let authorList = document.getElementById('author-list');
            let hasResults = false;

            document.querySelectorAll('#author-list li').forEach(function(item) {
                const authorName = item.textContent.toLowerCase();
                if (authorName.includes(searchQuery)) {
                    item.style.display = '';
                    hasResults = true;
                } else {
                    item.style.display = 'none';
                }
            });

            // Only show the list if there are matching authors
            if (hasResults && searchQuery !== '') {
                authorList.style.display = 'block';
            } else {
                authorList.style.display = 'none';
            }
        });
    </script>
</body>
</html>
