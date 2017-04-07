<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use AppBundle\Services\CheckIfDateIsBefore;
use AppBundle\Services\CheckIfDateInList;


class constraintsCheckDateReservationValidator extends ConstraintValidator
{

    private $checkIfDateIsBefore;
    private $checkIfDateInList;

    public function __construct(
        CheckIfDateIsBefore $checkIfDateIsBefore,
        CheckIfDateInList $checkIfDateInList
    )
    {
        $this->checkIfDateIsBefore = $checkIfDateIsBefore;
        $this->checkIfDateInList = $checkIfDateInList;
}

    public function validate($value, Constraint $constraint)
    {
        if (!$this->checkIfDateIsBefore->checkIfDateIsBefore($value)) $this->context->addViolation($constraint->message);
        if (!$this->checkIfDateInList->checkIfDateInList($value)) $this->context->addViolation($constraint->message);
    }
}