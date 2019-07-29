$.fn.editable.defaults.mode = 'inline';
$.fn.editable.defaults.ajaxOptions = {type: "PUT"};
const addForm = document.querySelector(".add")
const list = document.querySelector(".todos")
const search = document.querySelector(".search input")

$(document).ready(function () {
    $("[id='todocontent']").editable({
        url: 'todo/update',
        showbuttons: false,
        send: 'always'
    });
});

const generateTemplate = (todo, id) => {
    const currentTodoList = list.innerHTML;
    const html = `
<li class="list-group-item d-flex justify-content-between align-items-center animated bounceInDown faster">
<div id="todocontent" data-url="/todo/update" data-pk="${id}">${todo}</div>
<i class="far fa-trash-alt delete" id="${id}" onClick="deleteTodo(this)"></i>
</li>`;
    list.innerHTML = html + currentTodoList;
    list.addEventListener('animationend', function (event) {
        event.target.classList.remove('animated', 'bounceInDown', 'faster');
    });
}

const refreshTodo = () => {
    $("[id='todocontent']").editable({
        url: 'todo/update',
        showbuttons: false,
        send: 'always'
    });

}

const addTodo = todo => {
    let todoData = {
        'todo': todo,
    };

    $.ajax({
        type: "post",
        url: 'todo/add',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(todoData),
        success: (response) => {
            if (response.success) {
                if (todo.length) {
                    generateTemplate(todo, response.id);
                    addForm.reset();
                    refreshTodo();
                }
            } else {
                alert(response.message)
            }
        },
        error: (jqXHR, exception) => {

        },
    });
}
addForm.addEventListener("submit", e => {
    e.preventDefault();
    const todo = addForm.add.value.trim();
    addTodo(todo);
})

const deleteTodo = (target) => {
    target.parentElement.classList.add('animated', 'fadeOutLeft', 'faster');
    target.parentElement.addEventListener('animationend', function () {
        target.parentElement.remove();
    });

    let todoData = {
        'id': target.id,
    };

    $.ajax({
        type: "delete",
        url: 'todo/delete',
        dataType: 'json',
        data: JSON.stringify(todoData),
        success: (response) => {
            if (response.success) {

            } else {
                alert(response.message)
            }
        },
        error: (jqXHR, exception) => {

        },
    });

}

const filterTodos = (term) => {
    Array.from(list.children).filter((todo) => {
        return !todo.textContent.toLowerCase().includes(term)
    }).forEach((todo) => {
        todo.classList.add("filtered")
    });
    Array.from(list.children).filter((todo) => {
        return todo.textContent.toLowerCase().includes(term)
    }).forEach((todo) => {
        todo.classList.remove("filtered")
    });

}

search.addEventListener("keyup", () => {
    const term = search.value.trim().toLowerCase();
    filterTodos(term);
})