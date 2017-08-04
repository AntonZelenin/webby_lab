<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define ('MAX_FILE_SIZE', 1000000);

require 'autoload.php';

$permitted = 'text/plain';

if ($_FILES['file']['type'] != $permitted) {
    die("Not permitted filetype");
}
if ($_FILES['file']['size'] >= MAX_FILE_SIZE) {
    die("File is too large");
}

$contents = strip_tags(file_get_contents($_FILES["file"]["tmp_name"]));

preg_match_all('/Title: (.+).*\s*Release Year: (\d+).*\s*Format: (.+).*\s*Stars: (.+).*\s*/', $contents, $out);

$movies = [];

foreach ($out[1] as $key => $value) {
    $movie = new Movie;

    $movie->setName($out[1][$key]);
    $movie->setYear($out[2][$key]);
    $movie->setFormat($out[3][$key]);

    $actors = explode(',', $out[4][$key]);
    foreach ($actors as $key => $full_name) {
        $temp = explode(' ', trim($full_name));
        $movie->addActor(new Actor($temp[0], $temp[1]));
    }

    $movies[] = $movie;
}

$movie_mapper = new MovieMapper(new DatabasePDO);

foreach ($movies as $key => $movie) {
    $movie_mapper->save($movie);
}

header("Location: /");
