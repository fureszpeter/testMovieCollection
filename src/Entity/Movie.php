<?php

namespace Entity;

use Collection\ContractCollection;
use Exception\Validation\ValidationException;
use ValueObject\ActorMovieContract;

class Movie
{
    const FIELD_TITLE = "title";
    const FIELD_RUNTIME = "runtime";
    const FIELD_RELEASE_DATE = "releaseDate";

    const VALIDATE_MOVIE_LENGTH_MIN = "PT10M";
    const VALIDATE_MOVIE_LENGTH_MAX = "PT2H30M";

    /** @var  int */
    private $id;

    /** @var  string */
    private $title;

    /** @var  \DateInterval */
    private $runtime;

    /** @var  \DateTime */
    private $releaseDate;

    /**
     * @param array $dto
     * @param ContractCollection $contract
     */
    public function __construct(array $dto, ContractCollection $contract)
    {
        $this->validateDTO($dto);

        if (count($contract)==0){
            throw new ValidationException("Empty collection not allowed");
        }

        $this->setTitle($dto[self::FIELD_TITLE]);
        $this->setRuntime($dto[self::FIELD_RUNTIME]);
        $this->setReleaseDate($dto[self::FIELD_RELEASE_DATE]);

    }

    /**
     * @param string $title
     * @throws ValidationException
     */
    public function setTitle($title)
    {
        $this->validateTitle($title);
        $this->title = $title;
    }

    /**
     * @param \DateInterval $runtime
     * @throws ValidationException
     */
    public function setRuntime(\DateInterval $runtime)
    {
        $this->validateRunTime($runtime);
        $this->runtime = $runtime;
    }

    /**
     * @param \DateTime $releaseDate
     */
    public function setReleaseDate($releaseDate)
    {
        $this->releaseDate = $releaseDate;
    }

    /**
     * @param \DateInterval $runtime
     * @throws ValidationException
     */
    protected function validateRunTime(\DateInterval $runtime)
    {
        $min = new \DateInterval(self::VALIDATE_MOVIE_LENGTH_MIN);
        $max = new \DateInterval(self::VALIDATE_MOVIE_LENGTH_MAX);

        //@TODO DateInterval not comparable if PHP5<5.6
        if (
        !(
            (new \DateTime())->add($runtime) > (new \DateTime())->add($min)
            && (new \DateTime())->add($runtime) < (new \DateTime())->add($max)
        )
        ) {
            throw new ValidationException("Movie length not correct, minimum: " . $min->i . " min, maximum: " . $max->h * 60 . " min. You give me: " . $runtime->h * 60 . " min");
        }
    }

    /**
     * @param $title
     * @throws ValidationException
     */
    protected function validateTitle($title)
    {
        if (preg_match("/[^a-zA-Z0-9 \.\,\?\!]/", $title)) {
            throw new ValidationException();
        }
    }

    /**
     * @return array
     */
    private function requiredFields()
    {
        return [
            self::FIELD_RELEASE_DATE,
            self::FIELD_RUNTIME,
            self::FIELD_TITLE
        ];
    }

    /**
     * @param array $dto
     */
    private function validateDTO(array $dto)
    {
        $required = $this->requiredFields();
        if (array_intersect(array_keys($dto), $required) != $required) {
            throw new \InvalidArgumentException();
        }
    }

}