<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Competenze
 *
 * @ORM\Table(name="competenze")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompetenzeRepository")
 */
class Competenze
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
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean", options={"default":false})
     */
    private $deleted = false;

    /**
     * @ORM\ManyToMany(targetEntity="Agenda", mappedBy="competenze")
     */
    private $agendas;

    public function __construct()
    {
        $this->agendas = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Competenze
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
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return Competenze
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
     * Add agenda
     *
     * @param \AppBundle\Entity\Agenda $agenda
     *
     * @return Competenze
     */
    public function addAgenda(\AppBundle\Entity\Agenda $agenda)
    {
        $this->agendas[] = $agenda;

        return $this;
    }

    /**
     * Remove agenda
     *
     * @param \AppBundle\Entity\Agenda $agenda
     */
    public function removeAgenda(\AppBundle\Entity\Agenda $agenda)
    {
        $this->agendas->removeElement($agenda);
    }

    /**
     * Get agendas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAgendas()
    {
        return $this->agendas;
    }
}
