<?php

namespace Mails\MailBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MailMailsentEditType extends AbstractType
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
        ->remove('dateEdition', 'datetime')
        ;
    }

    public function getName()
    {
        return 'mails_mailbundle_mailsent_edit';
    }

    public function getParent()
    {
        return new MailMailsentType($this->admin->getCompany());
    }
}
