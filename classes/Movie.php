<?php

class Movie
{
    private $id;
    private $name;
    private $year;
    private $format;
    private $actors = [];

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setYear(int $year)
    {
        $this->year = $year;
    }

    public function setFormat(string $format)
    {
        $this->format = $format;
    }

    public function addActor(Actor $actor)
    {
        $this->actors[] = $actor;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getYear() : int
    {
        return $this->year;
    }

    public function getFormat() : string
    {
        return $this->format;
    }

    public function getActors(): array
    {
        return $this->actors;
    }

    public function getActorsStr()
    {
        $actors = '';
        foreach ($this->actors as $key => $actor) {
            $actors .= $actor->getFullName().', ';
        }

        return substr($actors, 0, -2);
    }

}
