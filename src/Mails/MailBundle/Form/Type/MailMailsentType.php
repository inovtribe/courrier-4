<?php

namespace Mails\MailBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MailMailsentType extends AbstractType
{
    private $adminCompany;

    /**
     * @param string $class The User class name
     */
    public function __construct($adminCompany)
    {
        $this->adminCompany = $adminCompany;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference', 'text')
            ->add('objet', 'text')
            ->add('dateEdition', 'datetime')
            ->add('nombrePiecesJointes', 'text')
            ->add('mailsent', new MailSentType($this->adminCompany))
            //->add('mailsent', new MailSentHeirType())
            //->add('mailsent', new MailSentHeir2Type())
            //->add('mailsent', new MailSentHeir3Type())
            //->add('mailreceived', new MailReceivedType())
            //->add('nbDaysBefore', 'text')
            ->add('save', 'submit')
            
        ;
        
        // On ajoute une fonction qui va écouter l'évènement PRE_SET_DATA
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                // On récupère notre objet Mail sous-jacent
                $mail = $event->getData();

                if (null === $mail) {
                    return;
                }

                if (!$mail->getReceived() || null === $mail->getId()) {
                    $event->getForm()->add('received', 'checkbox', array('required' => false));
                } else {
                    $event->getForm()->remove('received');
                }
            }
        );
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mails\MailBundle\Entity\Mail'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mails_mailbundle_mail';
    }
}
