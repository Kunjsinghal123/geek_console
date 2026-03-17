<!DOCTYPE html> 
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="gradient" data-menu-styles="dark">

<head>

    @include('partials.head')

</head>

<body>

    @include('partials.switcher')
    @include('partials.loader')

    <div class="page">

        @include('partials.navbar')
        @include('partials.sidebar')

        <main>

            @yield('content')

        </main>

        @include('partials.footer')

    </div>

    @include('partials.scroll-to-top')
    @include('partials.scripts')

</body>
</html>