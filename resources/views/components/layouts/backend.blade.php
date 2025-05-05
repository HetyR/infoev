<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Backend - InfoEV</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <meta name="robots" content="noindex, nofollow">
    {{ $css ?? '' }}
</head>

<body>

    <div class="container pb-5">
        @auth
            <div class="row mb-5">
                <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#">Menu</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link @if (Route::is('backend.type.index')) active @endif" @if (Route::is('backend.type.index')) aria-current="page" @endif href="{{ route('backend.type.index') }}">Types</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link @if (Route::is('backend.brand.index')) active @endif" @if (Route::is('backend.brand.index')) aria-current="page" @endif href="{{ route('backend.brand.index') }}">Brands</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link @if (Route::is('backend.blog.index')) active @endif" @if (Route::is('backend.blog.index')) aria-current="page" @endif href="{{ route('backend.blog.index') }}">Blogs</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link @if (Route::is('backend.stickyArticle.index')) active @endif" @if (Route::is('backend.stickyArticle.index')) aria-current="page" @endif href="{{ route('backend.stickyArticle.index') }}">Sticky Articles</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link @if (Route::is('backend.spec.index')) active @endif" @if (Route::is('backend.spec.index')) aria-current="page" @endif href="{{ route('backend.spec.index') }}">Specifications</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link @if (Route::is('backend.vehicle.index')) active @endif" @if (Route::is('backend.vehicle.index')) aria-current="page" @endif href="{{ route('backend.vehicle.index') }}">Vehicles</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link @if (Route::is('backend.marketplace.index')) active @endif" @if (Route::is('backend.marketplace.index')) aria-current="page" @endif href="{{ route('backend.marketplace.index') }}">Marketplaces</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link @if (Route::is('backend.comment.index')) active @endif" @if (Route::is('backend.comment.index')) aria-current="page" @endif href="{{ route('backend.comment.index') }}">Comments</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link @if (Route::is('backend.option.index')) active @endif" @if (Route::is('backend.option.index')) aria-current="page" @endif href="{{ route('backend.option.index') }}">Settings</a>
                                </li>
                                <li class="nav-item">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();this.closest('form').submit();">Logout</a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        @endauth

        {{ $slot }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    {{ $js ?? '' }}
</body>

</html>
