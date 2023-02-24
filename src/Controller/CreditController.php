<?php

namespace App\Controller;

use App\Entity\Credit;
use App\Entity\Compte;
use App\Entity\OperationCredit;
use App\Form\CreditType;
use App\Form\OperationCreditType;
use App\Repository\CreditRepository;
use App\Repository\OperationCreditRepository;
use Composer\Autoload\ClassLoader;
use Doctrine\ORM\EntityManager;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use function Sodium\add;

class CreditController extends AbstractController
{
    /**
     * @Route("/credit", name="credit")
     */
    public function index(): Response
    {
        return $this->render('credit/index.html.twig', [
            'controller_name' => 'CreditController',
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/credit/ajoutCredit",name="ajoutCredit")
     */
    function AjoutCredit(Request $request)
    {
        $credit = new Credit();
        $form = $this->createForm(CreditType::class, $credit);
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($credit);
            $em->flush();
            $this -> addFlash("message", "Votre crédit est bien ajouté");

            return $this->redirectToRoute('affichCredit');
        }
        return $this->render("credit/ajoutCredit.html.twig", ["f" => $form->createView()]);
    }

    /**
     * @param CreditRepository $repository
     * @param $id
     * @param $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/credit/modifCredit/{id}",name="modifCredit")
     */
    function ModifCredit(CreditRepository $repository, $id, Request $request)
    {
        $credit = $repository->find($id);
        $form = $this->createForm(CreditType::class, $credit);
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('affichCredit');
        }
        return $this->render("credit/modifCredit.html.twig", ["f" => $form->createView()]);
    }


    /**
     * @param CreditRepository $repository
     * @return Response
     * @Route ("/credit/affichCredit",name="affichCredit")
     */
    function AfficheCredit(CreditRepository $repository)
    {
        $credits = $repository->findAll();
        return $this->render("credit/affichCredit.html.twig", ["credits" => $credits]);
    }
    /**
     * @param OperationCreditRepository $repository
     * @return Response
     * @Route ("/AfficheCreditFront",name="AfficheCreditFront")
     */
    function AfficheCreditFront(CreditRepository $repository){
        $Credit=$repository->findAll();
        return $this->render("Credit/afficherCreditFront.html.twig",["Credit"=>$Credit]);
    }

    /**
     * @param CreditRepository $repository
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("/credit/suppCredit/{id}",name="suppCredit")
     */
    function SuppCredit(CreditRepository $repository, $id)
    {
        $credit = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($credit);
        $em->flush();
        return $this->redirectToRoute('affichCredit');
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/credit/ajoutCreditFront",name="ajoutCreditFront")
     */
    function AjoutCreditFront(Request $request)
    {
        $Credit = new Credit();
        $form = $this->createForm(CreditType::class, $Credit);
        $form->remove('tauxInteret');
        $form->remove('decision');
        $form->remove('etatCredit');
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Credit);
            $em->flush();
            $this->addFlash('success', 'Demande de Credit créé avec succès ');
            return $this->redirectToRoute('ajoutCreditFront');
        }
        return $this->render("Credit/ajoutCreditFront.html.twig", ["for" => $form->createView()]);
    }





}
