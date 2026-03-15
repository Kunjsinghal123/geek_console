<!DOCTYPE html> 
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="gradient" data-menu-styles="dark">

<head>

    @include('partials.head')

</head>

<body>

    <div class="page">

        @include('partials.navbar')
        @include('partials.sidebar')

        <main>

            @yield('content')

        </main>

        @include('partials.footer')

    </div>

    @include('partials.scripts')

</body>
</html>