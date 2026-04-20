<!DOCTYPE html> 
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">
    
<head>

    @include('partials.minimal.head')

</head>

<body>

    <main>

        @yield('content')

    </main>

   @include('partials.minimal.scripts')

</body>

</html>