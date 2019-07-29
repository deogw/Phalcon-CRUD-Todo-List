<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    {{ stylesheet_link('css/bootstrap.min.css') }}
    {{ stylesheet_link('css/style.css') }}
    {{ javascript_include('js/all.js') }}
    <title>Phalcon Todo-List</title>
</head>
<body>
<div class="container">
    <header class="text-center text-light my-4">
        <h1 class="mb-4">What task are on your mind?</h1>
        <form class="search" autocomplete="off">
            <input type="text" autocomplete="off" name="search" class="form-control m-auto" placeholder="Search Todos" />
        </form>
    </header>
    <ul class="list-group todos mx-auto text-light">
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>Play Mariokart</span>
            <i class="far fa-trash-alt delete"></i>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>Defeat ganon in zelda</span>
            <i class="far fa-trash-alt delete"></i>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>Make a veggie pie</span>
            <i class="far fa-trash-alt delete"></i>
        </li>
    </ul>
    <form class="add text-center my-4">
        <input class="form-control m-auto" type="text" name="add" placeholder="Add new todo...">
    </form>
</div>

{#{% block content %}#}
{#{% endblock %}#}
{{ javascript_include('js/jquery.min.js') }}
{{ javascript_include('js/bootstrap.bundle.min.js') }}
{{ javascript_include('js/app.js') }}
</body>
</html>