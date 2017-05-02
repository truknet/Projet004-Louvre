<?php


namespace AppBundle\Services;


use Symfony\Component\Validator\Constraints\DateTime;

class GetAge
{
    public function getAge(\DateTime $date)
    {
        $dateInterval = $date->diff(new \DateTime());
        return $dateInterval->y;
    }
}
