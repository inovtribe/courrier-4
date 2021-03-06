<?php

namespace Mails\MailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Mails\MailBundle\Form\Type\MailreceivedRegisterType;
use Mails\MailBundle\Form\Type\MailreceivedSecretaryType;
use Mails\MailBundle\Form\Type\MailreceivedEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MailreceivedController extends Controller
{

    /**
     * Add or create a mail received action.
     *
     * @param Request $request
     *
     * @Template("@mailreceived_form_views/mailreceived_add.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     * 
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addMailreceivedAction(Request $request)
    {
        // On récupère notre mail factory
        $mailFactory = $this->get('mails_mail.mail_factory');
    
        //On crée le courrier reçu
        $mailreceived = $mailFactory::createMailReceived();
        
        //On défini la date de reception du courrier reçu à la date courante
        $mailreceived->setdateReception(new \Datetime("now", new \DateTimeZone('Africa/Abidjan')));

        //On crée le courrier
        $courier = $mailFactory::create();

        //On défini le courrier reçu
        $courier->setMailreceived($mailreceived);

        //On crée notre formulaire
        $form = $this->createForm(MailreceivedRegisterType::class, $courier, array(
            'adminCompany' => $this->getUser()->getCompany()
        ));
        
        // Si la requête est en POST
        if ($form->handleRequest($request)->isSubmitted() && $request->isMethod('POST')) {
            // On récupère notre service mail creator
            $mailCreator = $this->get('mails_mail.mail_creator');

            // On renvoi le conrrier reçu crée
            $mail = $mailCreator->processCreateMailReceived($form, $mailreceived, $this->getUser());
            
            //Redirection vers les détail du courrier
            return $this->redirect($this->generateUrl('mails_mailreceived_detail', array('id' => $mail->getId())));
        }
        // Si la requête est en GET
        return array('form' => $form->createView());
    }

    /**
     * Edit a mail received.
     *
     * @param $id
     * @param Request $request
     *
     * @Security("has_role('ROLE_ADMIN')")
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editMailreceivedAction($id, Request $request)
    {
        // On récupère notre entity manager
        $em = $this->getDoctrine()->getManager();

        // On récupère le mail received d'id $id
        $mail = $em->getRepository('MailsMailBundle:Mail')->findMailReceived($id, $this->getUser()->getCompany());

        if (null === $mail) {
            throw new NotFoundHttpException("Le courrier reçu d'id ".$id." n'existe pas.");
        }

        $form = $this->createForm(MailreceivedEditType::class, $mail, array(
            'adminCompany' => $this->getUser()->getCompany()
        ));

        //Si la requête est en POST
        if ($form->handleRequest($request)->isSubmitted() && $request->isMethod('POST') && $request->isMethod('POST')) {
            $em->flush();
            $request
            ->getSession()
            ->getFlashBag()
            ->add('success', 'Le courrier reçu de référence "'.$mail->getReference().'" a bien été modifiée.');

            return $this->redirect($this->generateUrl('mails_user_mailreceived'));
        }

        //Si la requête est en GET
        return $this->render('@mailreceived_form_views/mailreceived_edit.html.twig', array(
        'form'   => $form->createView(),
        'mail' => $mail
        ));
    }

    /**
     * Delete a mail received.
     *
     * @param integer $id mail received id
     * @param Request $request Incoming request
     * @Security("has_role('ROLE_ADMIN')")
     * @Template("@delete_mails_views/delete_mailreceived.html.twig")
     * 
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteMailreceivedAction($id, Request $request)
    {
        // On récupère notre entity manager
        $em = $this->getDoctrine()->getManager();

        // On récupère le mail received d'id $id
        $mail = $em->getRepository('MailsMailBundle:Mail')->findMailReceived($id, $this->getUser()->getCompany());

        if (null === $mail) {
            throw new NotFoundHttpException("Le courrier reçu d'id ".$id." n'existe pas.");
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression de courrier contre cette faille
        $form = $this->createFormBuilder()->getForm();
        
        // Si la requête est en POST, le courrier sera supprimé
        if ($form->handleRequest($request)->isSubmitted() && $request->isMethod('POST')) {
            //On stocke la référence du courrier reçu dans une varable tampon
            $tempMailreceivedRef = $mail->getReference();

            // On supprime notre objet $mail dans la base de données
            $em->remove($mail);
            $em->flush();

            // On affiche un message de confirmation
            $request
            ->getSession()
            ->getFlashBag()
            ->add('success', 'Le courrier reçu de référence "'.$tempMailreceivedRef.'" a bien été supprimé.');

            //On détruit la variable tampon.
            unset($tempMailreceivedRef);
            
            // Puis on redirige vers l'accueil
            return $this->redirect($this->generateUrl('mails_core_home'));
        }
        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return array('mail' => $mail, 'form' => $form->createView());
    }

    /**
     *  Register a mail received action.
     *
     * @param Request $request Incoming request
     * @param Integer $id mail received id
     * @Security("has_role('ROLE_SECRETAIRE')")
     * @Template("@mailreceived_form_views/mailreceived_registred.html.twig")
     * 
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerMailreceivedAction($id, Request $request)
    {
        //On récupère notre Entity Manager
        $em = $this->getDoctrine()->getManager();

        // On récupère l'$id du mail received
        $mailReceived = $em
        ->getRepository('MailsMailBundle:Mail')
        ->findMailReceived($id, $this->getUser()->getCompany());

        // On vérifie que ce courrier reçu existe bele et bien en base de données
        if (null === $mailReceived) {
            throw new NotFoundHttpException("Le courrier reçu d'id ".$id." n'existe pas.");
        }
        
        //On défini la date d'enregistrement du courrier reçu selon la date courante
        $mailReceived->setdateEdition(new \Datetime("now", new \DateTimeZone('Africa/Abidjan')));
        
        //On crée le formulaire
        $form = $this->createForm(MailreceivedSecretaryType::class, $mailReceived, array(
            'adminCompany' => $this->getUser()->getCompany()
        ));
        
        //Si la réquête est en POST
        if ($form->handleRequest($request)->isSubmitted() && $request->isMethod('POST')) {
            //On enregistre le courrier reçu
            $mailReceived->setRegistred(true);
        
            //On enregistre le courrier reçu dans la BDD
            $em->persist($mailReceived);
            $em->flush();

            //On affiche un message de confirmation
            $request
            ->getSession()
            ->getFlashBag()
            ->add('success', 'Le courrier reçu de référence "'.$mailReceived->getReference().'" a bien été enregistré.');

            // On redirige vers l'accueil
            return $this->redirect($this->generateUrl('mails_core_workspace_secretary'));
        }
        //Si la réquête est en GET on affiche le formulaire d'enregistrement
        return array('form' => $form->createView());
    }

    /**
     * view the features of the mail received
     *
     * @param Integer $id Mailreceived id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewMailreceivedAction($id)
    {
        //On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();
        
        // Pour récupérer un courrier reçu unique
        $mail = $em
        ->getRepository('MailsMailBundle:Mail')->findMailReceived($id, $this->getUser()->getCompany())
        ;

        // On vérifie que ce courrier existe bel et bien
        if (null === $mail) {
            throw $this->createNotFoundException("Le courrier reçu d'id ".$id." n'existe pas.");
        }

        // On affiche la page correspondante aux détails du courrier
        return $this->render('MailsMailBundle:Mail:view_mailreceived.html.twig', array(
        'mail' => $mail
        ));
    }
}
