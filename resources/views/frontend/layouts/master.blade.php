<!doctype html>
<html>
<head>
    @include('frontend.layouts.head')
</head>
<body class="bg-white min-h-screen font-sans">
    @include('frontend.layouts.header')
    
        @yield('content')
    
    @include('frontend.layouts.footer')
</body>
</html>