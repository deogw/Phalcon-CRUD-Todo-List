<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    {{ stylesheet_link('css/bootstrap.min.css') }}
    {{ stylesheet_link('css/style.css') }}
    {{ javascript_include('js/all.js') }}
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"
          rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
    <title>Phalcon Todo-List</title>
</head>
<body>
<div class="container">
    <header class="text-center text-light my-4">
        <h1 class="mb-4">What task are on your mind?</h1>
        <form class="search my-4" autocomplete="off">
            <input type="text" autocomplete="off" name="search" class="form-control m-auto" placeholder="Search Todos"/>
        </form>
    </header>
    <ul class="list-group todos mx-auto text-light">
        {% for todo in todos %}
            <tr>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div id="todocontent" data-url="/todo/update" data-pk="{{ todo['id'] }}">{{ todo['content'] }}</div>
                    <i class="far fa-trash-alt delete" id="{{ todo['id'] }}" onClick="deleteTodo(this)"></i>
                </li>
            </tr>
        {% endfor %}

    </ul>
    <form class="add text-center my-4" autocomplete="off">
        <input class="form-control m-auto" type="text" name="add" placeholder="Add new todo..." autocomplete="off" autofocus>
    </form>
</div>

{{ javascript_include('js/jquery.min.js') }}
{{ javascript_include('js/bootstrap.bundle.min.js') }}
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
{{ javascript_include('js/app.js') }}
</body>
</html>