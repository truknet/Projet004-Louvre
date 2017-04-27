<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConstraintsCheckDateReservation extends Constraint
{

    public $message = 'La date de réservation n\'est pas valide ! ';

}
