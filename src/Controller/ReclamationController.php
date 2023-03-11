<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Form\ReclamationType;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="reclamation")
     */
    public function index(): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
    /**
     * @Route("/ajouterRec", name="ajouterRec")
     */
    public function ajouterRec(\Symfony\Component\HttpFoundation\Request $request): Response
    {
        // Construction du formulaire
        $reclamation =new Reclamation();
        $date = new \DateTime('@' .strtotime('now'));
        $user = $this->getUser();
        $reclamation->setNomU($user);
        $reclamation->setDateRec($date);
        $reclamation->setEtatRec("encours");
        $form=$this->createForm(ReclamationType::class,$reclamation); //formulaire vide
        //recuperer les donnees saisies
        $form->handleRequest($request);
        //Insert
        if ($form->isSubmitted())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($reclamation);
            $em->flush();
            $this->addFlash('message','reclamation bien passer');
            return $this->redirectToRoute('ajouterRec');
        }
        return $this->render('reclamation/ajouterRec.html.twig', [
            //'controller_name' => 'ClassroomController',
            'formRec'=> $form->createView()
        ]);
    }
    /**
     * @Route("/admin/back_reclamation", name="back_reclamation")
     */
    public function back_reclamation(): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        //Récupérer le répository
        $form = $this->getDoctrine()->getRepository(Reclamation::class);
        //Récupérer la fonction findAll()
        $reclamation = $form->findAll();
        return $this->render('reclamation/back_reclamation.html.twig', [
            'formBackRec' => $reclamation
        ]);
    }

    /**
     * @Route("/admin/triDateReclamation", name="triDateReclamation")
     */
    public function triDateReclamation()
    {
        $reclamation= $this->getDoctrine()->getRepository(Reclamation::class)->TriParDateReclamation();
        return $this->render("reclamation/back_reclamation.html.twig",array('formBackRec'=>$reclamation));
    }
    /**
     * @Route("/admin/triTypeReclamation", name="triTypeReclamation")
     */
    public function triTypeReclamation()
    {
        $reclamation= $this->getDoctrine()->getRepository(Reclamation::class)->TriParTypeReclamation();
        return $this->render("reclamation/back_reclamation.html.twig",array('formBackRec'=>$reclamation));
    }


    /**
     * @Route("/admin/traite/{id}", name="traite")
     */
    public function traite($id): Response
    {
        //Récupérer le classroom à supprimer
        $Reclamation = $this->getDoctrine()->getRepository(Reclamation::class) ->find($id);
        $Reclamation-> setEtatRec("Traité");
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Reclamation);
        $entityManager->flush();

        $this->addFlash('message','Etat reclamation change vers traité');
        return $this->redirectToRoute('back_reclamation');
    }

}