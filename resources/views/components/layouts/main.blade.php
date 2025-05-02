<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    {{ $meta }}
    {{ $css ?? '' }}
    @if (env('APP_ENV') == 'production')
        @if (!is_null(env('GTM_ID')) && !empty(env('GTM_ID')))
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{{ env('GTM_ID') }}');</script>
        @endif
    @endif
</head>
<body class="bg-stone-700 bg-opacity-5">
    @if (env('APP_ENV') == 'production')
        @if (!is_null(env('GTM_ID')) && !empty(env('GTM_ID')))
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ env('GTM_ID') }}" height="0" width="0"
                    style="display:none;visibility:hidden"></iframe></noscript>
        @endif
    @endif
    {{-- Header --}}
    {{ $header ?? '' }}

    {{-- End Header --}}

    {{-- Main Container --}}
    <main class="container grid mx-auto pt-4 bg-white md:grid-cols-4">
        {{-- Sidebar --}}
        <aside class="my-10 pb-2 space-y-5 border-r border-zinc-200 md:my-0 md:pr-2 md:space-y-4 md:row-start-1">
            {{ $sidebar ?? '' }}
        </aside>
        {{-- End Siderbar --}}

        {{-- Main Content --}}
        <section class="flex flex-col row-start-1 bg-white min-w-0 md:col-span-3">
            {{ $slot }}
        </section>
        {{-- End Main Content --}}
    </main>
    {{-- End Main Container --}}

    {{-- Footer --}}
    {{ $footer ?? '' }}
    {{-- End Footer --}}

    <script>document.querySelector('#year').innerText = new Date().getFullYear()</script>
    {{ $js ?? '' }}
</body>
</html>
