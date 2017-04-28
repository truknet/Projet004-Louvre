<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use AppBundle\Services\CheckIfDateIsBefore;
use AppBundle\Services\CheckIfDateInList;

class ContaintsCheckDateReservationValidator extends ConstraintValidator
{

    private $checkIfDateIsBefore;
    private $checkIfDateInList;

    public function __construct(CheckIfDateIsBefore $checkIfDateIsBefore, CheckIfDateInList $checkIfDateInList)
    {
        $this->checkIfDateIsBefore = $checkIfDateIsBefore;
        $this->checkIfDateInList = $checkIfDateInList;
    }

    public function validate($date, Constraint $constraint)
    {
        if (!$this->checkIfDateIsBefore->checkIfDateIsBefore($date)) $this->context->addViolation($constraint->message);
        if (!$this->checkIfDateInList->checkIfDateInList($date)) $this->context->addViolation($constraint->message);
    }
}
