<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ModifierUBackType;
use App\Form\UtilisateurBackType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('dashboard_back');
    }
    /**
     * @Route("/admin/back_utilisateur", name="back_utilisateur")
     */
    public function back_utilisateur(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        //Récupérer le répository
        $r = $this->getDoctrine()->getRepository(Utilisateur::class);
        //Récupérer la fonction findAll()
        $Utilisateur = $r->findAll();
        return $this->render('admin/back_utilisateur.html.twig', [
            'formU' => $Utilisateur
        ]);
    }

    /**
     * @Route("/admin/supprimer_uBack/{id}", name="supprimer_uBack")
     */
    public function supprimer_uBack($id): Response
    {
        //Récupérer le classroom à supprimer
        $Utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class) ->find($id);
        //L'action de suppression :
        $em = $this->getDoctrine()->getManager();
        $em->remove($Utilisateur);
        $em->flush(); // On ne prend en considération la suppression qu'apres l'appel de flush
        //faire une redirections vers l'affichage
        $this->addFlash('message','Utilisateur bien supprimer');
        return $this->redirectToRoute('back_utilisateur');
        //OU : Affichage d'un message de confirmation
        //return new Response("Suppression faite avec succès");
    }

    /**
     * @Route("/admin/addU_back", name="addU_back")
     */
    public function addU_back(Request $request , UserPasswordEncoderInterface $encoder){
        $Utilisateur = new Utilisateur();
        $form=$this->createForm(UtilisateurBackType::class,$Utilisateur);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $hash = $encoder->encodePassword($Utilisateur, $Utilisateur->getMotDePasse());
            $Utilisateur->setMotDePasse($hash);
            $Utilisateur=$form->getData();
            $Utilisateur->setEtat("Debloquer");
            $em=$this->getDoctrine()->getManager();
            $em->persist($Utilisateur);
            $em->flush();
            $this->addFlash('message','utilisateur bien ajouter');
            return $this->redirectToRoute('back_utilisateur');

            //return new Response("utilisateur ajouté");
            //return $this->render('utilisateur/back_utilisateur.html.twig',['formAjout'=>$form->createView()]);
        }
        return $this->render('admin/addU_back.html.twig',['formAddU'=>$form->createView()]);
    }


    /**
     * @Route("/admin/modifierU_Back/{id}", name="modifierU_Back")
     */
    public function modifier_uBack(\Symfony\Component\HttpFoundation\Request $request, $id): Response
    {
        //Récupérer le classroom à supprimer
        $Utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class) ->find($id);
        // Construction du formulaire
        $form=$this->createForm(ModifierUBackType::class, $Utilisateur);

        //recuperer les donnees saisies
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $em=$this->getDoctrine()->getManager();

            $em->flush();
            return $this->redirectToRoute('back_utilisateur');
        }
        $this->addFlash('message','utilisateur bien modifier');
        return $this->render('admin/modifierU_Back.html.twig', [
            //'controller_name' => 'ClassroomController',
            'formModU'=> $form->createView()
        ]);
    }




}