<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define ('ROOT', __DIR__.'/..');

require (ROOT.'/scripts/autoload.php');

// function cmp($a, $b)
// {
//     return strcmp($a->getName(), $b->getName());
// }

$pdo = new DatabasePDO;
$movies_retriever = new MoviesRetriever($pdo);
$movies;
// $order = false;

if (isset($_GET['all'])) {
    $movies = $movies_retriever->getAllMovies();
} elseif (isset($_GET['name'])) {
    $name = strip_tags($_GET['name']);
    $name = htmlspecialchars($name);

    $movies = $movies_retriever->getMoviesByName($name);
} elseif (isset($_GET['actor'])) {
    $actor = strip_tags($_GET['actor']);
    $actor = htmlspecialchars($actor);

    $movies = $movies_retriever->getMoviesByActor($actor);
}

// if ($_GET['order']) {
//     usort($movies, "cmp");
// }

foreach ($movies as $movie_id => $movie) {
    $movies[$movie_id] = $movie->toArray();
}

echo json_encode($movies);
