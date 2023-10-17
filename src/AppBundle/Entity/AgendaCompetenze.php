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
     * @var int
     *
     * @ORM\Column(name="agenda_id", type="integer")
     */
    private $agenda_id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Agenda", inversedBy="agendaCompetenze")
     * @ORM\JoinColumn(name="agenda_id", referencedColumnName="id")
     */
    private $agenda;

    /**
     * @var int
     *
     * @ORM\Column(name="competenza_id", type="integer")
     */
    private $competenza_id;

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
     * Set agendaId
     *
     * @param integer $agendaId
     *
     * @return AgendaCompetenze
     */
    public function setAgendaId($agendaId)
    {
        $this->agenda_id = $agendaId;

        return $this;
    }

    /**
     * Get agendaId
     *
     * @return integer
     */
    public function getAgendaId()
    {
        return $this->agenda_id;
    }

    /**
     * Set competenzaId
     *
     * @param integer $competenzaId
     *
     * @return AgendaCompetenze
     */
    public function setCompetenzaId($competenzaId)
    {
        $this->competenza_id = $competenzaId;

        return $this;
    }

    /**
     * Get competenzaId
     *
     * @return integer
     */
    public function getCompetenzaId()
    {
        return $this->competenza_id;
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
