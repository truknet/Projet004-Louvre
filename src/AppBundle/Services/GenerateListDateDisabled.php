<?php

/*
 * Function qui genere la liste des dates à désactiver pour le datepicker
 */

namespace AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;

class GenerateListDateDisabled
{

    private $em;
    private $listDateDisabled = array();

    /**
     * ToolsBox constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function generateListDateDisabled()
    {
        $this->generateListOver1000();
        $this->generateListHolidays();
        return $this->listDateDisabled;
    }


    private function generateListHolidays()
    {
        // Rajout des dates pour les jours féries (1er Mai / 1er Novembre / 25 Décembre)
        // Comme le datepicker affiche les dates sur 1 an (12 mois), nous ajouterons les jours féries sur 2 ans
        $holidays = array('01/05', '01/11', '25/12');
        $currentYear = (new \DateTime('now'))->format('Y');
        for ($i = 0; $i <= 1; $i++ )
        {
            foreach ($holidays as $holiday) {
                $holidayTmp = explode("/", $holiday);
                $dateTmp = strval($holidayTmp[0]) . '-' . strval($holidayTmp[1]) . '-' . strval($currentYear+$i);
                $this->listDateDisabled[] = $dateTmp;
            }
        }
        return $this->listDateDisabled;
    }

    private function generateListOver1000()
    {
        // Verifier s'il existe des jours ou plus de 1000 tickets ont été réservé.
        // si oui intégrer ces dates dans le tableau des date disabled
        $tmp = $this->em->getRepository('AppBundle:Client')->getNbTicketAndDate();
        foreach ($tmp as $value)
        {
            if ($value['nombre'] >= 1000) { $this->listDateDisabled[] = (new \DateTime($value['date']))->format('d-m-Y'); }
        }
        return $this->listDateDisabled;
    }
}