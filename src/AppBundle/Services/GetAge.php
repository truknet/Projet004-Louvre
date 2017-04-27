<?php

/*
 * Function qui calcul l'age
 * Argument : Date de naissance
 *
 * Exemple d'utilisation : $this->getAge->getAge(new \DateTime('2000/03/30'))
 *
 */

namespace AppBundle\Services;

class GetAge
{
    /**
     * Function pour calculer l'age
     *
     * @param $date
     * @return mixed
     */
    public function getAge($date)
    {
        $dateInterval = $date->diff(new \DateTime());
        return $dateInterval->y;
    }
}
