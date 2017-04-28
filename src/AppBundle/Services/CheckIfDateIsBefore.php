<?php


namespace AppBundle\Services;


use Symfony\Component\Validator\Constraints\DateTime;

class CheckIfDateIsBefore
{

    /**
     * @param DateTime $date
     * @return bool
     */
    public function checkIfDateIsBefore($date)
    {

        $dateNow = new \DateTime('now');
        $dateInterval = $dateNow->diff($date);
        if ($dateInterval->invert == 1)
        {
            return false;
        } else {
            return true;
        }

    }
}
