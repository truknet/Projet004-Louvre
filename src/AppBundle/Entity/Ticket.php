<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 */
class Ticket
{
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\Length(min=3, minMessage="Le nom doit faire au moins {{ limit }} caractères.")
     * @Assert\Length(max=50, maxMessage="Le nom doit faire un maximum de {{ limit }} caractères.")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     * @Assert\Length(min=3, minMessage="Le prénom doit faire au moins {{ limit }} caractères.")
     * @Assert\Length(max=50, maxMessage="Le prénom doit faire un maximum de {{ limit }} caractères.")
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\Country(message = "Le code pays n'est pas valide.")
     */
    private $country;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="datetime")
     * @Assert\DateTime()
     */
    private $birthday;

    /**
     * @var bool
     *
     * @ORM\Column(name="tarifReduit", type="boolean")
     */
    private $tarifReduit = false;

    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer", nullable=true)
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="tickets"))
     * @ORM\JoinColumn(nullable=false)
     */
    private $Client;

    /**
     * Ticket constructor.
     */
    public function __construct()
    {
        $this->birthday = new \Datetime();
        $this->birthday->setTimezone(new \DateTimeZone('Europe/Paris'));
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Ticket
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Ticket
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Ticket
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Ticket
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set tarifReduit
     *
     * @param boolean $tarifReduit
     *
     * @return Ticket
     */
    public function setTarifReduit($tarifReduit)
    {
        $this->tarifReduit = $tarifReduit;

        return $this;
    }

    /**
     * Get tarifReduit
     *
     * @return bool
     */
    public function getTarifReduit()
    {
        return $this->tarifReduit;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return Ticket
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return int
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set client
     *
     * @param Client $client
     *
     * @return Ticket
     */
    public function setClient(Client $client)
    {
        $this->Client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->Client;
    }
}
