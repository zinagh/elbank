<?php

namespace App\Controller;

use App\Entity\CategorieCarte;
use App\Repository\CategorieCarteRepository;
use App\Form\SearchCategorieCarteType;
use App\Form\PropertySearchType;
use App\Entity\PropertySearch;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\CategorieCarteType;
use Symfony\Component\Validator\Constraints\Date;
class CategorieCarteController extends AbstractController


{
    /**
     * @Route("/categorieCarte/index", name="categorieCarte")
     */
    public function index(): Response
    {
        return $this->render('categorieCarte/index.html.twig', [
            'controller_name' => 'CategorieCarteController',
        ]);
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/CategorieCarte/ajouterCategorieCarte",name="ajouterCategorieCarte")
     */
    function AjoutCategorieCarte(Request $request){
        $categorieCarte=new CategorieCarte();
        $form=$this->createForm(CategorieCarteType::class,$categorieCarte);
        $form->add("Ajouter",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $date = new \DateTime('@' . strtotime('now + 10 day'));

            $categorieCarte->setDateCategorie($date);
            $em=$this->getDoctrine()->getManager();
            $em->persist($categorieCarte);
            $em->flush();
            return $this->redirectToRoute('afficherCategorieCarte');
        }
        return $this->render("categorieCarte/ajouterCategorieCarte.html.twig",["f"=>$form->createView()]);
    }
    /**
     * @param CategorieCarteRepository $repository
     * @return Response
     * @Route("/categoriecarte/afficherCategorieCarte", name="afficherCategorieCarte")
     */
    public function AfficherCategorieCarte(): Response
    {
        $repository = $this->getDoctrine()->getRepository(CategorieCarte::class);
        $categorieCarte = $repository->findAll();
        return $this->render    ("categoriecarte/afficherCategorieCarte.html.twig",["categorieCarte"=>$categorieCarte]);


    }
    /**
     * @param CategorieCarteRepository $repository
     * @return Response
     * @Route("/afficherCategorieCarte}", name="categoriecartefront")
     */
    public function categoriecartefront()
    {
        $repository = $this->getDoctrine()->getRepository(CategorieCarte::class);
        $categorieCarte = $repository->findAll();
        return $this->render('categorieCarte/afficherCategorieCarteFront.html.twig',["categorieCarte"=>$categorieCarte]);
    }
    /**
     * @param CategorieCarteRepository $repository
     * @param $id
     * @param $request
     * @Route("/modifierCategorieCarte/{id}", name="modifierCategorieCarte")
     * Modifier un categoriecarte
     */
    public function modifierCategorieCarte(Request $request, $id): Response
    {
        $categorieCarte = $this->getDoctrine()->getRepository(CategorieCarte::class)->find($id);
        $form = $this->createForm(CategorieCarteType::class, $categorieCarte);
        $form->add("Modifier",SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('afficherCategorieCarte');
        }
        return $this->render("categoriecarte/modifierCategorieCarte.html.twig",[
            "categorieCarte"=>$categorieCarte,
            "f" => $form->createView(),
        ]);
    }
    /**
     * @Route("/supprimerCategorieCarte/{id}", name="supprimerCategorieCarte")
     * Suppression d'un CategorieCarte
     */
    public function SupprimerCompte($id): Response
    {
        $categorieCarte = $this->getDoctrine()->getRepository(CategorieCarte::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($categorieCarte);
        $entityManager->flush();
        return $this->redirectToRoute('afficherCategorieCarte');

    }

}