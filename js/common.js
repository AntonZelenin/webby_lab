$(document).ready(getAll());

function getAll() {
    $("#browse-label").empty();
    var lable_text = ($('#file').val().split('\\').pop() == '') ? 'No file selected' : ($('#file').val().split('\\').pop());
    $('#browse-label').append(lable_text);

    $.get(
        '/scripts/getMovies.php',
        {
            all : 1,
        },

        function(data) {
            var movies = getMovies(data);

            show_movies(movies);
        }
    );
}

function getMovies(data) {
    var main = document.getElementById('main');
    data = JSON.parse(data);

    var movies = [];

    for (var key in data) {
        id = data[key]['id'];
        name = data[key]['name'];
        year = data[key]['year'];
        format = data[key]['format'];
        actors = '';

        for (var key2 in data[key]['actors']) {
            actors += data[key]['actors'][key2]['first_name'] + " " + data[key]['actors'][key2]['last_name'] + ', ';
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

        movies.push(tmpl);
    }

    return movies;
}

function show_movies(movies) {
    main.innerHTML = '';

    for (var i = 0; i < movies.length; i++) {
        main.appendChild(movies[i]);
    }
}

function sort_movies(movies) {
    movies = Array.prototype.slice.call(movies);

    movies.sort(function(a, b) {
        var string_a = $(a).find("span").text();
        var string_b = $(b).find("span").text();

        return string_a.localeCompare(string_b);
    });

    return movies;
}

function order_list() {
    var movies = document.getElementsByClassName('movie-wrapper');

    movies = sort_movies(movies);
    show_movies(movies);
}

function reset() {
    window.location = '/';
}

function submitForm(elem){
    var url = "/scripts/getMovies.php";
    var elemData = {};

    $(elem).find("input[name]").each(function (index, node) {
        elemData[node.name] = node.value;
    });

    $.get(url, elemData, function(data) {
        var movies = getMovies(data);
        show_movies(movies);
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
    var id = elem.getAttribute('movie-id');

    $.ajax({
        type: "POST",
        url: '/scripts/deleteMovie.php',
        data: {
            id: id
        },
        success: function(){
            $(elem).remove();
        }
    });
}



$("#uploadTrigger").click(function(){
   $("#file").click();
});

$('#file').change(function(){
    $("#browse-label").empty();
    $('#browse-label').append($('#file').val().split('\\').pop());
});
