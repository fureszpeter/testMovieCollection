<?php

namespace Entity;

use Collection\ContractCollection;
use Exception\Validation\ValidationException;
use Furesz\Date\Validate;
use ValueObject\IActorContract;

class Movie extends AbstractEntity implements IMovie
{
    const FIELD_TITLE = "title";
    const FIELD_RUNTIME = "runtime";
    const FIELD_RELEASE_DATE = "releaseDate";

    const VALIDATE_MOVIE_LENGTH_MIN = "PT10M";
    const VALIDATE_MOVIE_LENGTH_MAX = "PT2H30M";
    const VALIDATE_TITLE_PATTERN = "/([a-zA-Z \.\,\?\!]+)/";

    /** @var  string */
    private $title;

    /** @var  \DateInterval */
    private $runtime;

    /** @var  \DateTime */
    private $releaseDate;

    /** @var  ContractCollection */
    private $contracts;

    /**
     * @param IAutoIncrement $sequence
     * @param array $dto
     * @param ContractCollection $contract
     *
     * @throws ValidationException
     */
    public function __construct(IAutoIncrement $sequence, array $dto, ContractCollection $contract)
    {
        $this->validateDTO($dto);
        $this->validateCollection($contract);

        $this->setTitle($dto[self::FIELD_TITLE]);
        $this->setRuntime($dto[self::FIELD_RUNTIME]);
        $this->setReleaseDate($dto[self::FIELD_RELEASE_DATE]);
        $this->setContracts($contract);

        parent::__construct($sequence);
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
    public function setReleaseDate(\DateTime $releaseDate)
    {
        $this->validateReleaseDate($releaseDate);
        $this->releaseDate = $releaseDate;
    }

    /**
     * @param ContractCollection $contracts
     */
    public function setContracts(ContractCollection $contracts)
    {
        $this->contracts = $contracts;
    }

    /**
     * @return ContractCollection
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return \DateTime
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * @return \DateInterval
     */
    public function getRuntime()
    {
        return $this->runtime;
    }

    /**
     * @param \DateInterval $runtime
     * @throws ValidationException
     */
    private function validateRunTime(\DateInterval $runtime)
    {
        $min = new \DateInterval(self::VALIDATE_MOVIE_LENGTH_MIN);
        $max = new \DateInterval(self::VALIDATE_MOVIE_LENGTH_MAX);

        if (!Validate::betweenInterval($runtime, $min, $max)) {
            throw new ValidationException("Movie runtime not correct, minimum: " . $min->i . " min, maximum: " . $max->h * 60 . " min. You give me: " . $runtime->h * 60 . " min");
        }
    }

    /**
     * @param $title
     *
     * @return bool
     * @throws ValidationException
     */
    private function validateTitle($title)
    {
        if (preg_match(self::VALIDATE_TITLE_PATTERN, $title, $res) && $res[0] == $title) {
            return true;
        }

        throw new ValidationException("Title contains invalid characters");
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
        if (array_diff($required, array_keys($dto))) {
            throw new \InvalidArgumentException(
                "Invalid DTO, missing field provided: " . var_export($dto, 1)
                . " needed: " . var_export($required, 1)
                . " missing: " . var_export(array_intersect(array_keys($dto), $required), 1)
            );
        }
    }

    /**
     * @param ContractCollection $contract
     * @throws ValidationException
     */
    private function validateCollection(ContractCollection $contract)
    {
        if (count($contract) == 0) {
            throw new ValidationException("Empty collection not allowed");
        }
    }

    /**
     * @return array
     */
    protected function getSerializableFields()
    {
        return $this->getRequiredFields();
    }

    /**
     * @param \DateTime $releaseDate
     * @throws ValidationException
     */
    private function validateReleaseDate(\DateTime $releaseDate)
    {
        if ($releaseDate > new \DateTime()) {
            throw new ValidationException("Invalid release date.");
        }
    }

    /**
     * @return array
     */
    function getRequiredFields()
    {
        return [
            self::FIELD_ID           => $this->getId(),
            self::FIELD_RELEASE_DATE => $this->getReleaseDate(),
            self::FIELD_RUNTIME      => $this->getRuntime(),
            self::FIELD_TITLE        => $this->getTitle()
        ];
    }

    /**
     * @param int $sortOrder
     *
     * @return Actor[]
     */
    public function getActors($sortOrder = SORT_DESC)
    {

        $this->getContracts()->uasort(function ($a, $b) use ($sortOrder) {
            /** @var IActorContract $a */
            /** @var IActorContract $b */
            switch ($sortOrder) {
                case SORT_DESC:
                    return $a->getActor()->getAge() < $b->getActor()->getAge();
                    break;
                case SORT_ASC:
                    return $a->getActor()->getAge() > $b->getActor()->getAge();
                    break;
            }

            return $a->getActor()->getAge() > $b->getActor()->getAge();
        });

        $actors = [];
        foreach ($this->getContracts() as $contract) {
            /** @var IActorContract $contract */
            $actors[] = $contract->getActor();
        }

        return $actors;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}
