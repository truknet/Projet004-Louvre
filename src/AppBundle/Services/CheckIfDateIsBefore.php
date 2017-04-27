<?php


namespace AppBundle\Services;


class CheckIfDateIsBefore
{

    /**
     * Controle si la date n'est pas inférieur à la date du jour
     *
     * @param $date
     * @return bool
     */
    public function checkIfDateIsBefore($date)
    {
        $dateNow = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $dateInterval = $dateNow->diff($date);
        if ($dateInterval->invert == 1)
        {
            return false;
        } else {
            return true;
        }

    }
}
