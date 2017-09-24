<?php

namespace Mails\MailBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MailreceivedRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->remove('dateEdition', 'datetime')//supprimer dans la création d'un nouveau courrier reçu
        ;
    }

    public function getBlockPrefix()
    {
        return 'mails_mailbundle_mailreceived_admin';
    }

    public function getParent()
    {
        return MailMailreceivedType::class;
    }
}
