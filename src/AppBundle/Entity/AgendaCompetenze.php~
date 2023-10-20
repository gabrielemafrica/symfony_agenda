<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AgendaCompetenze
 *
 * @ORM\Table(name="agenda_competenze")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgendaCompetenzeRepository")
 */
class AgendaCompetenze
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Agenda", inversedBy="agendaCompetenze")
     * @ORM\JoinColumn(name="agenda_id", referencedColumnName="id")
     */
    private $agenda;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Setup_competenze", inversedBy="agendaCompetenze")
     * @ORM\JoinColumn(name="competenza_id", referencedColumnName="id")
     */
    private $competenza;



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
     * Set agenda
     *
     * @param \AppBundle\Entity\Agenda $agenda
     *
     * @return AgendaCompetenze
     */
    public function setAgenda(\AppBundle\Entity\Agenda $agenda = null)
    {
        $this->agenda = $agenda;

        return $this;
    }

    /**
     * Get agenda
     *
     * @return \AppBundle\Entity\Agenda
     */
    public function getAgenda()
    {
        return $this->agenda;
    }

    /**
     * Set competenza
     *
     * @param \AppBundle\Entity\Setup_competenze $competenza
     *
     * @return AgendaCompetenze
     */
    public function setCompetenza(\AppBundle\Entity\Setup_competenze $competenza = null)
    {
        $this->competenza = $competenza;

        return $this;
    }

    /**
     * Get competenza
     *
     * @return \AppBundle\Entity\Setup_competenze
     */
    public function getCompetenza()
    {
        return $this->competenza;
    }
}
