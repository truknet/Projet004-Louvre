<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Validator\Constraints as CustomAssert;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 *
 */
class Client
{

    const TYPE_DAY = 'Journée';
    const TYPE_HALF_DAY = 'Demi-journée';
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'est pas valide.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reservation", type="datetime", nullable=false)
     * @Assert\NotBlank()
     * @Assert\DateTime()
     * @CustomAssert\ContaintsCheckDateReservation()
     *
     */
    private $dateReservation;

    /**
     * @var string
     *
     * @ORM\Column(name="uniquid", type="string", nullable=true)
     */
    private $uniquId;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbr_ticket", type="integer", nullable=false)
     * @Assert\Range(min="1")
     */
    private $nbrTicket;

    /**
     * @var string
     *
     * @ORM\Column(name="type_ticket", type="string", length=255, nullable=true)
     * @CustomAssert\ContaintsCheckTypeTicket()
     */
    private $typeTicket;

    /**
     * @var decimal
     *
     * @ORM\Column(type="decimal", precision=7, scale=2, nullable=true)
     */
    private $prixTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", nullable=true)
     */
    private $token;

    /**
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="Client", cascade={"persist"})
     */
    protected $tickets;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->date = new \Datetime();
        $this->date->setTimezone(new \DateTimeZone('Europe/Paris'));
        $this->tickets = new ArrayCollection();
        $this->uniquId = uniqid();
    }



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set confirmEmail
     *
     * @param string $confirmEmail
     *
     * @return Client
     */
    public function setConfirmEmail($confirmEmail)
    {
        $this->confirmEmail = $confirmEmail;

        return $this;
    }

    /**
     * Get confirmEmail
     *
     * @return string
     */
    public function getConfirmEmail()
    {
        return $this->confirmEmail;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Client
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set dateReservation
     *
     * @param \DateTime $dateReservation
     *
     * @return Client
     */
    public function setDateReservation(\DateTime $dateReservation)
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    /**
     * Get dateReservation
     *
     * @return \DateTime
     */
    public function getDateReservation()
    {
        return $this->dateReservation;
    }

    /**
     * Add ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return Client
     */
    public function addTicket(Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     */
    public function removeTicket(Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Set uniquId
     *
     * @param string $uniquId
     *
     * @return Client
     */
    public function setUniquId($uniquId)
    {
        $this->uniquId = $uniquId;

        return $this;
    }

    /**
     * Get uniquId
     *
     * @return string
     */
    public function getUniquId()
    {
        return $this->uniquId;
    }

    /**
     * Set nbrTicket
     *
     * @param integer $nbrTicket
     *
     * @return Client
     */
    public function setNbrTicket($nbrTicket)
    {
        $this->nbrTicket = $nbrTicket;

        return $this;
    }

    /**
     * Get nbrTicket
     *
     * @return integer
     */
    public function getNbrTicket()
    {
        return $this->nbrTicket;
    }

    /**
     * Set typeTicket
     *
     * @param string $typeTicket
     *
     * @return Client
     */
    public function setTypeTicket($typeTicket)
    {
        $this->typeTicket = $typeTicket;

        return $this;
    }

    /**
     * Get typeTicket
     *
     * @return string
     */
    public function getTypeTicket()
    {
        return $this->typeTicket;
    }

    /**
     * Set prixTotal
     *
     * @param integer $prixTotal
     *
     * @return Client
     */
    public function setPrixTotal($prixTotal)
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    /**
     * Get prixTotal
     *
     * @return integer
     */
    public function getPrixTotal()
    {
        return $this->prixTotal;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Client
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}
