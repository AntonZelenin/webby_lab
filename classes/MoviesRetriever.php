<?php

class MoviesRetriever
{
    private $pdo;
    private $movies = [];

    public function __construct(DatabasePDO $pdo)
    {
        $this->pdo = $pdo->getConnection();
    }

    public function getAllMovies() : array
    {
        $query = $this->pdo->prepare('SELECT movies.id, movies.name, movies.year, formats.format
            FROM webby_lab_task.movies
            LEFT JOIN webby_lab_task.formats
            ON movies.format = formats.id');
        $query->execute();

        $movie_mapper = new MovieMapper(new DatabasePDO);

        while ($result = $query->fetch()) {
            $movie = $movie_mapper->movieFromArray($result);

            $this->movies[$movie->getId()] = $movie;
        }

        $query = $this->pdo->prepare('SELECT movies_actors.movie_id, actors.first_name, actors.last_name
            FROM webby_lab_task.movies_actors
            LEFT JOIN webby_lab_task.actors
            ON actors.id = actor_id
            ');
        $query->execute();

        $result = $query->fetchAll();

        foreach ($result as $key => $actor) {
            $this->addActorToMovie($actor);
        }

        return $this->movies;
    }

    public function getMoviesByName(string $name) : array
    {
        $query = $this->pdo->prepare('SELECT movies.id, movies.name, movies.year, formats.format
            FROM webby_lab_task.movies
            LEFT JOIN webby_lab_task.formats
            ON movies.format = formats.id
            WHERE movies.name = :name');
        $query->execute(['name' => $name]);

        $movie_mapper = new MovieMapper(new DatabasePDO);
        $movies_id = '';

        while ($result = $query->fetch()) {
            $movie = $movie_mapper->movieFromArray($result);
            $id = $movie->getId();

            $this->movies[$id] = $movie;
            $movies_id .= "$id,";
        }
        $movies_id = substr($movies_id, 0, -1);

        $query = $this->pdo->prepare("SELECT movies_actors.movie_id, actors.first_name, actors.last_name
            FROM webby_lab_task.movies_actors
            LEFT JOIN webby_lab_task.actors
            ON actors.id = actor_id
            WHERE movie_id IN ($movies_id)
            ");
        $query->execute();

        $result = $query->fetchAll();

        foreach ($result as $key => $actor) {
            $this->addActorToMovie($actor);
        }

        return $this->movies;
    }

    public function getMoviesByActor(string $actor) : array
    {
        $temp = explode(' ', trim($actor));
        $first_name = $temp[0];
        $last_name = $temp[1];

        if (!isset($first_name) || !isset($last_name)) {
            return [];
        }

        $query = $this->pdo->prepare("SELECT movies_actors.movie_id, actors.first_name, actors.last_name
            FROM webby_lab_task.movies_actors
            LEFT JOIN webby_lab_task.actors
            ON actors.id = actor_id
            WHERE first_name = :first_name
            AND last_name = :last_name
            ");
        $query->execute(['first_name' => $first_name, 'last_name' => $last_name]);

        $result = $query->fetchAll();
        $movies_id = '';
        foreach ($result as $key => $value) {
            $movies_id .= intval($value['movie_id']).',';
        }
        $movies_id = substr($movies_id, 0, -1);

        $query = $this->pdo->query("SELECT movies.id, movies.name, movies.year, formats.format
            FROM webby_lab_task.movies
            LEFT JOIN webby_lab_task.formats
            ON movies.format = formats.id
            WHERE movies.id IN ($movies_id)");


        $movie_mapper = new MovieMapper(new DatabasePDO);
        $movies_id = '';

        while ($result = $query->fetch()) {
            $movie = $movie_mapper->movieFromArray($result);
            $id = $movie->getId();

            $this->movies[$id] = $movie;
            $movies_id .= "$id,";
        }
        $movies_id = substr($movies_id, 0, -1);

        $query = $this->pdo->prepare("SELECT movies_actors.movie_id, actors.first_name, actors.last_name
            FROM webby_lab_task.movies_actors
            LEFT JOIN webby_lab_task.actors
            ON actors.id = actor_id
            WHERE movie_id IN ($movies_id)
            ");
        $query->execute();

        $result = $query->fetchAll();

        foreach ($result as $key => $actor) {
            $this->addActorToMovie($actor);
        }

        return $this->movies;
    }

    private function addActorToMovie(array $actor)
    {
        $movie_id = $actor['movie_id'];
        $first_name = $actor['first_name'];
        $last_name = $actor['last_name'];

        $this->movies[$movie_id]->addActor(new Actor($first_name, $last_name));
    }
}
