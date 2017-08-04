<?php

define ('ROOT', __DIR__.'/..');

require_once ROOT.'/scripts/autoload.php';

if (isset($_POST['name']) && isset($_POST['year']) && isset($_POST['format']) && isset($_POST['actors'])) {
    $name = htmlspecialchars(strip_tags($_POST['name']));
    $year = intval($_POST['year']);
    $format = htmlspecialchars(strip_tags($_POST['format']));
    $actors = explode(',', htmlspecialchars(strip_tags(trim($_POST['actors']))));

    $movie = new Movie;
    $movie->setName($name);
    $movie->setYear($year);
    $movie->setFormat($format);

    foreach ($actors as $key => $full_name) {
        $temp = explode(' ', trim($full_name));

        if(!isset($temp[0]) || !isset($temp[1])) {
            header("Location: /");
            die;
        }

        $movie->addActor(new Actor($temp[0], $temp[1]));
    }

    (new MovieMapper(new DatabasePDO))->save($movie);

}

header("Location: /");
