<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use AppBundle\Services\CheckIf14Hour;

class constraintsCheckTypeTicketValidator extends ConstraintValidator
{

    private $checkIf14Hour;

    public function __construct(CheckIf14Hour $checkIf14Hour)
    {
        $this->checkIf14Hour = $checkIf14Hour;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$this->checkIf14Hour->checkIf14Hour($this->context->getObject())) $this->context->addViolation($constraint->message);
    }
}