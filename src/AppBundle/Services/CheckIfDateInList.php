<?php

namespace AppBundle\Services;

use Symfony\Component\Validator\Constraints\DateTime;

class CheckIfDateInList
{

    private $generateListDateDisabled;

    /**
     * ToolsBox constructor.
     */
    public function __construct($generateListDateDisabled)
    {
        $this->generateListDateDisabled = $generateListDateDisabled;
    }


    public function checkIfDateInList(\DateTime $date)
    {
        $dateTmp = $date->format('d-m-Y');
        $listDateDisabled = $this->generateListDateDisabled->generateListDateDisabled();
        if (in_array($dateTmp, $listDateDisabled)) { return false; }
        return true;
    }
}
