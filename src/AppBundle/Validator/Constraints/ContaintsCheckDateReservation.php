<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContaintsCheckDateReservation extends Constraint
{

    public $message = 'La date de réservation n\'est pas valide ! ';

}
