<?php

namespace App\Controller;

use App\Entity\Carte;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\CarteType;
use Symfony\Component\Validator\Constraints\Date;
use App\Form\RecherchenumType;
use App\Repository\CarteRepository;
use Dompdf\Dompdf;
use Dompdf\Options;


class CarteController extends AbstractController
{
    /**
     * @Route("/carte", name="carte")
     */
    public function index(): Response
    {
        return $this->render('carte/index.html.twig', [
            'controller_name' => 'CarteController',
        ]);
    }
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/Carte/ajouterCarte",name="ajouterCarte")
     */
    function AjoutCarte(Request $request, UserPasswordEncoderInterface $encoder){
        $carte=new Carte();
        $form=$this->createForm(CarteType::class,$carte);
        $form->add("Ajouter",SubmitType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'CARTE Created!' );
            $em=$this->getDoctrine()->getManager();
            $em->persist($carte);
            $em->flush();
            return $this->redirectToRoute('afficherCarte');
        }
        return $this->render("carte/ajouterCarte.html.twig",["f"=>$form->createView()]);
    }
    /**
     * @param CarteRepository $repository
     * @return Response
     * @Route("/carte/afficherCarte", name="afficherCarte")
     */
    public function AfficherCarte(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Carte::class);
        $carte = $repository->findAll();
        return $this->render    ("carte/afficherCarte.html.twig",["carte"=>$carte]);


    }
    /**
     * @param CarteRepository $repository
     * @param $id
     * @param $request
     * @Route("/modifierCarte/{id}", name="modifierCarte")
     * Modifier un carte
     */
    public function modifierCarte(Request $request, $id): Response
    {
        $carte = $this->getDoctrine()->getRepository(Carte::class)->find($id);
        $form = $this->createForm(CarteType::class, $carte);
        $form->add("Modifier",SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('afficherCarte');
        }
        return $this->render("carte/modifierCarte.html.twig",[
            "carte"=>$carte,
            "f" => $form->createView(),
        ]);
    }
    /**
     * @Route("/supprimerCarte/{id}", name="supprimerCarte")
     * Suppression d'un Carte
     */
    public function SupprimerCarte($id): Response
    {
        $carte = $this->getDoctrine()->getRepository(Carte::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($carte);
        $entityManager->flush();
        return $this->redirectToRoute('afficherCarte');

    }


}