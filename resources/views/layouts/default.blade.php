<!doctype html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    @include('includes.head')
</head>
<body>
<div class="container">
    <header>
        @include('includes.header')
    </header>
    <br/>
    <?php $URIArray = explode('/', $_SERVER["REQUEST_URI"]) ?>
    @if (('/' == $_SERVER["REQUEST_URI"] || (true == strpos($_SERVER["REQUEST_URI"], 'users') && !isset($URIArray[2]))))
        <a class="btn btn-link" href="{{ route('users.create') }}">New user</a>
    @endif
    <div id="main">
        @yield('content')
    </div>
    <footer class="row">
    </footer>
</div>
</body>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
</html>
