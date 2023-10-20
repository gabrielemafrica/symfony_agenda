<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Agenda
 *
 * @ORM\Table(name="agenda")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgendaRepository")
 */
class Agenda
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="string", length=255, nullable=true)
     */
    private $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=255, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string|null
     *
     * @ORM\Column(name="foto_filename", type="string", length=255, nullable=true)
     */
    private $fotoFilename;

    /**
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean", options={"default":false})
     */
    private $deleted = false;

    
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Chiamate", mappedBy="agenda")
     */
    private $chiamate;
    
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AgendaCompetenze", mappedBy="agenda")
     */
    private $agendaCompetenze;
    
    

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
     * Set name
     *
     * @param string $name
     *
     * @return Agenda
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
     * Set surname
     *
     * @param string $surname
     *
     * @return Agenda
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set sex
     *
     * @param string $sex
     *
     * @return Agenda
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Agenda
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Agenda
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set fotoFilename
     *
     * @param string $fotoFilename
     *
     * @return Agenda
     */
    public function setFotoFilename($fotoFilename)
    {
        $this->fotoFilename = $fotoFilename;

        return $this;
    }

    /**
     * Get fotoFilename
     *
     * @return string
     */
    public function getFotoFilename()
    {
        return $this->fotoFilename;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return Agenda
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Add chiamate
     *
     * @param \AppBundle\Entity\Chiamate $chiamate
     *
     * @return Agenda
     */
    public function addChiamate(\AppBundle\Entity\Chiamate $chiamate)
    {
        $this->chiamate[] = $chiamate;

        return $this;
    }

    /**
     * Remove chiamate
     *
     * @param \AppBundle\Entity\Chiamate $chiamate
     */
    public function removeChiamate(\AppBundle\Entity\Chiamate $chiamate)
    {
        $this->chiamate->removeElement($chiamate);
    }

    /**
     * Get chiamate
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChiamate()
    {
        return $this->chiamate;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->chiamate = new ArrayCollection();
        $this->agendaCompetenze = new ArrayCollection();
    }



    /**
     * Add agendaCompetenze
     *
     * @param \AppBundle\Entity\AgendaCompetenze $agendaCompetenze
     *
     * @return Agenda
     */
    public function addAgendaCompetenze(\AppBundle\Entity\AgendaCompetenze $agendaCompetenze)
    {
        $this->agendaCompetenze[] = $agendaCompetenze;

        return $this;
    }

    /**
     * Remove agendaCompetenze
     *
     * @param \AppBundle\Entity\AgendaCompetenze $agendaCompetenze
     */
    public function removeAgendaCompetenze(\AppBundle\Entity\AgendaCompetenze $agendaCompetenze)
    {
        $this->agendaCompetenze->removeElement($agendaCompetenze);
    }

    /**
     * Get agendaCompetenze
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAgendaCompetenze()
    {
        return $this->agendaCompetenze;
    }
}
