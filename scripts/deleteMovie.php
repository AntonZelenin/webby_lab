<?php

define ('ROOT', __DIR__.'/..');

require_once ROOT.'/scripts/autoload.php';

if (isset($_POST['id'])) {
    $movie_id = intval($_POST['id']);

    $database = new DatabasePDO;
    $query = $database->getConnection()->prepare('DELETE FROM webby_lab_task.movies WHERE id = :id');
    $query->execute(['id' => $movie_id]);
}

header("Location: /");
