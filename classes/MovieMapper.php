<?php

class MovieMapper
{
    private $pdo;

    public function __construct(DatabasePDO $pdo)
    {
        $this->pdo = $pdo->getConnection();
    }

    public function save(Movie $movie)
    {
        $this->saveFormat($movie->getFormat());
        $this->saveActors($movie->getActors());
        $this->saveMovie($movie);
        $movie_id = $this->pdo->lastInsertId();
        $this->saveMovieActors($movie_id, $movie->getActors());
    }

    private function saveFormat($format)
    {
        $query = $this->pdo->prepare('INSERT IGNORE INTO webby_lab_task.formats (format) VALUES (:format)');
        $query->execute(['format' => $format]);
    }

    private function saveActors(array $actors)
    {
        $actors = $this->actors2query($actors);

        $query = $this->pdo->query("INSERT IGNORE INTO webby_lab_task.actors (first_name, last_name) VALUES $actors");
    }

    private function actors2query(array $actors) : string
    {
        $actors_values = '';

        foreach ($actors as $key => $actor) {
            $first_name = $this->pdo->quote($actor->getFirstName());
            $last_name = $this->pdo->quote($actor->getLastName());
            $actors_values .= "($first_name, $last_name), ";
        }

        return substr($actors_values, 0, -2);
    }

    private function saveMovie(Movie $movie)
    {
        $query = $this->pdo->prepare(
            'INSERT IGNORE INTO webby_lab_task.movies (name, year, format)
            VALUES (:name, :year, (SELECT id FROM webby_lab_task.formats WHERE format=:format))');
        $query->execute(['name' => $movie->getName(), 'year' => $movie->getYear(), 'format' => $movie->getFormat()]);
    }

    private function saveMovieActors(int $movie_id, array $actors)
    {
        foreach ($actors as $key => $actor) {
            $query = $this->pdo->prepare('INSERT IGNORE INTO webby_lab_task.movies_actors (movie_id, actor_id) VALUES (:movie_id, (SELECT id FROM webby_lab_task.actors WHERE first_name = :first_name AND last_name = :last_name))');
            $query->execute(['movie_id' => $movie_id, 'first_name' => $actor->getFirstName(), 'last_name' => $actor->getLastName()]);
        }
    }

    public function movieFromArray($array) : Movie
    {
        $movie = new Movie($array['id']);

        $movie->setName($array['name']);
        $movie->setYear($array['year']);
        $movie->setFormat($array['format']);

        return $movie;
    }
}
