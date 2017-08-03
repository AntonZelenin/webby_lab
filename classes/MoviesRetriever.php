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
        $this->getMovies();

        if (empty($this->movies)) {
            return [];
        }

        $movies_actors = $this->getMoviesActors();

        $this->distributeActors($movies_actors);

        return $this->movies;
    }

    public function getMoviesByName(string $name) : array
    {
        $this->moviesByName($name);

        if (empty($this->movies)) {
            return [];
        }

        $movies_actors = $this->getMoviesActors();

        $this->distributeActors($movies_actors);

        return $this->movies;
    }

    public function getMoviesByActor(string $actor) : array
    {
        $temp = explode(' ', trim($actor));

        if (!isset($temp[0]) || !isset($temp[1])) {
            return [];
        }

        $first_name = $temp[0];
        $last_name = $temp[1];

        $movies_id = $this->moviesIdByActor($first_name, $last_name);

        if (empty($movies_id)) {
            return [];
        }

        $this->getMoviesIn($movies_id);

        $movies_actors = $this->getMoviesActors();

        $this->distributeActors($movies_actors);

        return $this->movies;
    }

    private function getMovies()
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
    }

    private function getMoviesActors() : array
    {
        $movies_id = $this->getMoviesIdStr();

        $query = $this->pdo->query("SELECT movies_actors.movie_id, actors.first_name, actors.last_name
            FROM webby_lab_task.movies_actors
            LEFT JOIN webby_lab_task.actors
            ON actors.id = actor_id
            WHERE movie_id IN ($movies_id)
            ");

        return $query->fetchAll();
    }

    private function distributeActors($actors)
    {
        foreach ($actors as $key => $actor) {
            $this->addActorToMovie($actor);
        }
    }

    private function addActorToMovie(array $actor)
    {
        $movie_id = $actor['movie_id'];
        $first_name = $actor['first_name'];
        $last_name = $actor['last_name'];

        $this->movies[$movie_id]->addActor(new Actor($first_name, $last_name));
    }

    private function moviesByName(string $name)
    {
        $query = $this->pdo->prepare('SELECT movies.id, movies.name, movies.year, formats.format
            FROM webby_lab_task.movies
            LEFT JOIN webby_lab_task.formats
            ON movies.format = formats.id
            WHERE movies.name = :name');
        $query->execute(['name' => $name]);

        $movie_mapper = new MovieMapper(new DatabasePDO);

        while ($result = $query->fetch()) {
            $movie = $movie_mapper->movieFromArray($result);
            $id = $movie->getId();

            $this->movies[$id] = $movie;
        }
    }

    private function moviesIdByActor(string $first_name, string $last_name) : string
    {
        $query = $this->pdo->prepare("SELECT movies_actors.movie_id
            FROM webby_lab_task.movies_actors
            LEFT JOIN webby_lab_task.actors
            ON actors.id = actor_id
            WHERE first_name = :first_name
            AND last_name = :last_name
            ");
            $query->execute(['first_name' => $first_name, 'last_name' => $last_name]);

            return $this->moviesIdToString($query->fetchAll());
    }

    private function moviesIdToString(array $movies_id) : string
    {
        $movies_id_str = '';
        foreach ($movies_id as $key => $value) {
            $movies_id_str .= intval($value['movie_id']).',';
        }

        return substr($movies_id_str, 0, -1);
    }

    private function getMoviesIdStr()
    {
        $movies_id = '';
        foreach ($this->movies as $key => $movie) {
            $movies_id .= "$key,";
        }

        return substr($movies_id, 0, -1);
    }

    private function getMoviesIn($movies_id)
    {
        $query = $this->pdo->query("SELECT movies.id, movies.name, movies.year, formats.format
            FROM webby_lab_task.movies
            LEFT JOIN webby_lab_task.formats
            ON movies.format = formats.id
            WHERE movies.id IN ($movies_id)");

        $movie_mapper = new MovieMapper(new DatabasePDO);

        while ($result = $query->fetch()) {
            $movie = $movie_mapper->movieFromArray($result);
            $id = $movie->getId();

            $this->movies[$id] = $movie;
        }
    }
}
