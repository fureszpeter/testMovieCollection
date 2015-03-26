<?php

namespace Entity;


use Exception\Validation\ValidationException;

class Actor extends AbstractEntity implements IActor
{
    const FIELD_NAME = "name";
    const FIELD_AGE = "age";
    const FIELD_DOB = "dob";

    const VALIDATION_NAME = "/([a-zA-Z ]+)/";
    const VALIDATION_DOB_MIN_YRS = 10;
    const VALIDATION_DOB_MAX_YRS = 70;

    /** @var  string */
    private $name;

    /** @var  \DateTime */
    private $dateOfBirth;

    function __construct(IAutoIncrement $sequence, $name, \DateTime $dateOfBirth)
    {
        $this->setName($name);
        $this->setDateOfBirth($dateOfBirth);

        parent::__construct($sequence);
    }

    /**
     * @param $name
     * @throws ValidationException
     */
    public function setName($name)
    {
        $this->validateName($name);
        $this->name = $name;
    }

    /**
     * @param \DateTime $dateOfBirth
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $min = new \DateInterval("P" . self::VALIDATION_DOB_MIN_YRS . "Y");
        $max = new \DateInterval("P" . self::VALIDATION_DOB_MAX_YRS . "Y");
        $age = new \DateInterval("P" . $dateOfBirth->diff(new \DateTime())->format("%Y") . "Y");

        if (!\Furesz\Date\Validate::betweenInterval($age, $min, $max)) {
            throw new ValidationException("Age not allowed! Min: " . $min->y . " max: " . $max->y . " given: " . $age->y);
        }
        $this->dateOfBirth = $dateOfBirth;
    }

    /** string */
    public function getName()
    {
        return $this->name;
    }

    /** integer */
    public function getAge()
    {
        return $this->getDateOfBirth()->diff(new \DateTime())->format("%Y");
    }

    /** \DateTime */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param $name
     *
     * @return bool
     * @throws ValidationException
     */
    private function validateName($name)
    {
        if (preg_match(self::VALIDATION_NAME, $name, $res) && $res[0]==$name) {
            return true;
        }

        throw new ValidationException("Invalid character in Actor name");
    }

    /**
     * @return array
     */
    protected function getSerializableFields()
    {
        return [
            self::FIELD_ID   => $this->id,
            self::FIELD_NAME => $this->getName(),
            self::FIELD_AGE  => $this->getAge(),
            self::FIELD_DOB  => $this->getDateOfBirth()
        ];
    }
}