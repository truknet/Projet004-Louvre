<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class ContaintsCheckTypeTicket extends Constraint
{

    public $message = "Le type de ticket n'est pas valide pour cette date !";

}
