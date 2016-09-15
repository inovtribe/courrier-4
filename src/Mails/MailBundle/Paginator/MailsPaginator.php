<?php
namespace Mails\MailBundle\Paginator;

class MailsPaginator 
{
    const NUM_ITEMS = 5;

    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }
    
    public function pageIndexMailsentBySecretary($pageNumber, $itemsNumber, $idSecretary)
    {
        // On récupère notre objet Paginator en fonction des critères spécifiés
                $listMailsSent = $this
                        ->em
                        ->getRepository('MailsMailBundle:Mail')
                        ->getMailsSentBySecretary($pageNumber, $itemsNumber, $idSecretary)
                ;
                            
        // Et on n'oublie pas de faire un flush !
        $this->em->flush();

        return $listMailsSent;
    }

    public function pageIndexMailsentNotValidated($pageNumber, $itemsNumber, $admin)
    {
        // On récupère notre objet Paginator en fonction des critères spécifiés
                $listMailsSentNotValidated = $this
                        ->em
                        ->getRepository('MailsMailBundle:Mail')
                        ->getMailsSentNotValidated($pageNumber, $itemsNumber, $admin)
                ;
                            
        // Et on n'oublie pas de faire un flush !
        $this->em->flush();

        return $listMailsSentNotValidated
        ;
    }

    public function pageIndexMailsent($pageNumber, $itemsNumber)
    {
        // On récupère notre objet Paginator en fonction des critères spécifiés
                $listMailsSent = $this
                        ->em
                        ->getRepository('MailsMailBundle:Mail')
                        ->getMailsSent($pageNumber, $itemsNumber)
                ;
                            
        $this->em->flush();

        return $listMailsSent
        ;
    }

    public function pageIndexMailreceivedBySecretary($pageNumber, $itemsNumber, $idSecretary)
    {
        // On récupère notre objet Paginator en fonction des critères spécifiés
                $listMailsReceived = $this
                        ->em
                        ->getRepository('MailsMailBundle:Mail')
                        ->getMailsReceivedBySecretary($pageNumber, $itemsNumber, $idSecretary)
                ;
                            
        // Et on n'oublie pas de faire un flush !
        $this->em->flush();

        return $listMailsReceived
        ;
    }

    public function pageIndexMailreceivedNotValidated($pageNumber, $itemsNumber, $admin)
    {
        // On récupère notre objet Paginator en fonction des critères spécifiés
                $listMailsReceivedNotValidated = $this
                        ->em
                        ->getRepository('MailsMailBundle:Mail')
                        ->getMailsReceivedNotValidated($pageNumber, $itemsNumber, $admin)
                ;
                            
        // Et on n'oublie pas de faire un flush !
        $this->em->flush();

        return $listMailsReceivedNotValidated
        ;
    }

    public function pageIndexMailreceived($pageNumber, $itemsNumber)
    {
        // On récupère notre objet Paginator en fonction des critères spécifiés
                $listMailsReceived = $this
                        ->em
                        ->getRepository('MailsMailBundle:Mail')
                        ->getMailsReceived($pageNumber, $itemsNumber)
                ;
                            
        $this->em->flush();

        return $listMailsReceived
        ;
    }
}
