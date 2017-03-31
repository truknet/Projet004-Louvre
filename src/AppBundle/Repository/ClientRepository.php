<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ClientRepository extends EntityRepository
{

    // Fonction qui retourne le nombre de ticket réserver sur une date (en argument)
    public function getNbTicketByDate($date)
    {
        // SELECT SUM(nbr_ticket) AS nombre FROM client WHERE dateReservation = $date

        $dateD = clone $date;
        $dateF = clone $date;
        $dateF = $dateF->add(new \DateInterval('P1D'));

        $qb = $this->createQueryBuilder('c');
        $qb
            ->select('SUM(c.nbrTicket) as nombre')
            ->andWhere('c.dateReservation >= :dateD AND c.dateReservation < :dateF')
            ->setParameter('dateD', $dateD)
            ->setParameter('dateF', $dateF)
        ;
        return $qb
            ->getQuery()
            ->getResult()
            ;
    }


    // Fonction qui retourne un tableau avec Date et Nombre de ticket
    public function getNbTicketAndDate()
    {
        $datenow = new \DateTime('NOW');
        $datenow = $datenow->format('Y-m-d');
        $qb = $this->createQueryBuilder('c');
        $qb
            ->select('SUBSTRING(c.dateReservation, 1, 10) as date, SUM(c.nbrTicket) as nombre')
            ->andWhere('c.dateReservation >= :datenow')
            ->groupBy('date')
            ->setParameter('datenow', $datenow)
        ;
        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
}
