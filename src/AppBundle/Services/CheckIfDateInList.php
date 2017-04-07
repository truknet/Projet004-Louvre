<?php

namespace AppBundle\Services;

use AppBundle\Services\GenerateListDateDisabled;


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


    /**
     * Controle si la date n'est pas inclue dans la liste des jours désactivés
     *
     * @param $date
     * @return bool
     */
    public function checkIfDateInList($date)
    {
        $dateTmp = $date->format('d-m-Y');
        $listDateDisabled = $this->generateListDateDisabled->generateListDateDisabled();
        if (in_array($dateTmp, $listDateDisabled)) { return false; }
        return true;
    }


}