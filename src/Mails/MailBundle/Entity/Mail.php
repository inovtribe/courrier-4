<?php

namespace Mails\MailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Mail
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mails\MailBundle\Entity\MailRepository")
 * @UniqueEntity(fields="reference", message="Un courrier existe déjà avec cette référence.")
 * @ORM\HasLifecycleCallbacks
 */
class Mail
{
    /**
    * @ORM\OneToOne(targetEntity="Mails\MailBundle\Entity\MailSent", cascade={"persist","remove"})
    * @Assert\Valid
    */
    private $mailsent;
    
    /**
    * @ORM\OneToOne(targetEntity="Mails\MailBundle\Entity\MailReceived", cascade={"persist","remove"})
    * @Assert\Valid
    */
    private $mailreceived;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
     * @Assert\Regex(
     *     pattern="/^(CDEP[0-9]{4}|CARR[0-9]{4})$/",
     *     match=true,
     *     message="La référence du courrier n'est pas valide, elle doit être de la forme CDEP[0-9]{4} ou CARR[0-9]{4}."
     * )
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="objet", type="string", length=255)
     */
    private $objet;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_edition", type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $dateEdition;

    /**
     * @var integer
     *
     * @ORM\Column(name="nombre_pieces_jointes", type="integer")
     * @Assert\Range(min=0, max=10, minMessage="Valeur trop petite", maxMessage="Valeur trop grande", invalidMessage="Valeur invalide")
     */
    private $nombrePiecesJointes;

    /**
     * @var boolean
     *
     * @ORM\Column(name="received", type="boolean", nullable=true)
     */
    private $received;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="treated", type="boolean", nullable=true)
     */
    private $treated;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="validated", type="boolean", nullable=true)
     */
    private $validated;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="registred", type="boolean", nullable=true)
     */
    private $registred;
    
    /**
     * @Assert\Type(
     *     type="integer",
     *     message="La valeur {{ value }} n'est pas un{{ type }} valide ."
     * )
     */
    private $nbDaysBefore;
    
    /**
     * @ORM\Column(name="visa_secretaire", type="integer")
     */
    private $visaSecretaire;

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
     * Set reference
     *
     * @param string $reference
     * @return Mail
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string 
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set objet
     *
     * @param string $objet
     * @return Mail
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get objet
     *
     * @return string 
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set dateEdition
     *
     * @param \DateTime $dateEdition
     * @return Mail
     */
    public function setDateEdition($dateEdition)
    {
        $this->dateEdition = $dateEdition;

        return $this;
    }

    /**
     * Get dateEdition
     *
     * @return \DateTime 
     */
    public function getDateEdition()
    {
        return $this->dateEdition;
    }

    /**
     * Set nombrePiecesJointes
     *
     * @param integer $nombrePiecesJointes
     * @return Mail
     */
    public function setNombrePiecesJointes($nombrePiecesJointes)
    {
        $this->nombrePiecesJointes = $nombrePiecesJointes;

        return $this;
    }

    /**
     * Get nombrePiecesJointes
     *
     * @return integer 
     */
    public function getNombrePiecesJointes()
    {
        return $this->nombrePiecesJointes;
    }

    /**
     * Set received
     *
     * @param boolean $received
     * @return Mail
     */
    public function setReceived($received)
    {
        $this->received = $received;

        return $this;
    }

    /**
     * Get received
     *
     * @return boolean 
     */
    public function getReceived()
    {
        return $this->received;
    }



    /**
     * Set mailsent
     *
     * @param \Mails\MailBundle\Entity\MailSent $mailsent
     * @return Mail
     */
    public function setMailsent(MailSent $mailsent = null)
    {
        $this->mailsent = $mailsent;

        return $this;
    }

    /**
     * Get mailsent
     *
     * @return \Mails\MailBundle\Entity\MailSent 
     */
    public function getMailsent()
    {
        return $this->mailsent;
    }

    /**
     * Set mailreceived
     *
     * @param \Mails\MailBundle\Entity\MailReceived $mailreceived
     * @return Mail
     */
    public function setMailreceived(MailReceived $mailreceived = null)
    {
        $this->mailreceived = $mailreceived;

        return $this;
    }

    /**
     * Get mailreceived
     *
     * @return \Mails\MailBundle\Entity\MailReceived 
     */
    public function getMailreceived()
    {
        return $this->mailreceived;
    }
    
    /**
     * Set treated
     *
     * @param boolean $treated
     * @return MailReceived
     */
    public function setTreated($treated)
    {
        $this->treated = $treated;

        return $this;
    }

    /**
     * Get treated
     *
     * @return boolean 
     */
    public function getTreated()
    {
        return $this->treated;
    }
    
    public function setNbDaysBefore($nbDaysBefore)
    {
        $this->nbDaysBefore = $nbDaysBefore;

        return $this;
    }

    public function getNbDaysBefore()
    {
        return $this->nbDaysBefore;
    }
 

    /**
     * Set visaSecretaire
     *
     * @param integer $visaSecretaire
     * @return Mail
     */
    public function setVisaSecretaire($visaSecretaire)
    {
        $this->visaSecretaire = $visaSecretaire;

        return $this;
    }

    /**
     * Get visaSecretaire
     *
     * @return integer 
     */
    public function getVisaSecretaire()
    {
        return $this->visaSecretaire;
    }

    /**
     * Set validated
     *
     * @param boolean $validated
     * @return Mail
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;

        return $this;
    }

    /**
     * Get validated
     *
     * @return boolean 
     */
    public function getValidated()
    {
        return $this->validated;
    }

    /**
     * Set registred
     *
     * @param boolean $registred
     * @return Mail
     */
    public function setRegistred($registred)
    {
        $this->registred = $registred;

        return $this;
    }

    /**
     * Get registred
     *
     * @return boolean 
     */
    public function getRegistred()
    {
        return $this->registred;
    }
}
