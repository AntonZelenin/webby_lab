function setMovies(data) {
    var main = document.getElementById('main');
    main.innerHTML = '';
    data = JSON.parse(data);

    for (var key in data) {
        id = data[key]['id'];
        name = data[key]['name'];
        year = data[key]['year'];
        format = data[key]['format'];
        actors = '';

        for (var key2 in data[key]['actors']) {
            actors += data[key]['actors'][key2]['first_name'] + data[key]['actors'][key2]['last_name'] + ', ';
        }
        actors = actors.substring(0, actors.length - 2);

        var tmpl = document.getElementById('movie-template').content.cloneNode(true);
        tmpl.querySelector('.movie').setAttribute('movie-id', id);
        tmpl.querySelector('#movie-name-year').innerText = name + "(" + year + ")";
        tmpl.querySelector('.modal').setAttribute('id', id);
        tmpl.querySelector('#movie-name').innerText = name;
        tmpl.querySelector('#movie-id').innerText = "Id: " + id;
        tmpl.querySelector('#release-year').innerText = "Year: " + year;
        tmpl.querySelector('#format').innerText = "Format: " + format;
        tmpl.querySelector('#actors').innerText = "Actors: " + actors;

        main.appendChild(tmpl);
    }
}

function getAll() {
    order = (document.getElementById('order').checked) ? 1 : 0;

    $.get(
        '/scripts/getMovies.php',
        {
            all : 1,
            order: order
        },

        function(data) {
            setMovies(data);
        }
    );
}

$(document).ready(getAll());

function reload() {
    window.location.reload();
}

function reset() {
    window.location = '/';
}

function submitForm(elem){
    var url = "/scripts/getMovies.php";
    var order = (document.getElementById('order').checked) ? 1 : 0;
    var elemData = {};

    $(elem).find("input[name]").each(function (index, node) {
        elemData[node.name] = node.value;
    });

    elemData['order'] = order;

    $.get(url, elemData, function(data) {
        setMovies(data);
    });
}

function showInfo(elem) {
    id = elem.getAttribute('movie-id');
    var modal = document.getElementById(id);

    var span = modal.getElementsByClassName('close')[0];

    modal.style.display = "block";

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}

function close(elem) {
    modal.style.display = "none";
}

function del(elem) {
    var id = elem.getAttribute('movie_id');

    $.post(
        '/scripts/deleteMovie.php',
        {
            id: id
        },
        function(data) {
            window.location.reload();
        }
    );
}
