<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Setup_competenze
 *
 * @ORM\Table(name="setup_competenze")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Setup_competenzeRepository")
 */
class Setup_competenze
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
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean", options={"default":false})
     */
    private $deleted = false;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AgendaCompetenze", mappedBy="competenza")
     */
    private $agenda;


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
     * Set description
     *
     * @param string $description
     *
     * @return Setup_competenze
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return Setup_competenze
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
     * Constructor
     */
    public function __construct()
    {
        $this->agenda = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add agenda
     *
     * @param \AppBundle\Entity\AgendaCompetenze $agenda
     *
     * @return Setup_competenze
     */
    public function addAgenda(\AppBundle\Entity\AgendaCompetenze $agenda)
    {
        $this->agenda[] = $agenda;

        return $this;
    }

    /**
     * Remove agenda
     *
     * @param \AppBundle\Entity\AgendaCompetenze $agenda
     */
    public function removeAgenda(\AppBundle\Entity\AgendaCompetenze $agenda)
    {
        $this->agenda->removeElement($agenda);
    }

    /**
     * Get agenda
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAgenda()
    {
        return $this->agenda;
    }
}
