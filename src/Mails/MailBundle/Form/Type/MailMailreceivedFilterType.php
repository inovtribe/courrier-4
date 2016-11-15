<?php

namespace Mails\MailBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class MailMailreceivedFilterType extends AbstractType
{
    private $admin;

    /**
     * @param string $class The User class name
     */
    public function __construct($user)
    {
        $this->admin = $user;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->remove('reference', 'text')
        ->remove('objet', 'text')
        ->remove('nombrePiecesJointes', 'text')
        ->remove('mailreceived', new MailReceivedType($this->admin->getCompany()))
        ->remove('dateEdition', 'datetime')
        ->add('nbDaysBefore', new IntegerType())
        ->remove('save', 'submit')
        ->add('rechercher', 'submit')
        ;
    }

    public function getName()
    {
        return 'mails_mailbundle_mailreceived_filter';
    }

    public function getParent()
    {
        return new MailMailreceivedType($this->admin->getCompany());
    }
}
