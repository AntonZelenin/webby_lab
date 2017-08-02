<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define ('ROOT', __DIR__);

require 'autoload.php';

$pdo = new DatabasePDO;
$movies_retriever = new MoviesRetriever($pdo);
$movies;

if (isset($_POST['name'])) {
    $name = strip_tags($_POST['name']);
    $name = htmlspecialchars($name);

    $movies = $movies_retriever->getMoviesByName($name);
} elseif (isset($_POST['actor'])) {
    $actor = strip_tags($_POST['actor']);
    $actor = htmlspecialchars($actor);

    $movies = $movies_retriever->getMoviesByActor($actor);
} else {
    $movies = (new MoviesRetriever($pdo))->getAllMovies();
}

 ?>

 <html>
     <head>
         <title>Movies</title>
         <link rel="stylesheet" type="text/css" href="style.css" />
         <script src="/js/jquery-3.2.1.js"></script>
     </head>

     <body>
         <div class="sidebar">
             <form action="/scripts/addMovie.php" method="post" class="add-movie">
                 Movie Title:<br />
                 <input type="text" name="name" placeholder="e.g. Matrix" required /><br />
                 Release Year:<br />
                 <input type="number" min='1900' max='2017' name="year" placeholder="e.g. 1999" required /><br />
                 Format: <br />
                 <input type="text" name="format" placeholder="e.g. DVD" required /><br />
                 Stars:<br />
                 <input type="text" name="actors" placeholder="e.g. Keanu Reeves, Laurence Fishburne" required /><br />

                 <input type="Submit" value="Add Movie" />
             </form>



             <form action="index.php" method="post">
                 Search by movie name:<br />
                 <input type="text" name='name' placeholder="e.g. Jaws" required />
                 <input type="submit" value="Search" />
             </form>

             <form action="index.php" method="post">
                 Search by actor name:<br />
                 <input type="text" name='actor' placeholder="e.g. Robert Shaw" required />
                 <input type="submit" value="Search" />
             </form>

             <form action="/upload.php" method="post" class="upload" enctype="multipart/form-data">
                 Select file to upload:
                 <input type="file" name="file" id="file" required >
                 <input type="submit" value="Upload" name="submit">
             </form>

             <input type="button" value="Show all" onclick="reset()" />
         </div>

        <div class="main">
            <?php

            foreach ($movies as $key => $movie) {
                $id = $movie->getId();
                $name = $movie->getName();
                $year = $movie->getYear();
                $format = $movie->getFormat();
                $actors = $movie->getActorsStr();

                echo "<div class='movie' movie_id='$id'>
                                <span onclick='showInfo(this.parentElement)'>$name ($year)</span>
                                <span class='del' id='delete' onclick='del(this.parentElement)'>&times;</span>
                            </div>".
                            "<div id='$id' class='modal'>
                              <div class='modal-content'>
                                <div class='modal-header'>
                                  <span class='close'>&times;</span>
                                  <h2>$name</h2>
                                </div>
                                <div class='modal-body'>
                                  <p>ID: $id</p>
                                  <p>Release Year: $year</p>
                                  <p>Format: $format</p>
                                  <p>Actors: $actors</p>
                                </div>
                              </div>

                            </div>";
            }

            ?>
        </div>

        <script>

            function reset() {
                window.location = '/';
            }

            function showInfo(elem) {
                id = elem.getAttribute('movie_id');
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
        </script>

     </body>

 </html>
