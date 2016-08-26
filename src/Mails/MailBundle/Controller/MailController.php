<?php

namespace Mails\MailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Mails\MailBundle\Entity\Actor;
use Mails\MailBundle\Form\ActorType;
use Mails\MailBundle\Entity\MailSent;
use Mails\MailBundle\Entity\MailReceived;
use Mails\MailBundle\Entity\Mail;
use Mails\MailBundle\Form\MailReceivedType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Mails\MailBundle\Form\MailMailsentAdminType;
use Mails\MailBundle\Form\MailMailreceivedAdminType;
use Mails\MailBundle\Form\MailMailsentSecretaryType;
use Mails\MailBundle\Form\MailMailreceivedSecretaryType;


class MailController extends Controller
{
    /**
     * Displays the list of index of mails sent by user profil
     *
     * @param Integer $page page number
     */
    public function indexMailsentAction($page)
    {
        if ($page < 1) {
        throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }
        
        //Utilisateur authentifié ou non
        if($this->getUser() !== null)
        {
            //On récupère les roles de l'user
            $userRoles = $this->getUser()->getRoles();
            
            //En fonction du profil on fait la pagination de l'index des courriers envoyés
            if(in_array("ROLE_SECRETAIRE", $userRoles)) 
            {
                // On récupère l'id de la sécrétaire courante
                $idSecretaire = $this->getUser()->getId();
                
                // On récupère notre service paginator
                $paginator = $this->get('mails_mail.mail_paginator');

                // On récupère notre objet Paginator en fonction des critères spécifiés
                $listMailsSent = $paginator->pageIndexMailsentBySecretary($page, $paginator::NUM_ITEMS, $idSecretaire);
                
                // On calcule le nombre total de pages grâce au count($listMailsSent) qui retourne le nombre total de courriers envoyé
                $nombreTotalMailsSent = $listMailsSent->count();
                $nombreMailsentPage = $paginator::NUM_ITEMS;
                $nombreTotalPages = ceil($nombreTotalMailsSent/$nombreMailsentPage); 
                
                if($page > $nombreTotalPages){
                throw $this->createNotFoundException("La page ".$page." n'existe pas.");
                }
                   
            }
            if(in_array("ROLE_ADMINISTRATEUR", $userRoles)) 
            {
                // On récupère l'admin courant
                $admin = $this->getUser();

                // On récupère notre service paginator
                $paginator = $this->get('mails_mail.mail_paginator');

                // On récupère notre objet Paginator en fonction des critères spécifiés
                $listMailsSent = $paginator->pageIndexMailsentNotValidated($page, $paginator::NUM_ITEMS, $admin);
                
                // On calcule le nombre total de pages grâce au count($listMailsSent) qui retourne le nombre total de courriers envoyé
                $nombreTotalMailsSent = $listMailsSent->count();
                $nombreMailsentPage = $paginator::NUM_ITEMS;
                $nombreTotalPages = ceil($nombreTotalMailsSent/$nombreMailsentPage); 
                
                if($page > $nombreTotalPages){
                throw $this->createNotFoundException("La page ".$page." n'existe pas.");
                }
                   
            }
            if(in_array("ROLE_SUPER_ADMIN", $userRoles)) 
            {
                //On récupère l'admin courant
                $admin = $this->getUser();

                // On récupère notre service paginator
                $paginator = $this->get('mails_mail.mail_paginator');

                // On récupère notre objet Paginator en fonction des critères spécifiés
                $listMailsSent = $paginator->pageIndexMailsentNotValidated($page, $paginator::NUM_ITEMS, $admin);
                
                // On calcule le nombre total de pages grâce au count($listMailsSent) qui retourne le nombre total de courriers envoyé
                $nombreTotalMailsSent = $listMailsSent->count();
                $nombreMailsentPage = $paginator::NUM_ITEMS;
                $nombreTotalPages = ceil($nombreTotalMailsSent/$nombreMailsentPage); 
                
                if($page > $nombreTotalPages){
                throw $this->createNotFoundException("La page ".$page." n'existe pas.");
                }
                   
            }
        }
        else
        {
            // On récupère notre service paginator
            $paginator = $this->get('mails_mail.mail_paginator');

            // On récupère notre objet Paginator en fonction des critères spécifiés
            $listMailsSent = $paginator->pageIndexMailsent($page, $paginator::NUM_ITEMS);

            // On calcule le nombre total de pages grâce au count($listMailsSent) qui retourne le nombre total de courriers envoyé
            $nombreTotalMailsSent = $listMailsSent->count();
            $nombreMailsentPage = $paginator::NUM_ITEMS;
            $nombreTotalPages = ceil($nombreTotalMailsSent/$nombreMailsentPage); 
            
            if($page > $nombreTotalPages){
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
            }
        }

        return $this->render('MailsMailBundle:Mail:indexMailsent.html.twig', array(
        'mails' => $listMailsSent,
        'nbPages' => $nombreTotalPages,
        'page' => $page,
        ));
            
    }
    
    /**
     * Displays the list of index of mails received by user profil
     *
     * @param Integer $page page number
     */
    public function indexMailreceivedAction($page)
    {
        if ($page < 1) {
        throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }
        
        //Utilisateur authentifié ou non
        if($this->getUser() !== null)
        {
            //On récupère les roles de l'user
            $userRoles = $this->getUser()->getRoles();
            
            if(in_array("ROLE_SECRETAIRE", $userRoles)) 
            {
                //On récupère l'id de la sécrétaire courante
                $idSecretaire = $this->getUser()->getId();

                // On récupère notre service paginator
                $paginator = $this->get('mails_mail.mail_paginator');

                // On récupère notre objet Paginator en fonction des critères spécifiés
                $listMailsReceived = $paginator->pageIndexMailreceivedBySecretary($page, $paginator::NUM_ITEMS, $idSecretaire);
                
                // On calcule le nombre total de pages grâce au count($listMailsReceived) qui retourne le nombre total de courriers reçus
                $nombreTotalMailsReceived = $listMailsReceived->count();
                $nombreMailreceivedPage = $paginator::NUM_ITEMS;
                $nombreTotalPages = ceil($nombreTotalMailsReceived/$nombreMailreceivedPage); 
                
                if($page > $nombreTotalPages){
                throw $this->createNotFoundException("La page ".$page." n'existe pas.");
                }   
            }
            if(in_array("ROLE_ADMINISTRATEUR", $userRoles)) 
            {
                // On récupère l'admin courant
                $admin = $this->getUser();

                // On récupère notre service paginator
                $paginator = $this->get('mails_mail.mail_paginator');

                // On récupère notre objet Paginator en fonction des critères spécifiés
                $listMailsReceived = $paginator->pageIndexMailreceivedNotValidated($page, $paginator::NUM_ITEMS, $admin);
                
                // On calcule le nombre total de pages grâce au count($listMailsReceived) qui retourne le nombre total de courriers reçus
                $nombreTotalMailsReceived = $listMailsReceived->count();
                $nombreMailreceivedPage = $paginator::NUM_ITEMS;
                $nombreTotalPages = ceil($nombreTotalMailsReceived/$nombreMailreceivedPage); 
                
                if($page > $nombreTotalPages){
                throw $this->createNotFoundException("La page ".$page." n'existe pas.");
                } 
                   
            }
            if(in_array("ROLE_SUPER_ADMIN", $userRoles)) 
            {
                // On récupère l'admin courant
                $admin = $this->getUser();

                // On récupère notre service paginator
                $paginator = $this->get('mails_mail.mail_paginator');

                // On récupère notre objet Paginator en fonction des critères spécifiés
                $listMailsReceived = $paginator->pageIndexMailreceivedNotValidated($page, $paginator::NUM_ITEMS, $admin);
                
                // On calcule le nombre total de pages grâce au count($listMailsReceived) qui retourne le nombre total de courriers reçus
                $nombreTotalMailsReceived = $listMailsReceived->count();
                $nombreMailreceivedPage = $paginator::NUM_ITEMS;
                $nombreTotalPages = ceil($nombreTotalMailsReceived/$nombreMailreceivedPage); 
                
                if($page > $nombreTotalPages){
                throw $this->createNotFoundException("La page ".$page." n'existe pas.");
                } 
                   
            }
        }
        else
        {

            // On récupère notre service paginator
            $paginator = $this->get('mails_mail.mail_paginator');

            // On récupère notre objet Paginator en fonction des critères spécifiés
            $listMailsReceived = $paginator->pageIndexMailreceived($page, $paginator::NUM_ITEMS);

                // On calcule le nombre total de pages grâce au count($listMailsReceived) qui retourne le nombre total de courriers reçus
                $nombreTotalMailsReceived = $listMailsReceived->count();
                $nombreMailreceivedPage = $paginator::NUM_ITEMS;
                $nombreTotalPages = ceil($nombreTotalMailsReceived/$nombreMailreceivedPage); 
                
                if($page > $nombreTotalPages){
                throw $this->createNotFoundException("La page ".$page." n'existe pas.");
                } 
        }

        return $this->render('MailsMailBundle:Mail:indexMailreceived.html.twig', array(
        'mails' => $listMailsReceived,
        'nombreTotalPages' => $nombreTotalPages,
        'page' => $page,
        ));
    }
    
    /**
     * Displays the list of lastest mails on home page.
     *
     * @param Integer $limit limit number
     */
    public function showLatestMailAction($limit)
    {
        // On récupère notre service indexor
        $indexor = $this->get('mails_mail.mail_indexor');

        // On récupère notre objet indexor en fonction des critères spécifiés
        $latestMailsSent = $indexor->indexLatestMailsent($indexor::NUM_ITEMS);

        $latestMailsReceived = $indexor->indexLatestMailreceived($indexor::NUM_ITEMS);
        
        return $this->render('MailsMailBundle:Mail:listMail.html.twig', array(
            'mailsSent' => $latestMailsSent,
            'mailsReceived' => $latestMailsReceived
        ));
    }
    
    /**
     * Display the list of latest unregistered mails attributed to Secretary
     *
     * @param Integer $limit limit number
     */
    public function showMailUnregistredAction($limit)
    {
        //On récupère l'id de la sécrétaire
        $idSecretaire = $this->getUser()->getId();
        
        // On récupère notre service indexor
        $indexor = $this->get('mails_mail.mail_indexor');

        // On récupère notre objet indexor en fonction des critères spécifiés
        $listMailsentBySecretary = $indexor->indexMailsentNotRegistredBySecretary($idSecretaire, $indexor::NUM_ITEMS);

        $listMailreceivedBySecretary = $indexor->indexMailreceivedNotRegistredBySecretary($idSecretaire, $indexor::NUM_ITEMS);
        
        return $this->render('MailsMailBundle:Mail:listMail_secretary.html.twig', array(
            'listMailsentBySecretary' => $listMailsentBySecretary,
            'listMailreceivedBySecretary' => $listMailreceivedBySecretary,
        ));
    }
    
    /**
     * Display the list of latest not validated mails by user.
     * @param Integer $limit limit number
     */
    public function listMailNotValidatedAction($limit)
    {
        //On récupère notre administrateur courant
        $admin = $this->getUser();

        // On récupère notre service indexor
        $indexor = $this->get('mails_mail.mail_indexor');

        // On récupère notre objet indexor en fonction des critères spécifiés
        $listMailsentByAdmin = $indexor->indexMailsentNotValidatedByAdmin($admin, $indexor::NUM_ITEMS);

        $listMailreceivedByAdmin = $indexor->indexMailreceivedNotValidatedByAdmin($admin, $indexor::NUM_ITEMS);
        
        return $this->render('MailsMailBundle:Mail:listMail_admin.html.twig', array(
            'listMailsentNotValidated' => $listMailsentByAdmin,
            'listMailreceivedNotValidated' => $listMailreceivedByAdmin
        ));
    }
    
    //---------------------------------------------
    /**
     * view the features of the mail sent
     *
     * @param Integer $id Mailsent id
     */
    public function viewMailsentAction($id)
    {
        //On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();
        
        // Pour récupérer un courrier envoyé unique 
        $mail = $em
        ->getRepository('MailsMailBundle:Mail')
        ->findMailSent($id)
        ;

        if (null === $mail) {
        throw $this->createNotFoundException("Le courrier envoyé d'id ".$id." n'existe pas.");
        }

        return $this->render('MailsMailBundle:Mail:view_mailsent.html.twig', array(
        'mail' => $mail
        ));
    }
    
    /**
     * view the features of the mail received
     *
     * @param Integer $id Mailreceived id
     */
    public function viewMailreceivedAction($id)
    {
        //On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();
        
        // Pour récupérer un courrier reçus unique 
        $mail = $em
        ->getRepository('MailsMailBundle:Mail')
        ->findMailReceived($id)
        ;

        if (null === $mail) {
        throw $this->createNotFoundException("Le courrier reçu d'id ".$id." n'existe pas.");
        }

        return $this->render('MailsMailBundle:Mail:view_mailreceived.html.twig', array(
        'mail' => $mail
        ));
    }

     /**
      * Add or create a mail sent action.
      *
      * @param Request $request Incoming request
      * @Security("has_role('ROLE_ADMIN')")
      */     
     public function addMailsentAction(Request $request)
     {
        //On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();
        
        //On crée le mail
        $mail = new Mail();
            
        //On crée le mail sent
        $mailsent = new MailSent();
        
        //On défini la date d'envoi du courrier envoyé à la date courante
        $mailsent->setdateEnvoi(new \Datetime("now", new \DateTimeZone('Africa/Abidjan')));
        
        //On défini le mail sent
        $mail->setMailsent($mailsent);

        //On crée notre formulaire
        $form = $this->createForm(new MailMailsentAdminType(), $mail);
        
        // Si la requête est en POST
        if($form->handleRequest($request)->isValid()) 
        {
            //On récupère l'id de la sécrétaire
            $mail = $form->getData();
            $idSecretary = $mail->getMailsent()->getUser()->getId();
            
            //On récupère l'interlocuteur
            $actor = $mail->getMailsent()->getActor();
            
            //On défini l'interlocuteur
            $mailsent->setActor($actor);
            
            //On défini le visa de la sécrétaire
            $mail->setVisaSecretaire($idSecretary);
            
            //On défini l'administrateur
            $admin = $this->getUser();
            $mailsent->setUser($admin);
            
            //On défini le mail sent
            $mail->setMailsent($mailsent);
            
            //On enregiste le courrier en BDD
            $em->persist($mail);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Le courrier envoyé de référence "'.$mail->getReference().'" à bien été crée.');
            
            return $this->redirect($this->generateUrl('mails_mail_mailsent_detail', array('id' => $mail->getId())));
        }
        
        // On récupère notre service
        $checker = $this->get('mails_mail.mail_checker');

        //On récupère un courrier par sa référence
        $findOneMailByReference = $checker->checkReference('CDEP0001');
        
        // Si la requête est en GET
        return $this->render('MailsMailBundle:Mail:mailsent_add.html.twig', array(
        'form' => $form->createView(),
        'findOneMailByReference' => $findOneMailByReference,
        ));
         
     }
     
     /**
      * Add or create a mail received action.
      *
      * @param Request $request Incoming request
      * @Security("has_role('ROLE_ADMIN')")
      */
     public function addMailreceivedAction(Request $request)
     {
        //On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();
        
        //On crée le mail
        $mail = new Mail();
            
        //On crée le mail received
        $mailreceived = new MailReceived();
        
        //On défini la date de reception du courrier reçu à la date courante
        $mailreceived->setdateReception(new \Datetime("now", new \DateTimeZone('Africa/Abidjan')));
        
        //On défini le mail received
        $mail->setMailreceived($mailreceived);

        //On crée notre formulaire
        $form = $this->createForm(new MailMailreceivedAdminType(), $mail);
        
        // Si la requête est en POST
        if($form->handleRequest($request)->isValid()) 
        {
            //On récupère l'id de la sécrétaire concerné par le courrier
            $mail = $form->getData();
            $idSecretary = $mail->getMailreceived()->getUser()->getId();
            
            //On récupère l'expéditeur du courrier reçu
            $sender = $mail->getMailreceived()->getActor();
            
            //On défini l'expéditeur du courrier reçu
            $mailreceived->setActor($sender);
            
            //On défini la signature de la sécrétaire
            $mail->setVisaSecretaire($idSecretary);
            
            //On défini le destinataire du courrier reçu
            $recipient = $this->getUser();
            $mailreceived->setUser($recipient);
            
            //On défini le courrier reçu
            $mail->setMailreceived($mailreceived);
            
            //On enregiste le courrier reçu en BDD
            $em->persist($mail);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Le courrier reçu de référence "'.$mail->getReference().'" à bien été crée.');
            
            return $this->redirect($this->generateUrl('mails_mail_mailreceived_detail', array('id' => $mail->getId())));
        }
        
        // Si la requête est en GET
        return $this->render('MailsMailBundle:Mail:mailreceived_add.html.twig', array(
        'form' => $form->createView(),
        ));
         
     }
    
     /**
      * Register a mail sent.
      *
      * @param Request $request Incoming request
      * @param Integer $id mail sent id
      * @Security("has_role('ROLE_SECRETAIRE')")
      */
     public function registerMailsentAction($id, Request $request)
     {
        //On récupère notre Entity Manager 
        $em = $this->getDoctrine()->getManager();

        // On récupère l'$id du mail sent 
        $mail = $em->getRepository('MailsMailBundle:Mail')->findMailSent($id);

        if (null === $mail) {
        throw new NotFoundHttpException("Le courrier envoyé d'id ".$id." n'existe pas.");
        }
        
        //On défini la date d'enregistrement du courrier envoyé selon la date courante
        $mail->setdateEdition(new \Datetime("now", new \DateTimeZone('Africa/Abidjan')));
        
        //On crée le formulaire
        $form = $this->createForm(new MailMailsentSecretaryType, $mail);
        
        //Si la réquête est en POST
        if($form->handleRequest($request)->isValid()) 
        {
            //On enregistre le mail sent
            $mail->setRegistred(true);

            //On enregistre le mail sent dans la BDD
            $em->persist($mail);
            $em->flush();

            //On redirige vers la page d'accueil
            $request->getSession()->getFlashBag()->add('success', 'Le courrier envoyé de référence "'.$mail->getReference().'" a bien été enregistré.');

            return $this->redirect($this->generateUrl('mails_core_home'));
        }
        
        //Si la réquête est en GET
        return $this->render('MailsMailBundle:Mail:mailsent_registred.html.twig', array(
        'form' => $form->createView(),
        ));
          
     }
     
     /**
      * Register a mail received action.
      *
      * @param Request $request Incoming request
      * @param Integer $id mail received id
      * @Security("has_role('ROLE_SECRETAIRE')")
      */
     public function registerMailreceivedAction($id, Request $request)
     {
        //On récupère notre Entity Manager 
        $em = $this->getDoctrine()->getManager();

        // On récupère l'$id du mail received
        $mail = $em->getRepository('MailsMailBundle:Mail')->findMailReceived($id);

        if (null === $mail) {
        throw new NotFoundHttpException("Le courrier reçu d'id ".$id." n'existe pas.");
        }
        
        //On défini la date d'enregistrement du courrier reçu selon la date courante
        $mail->setdateEdition(new \Datetime("now", new \DateTimeZone('Africa/Abidjan')));
        
        //On crée le formulaire
        $form = $this->createForm(new MailMailreceivedSecretaryType, $mail);
        
        //Si la réquête est en POST
        if($form->handleRequest($request)->isValid()) 
        {
            //On enregistre le courrier reçu
            $mail->setRegistred(true);
        
            //On enregistre le mail received dans la BDD
            $em->persist($mail);
            $em->flush();

            //On redirige vers la page d'accueil
            $request->getSession()->getFlashBag()->add('success', 'Le courrier reçu de référence "'.$mail->getReference().'" a bien été enregistré.');

            return $this->redirect($this->generateUrl('mails_core_home'));
        }
        
        //Si la réquête est en GET
        return $this->render('MailsMailBundle:Mail:mailreceived_registred.html.twig', array(
        'form' => $form->createView(),
        ));
          
     }
}
