<?php

namespace App\Controller;

use App\Entity\Credit;
use App\Entity\Operation;
use App\Entity\OperationCredit;
use App\Form\OperationCreditType;
use App\Repository\OperationCreditRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\AbstractType;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
class OperationCreditController extends AbstractController
{
    /**
     * @Route("/operationCredit", name="operationCredit")
     */
    public function index(): Response
    {
        return $this->render('operationCredit/index.html.twig', [
            'controller_name' => 'OperationCreditController',
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/operationCredit/ajoutOperationCredit",name="ajoutOperationCredit")
     */
    function AjoutOperationCredit(Request $request){
        $operationCredit=new OperationCredit();
        $form=$this->createForm(OperationCreditType::class,$operationCredit);
        $form->add("Ajouter",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($operationCredit);
            $em->flush();

            return $this->redirectToRoute('affichOperationCredit');
        }
        return $this->render("operationCredit/ajoutOperationCredit.html.twig",["f"=>$form->createView()]);
    }

    /**
     * @param OperationCreditRepository $repository
     * @param $id
     * @param $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/operationCredit/modifOperationCredit/{id}",name="modifOperationCredit")
     */
    function ModifOperationCredit(OperationCreditRepository $repository,$id,Request $request){
        $operationCredit=$repository->find($id);
        $form=$this->createForm(OperationCreditType::class,$operationCredit);
        $form->add("Modifier",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('affichOperationCredit');
        }
        return $this->render("operationCredit/modifOperationCredit.html.twig",["f"=>$form->createView()]);
    }


    /**
     * @param OperationCreditRepository $repository
     * @return Response
     * @Route ("/operationCredit/affichOperationCredit",name="affichOperationCredit")
     */
    function AfficheOperationCredit(OperationCreditRepository $repository){
        $operationCredit=$repository->findAll();
        return $this->render("operationCredit/affichOperationCredit.html.twig",["operationCredit"=>$operationCredit]);
    }

    /**
     * @param OperationCreditRepository $repository
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("/operationCredit/suppOperationCredit/{id}",name="suppOperationCredit")
     */
    function SuppOperationCredit(OperationCreditRepository $repository,$id){
        $operationCredit=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($operationCredit);
        $em->flush();
        return $this->redirectToRoute('affichOperationCredit');
    }

    //    ******************  FRONT *******************
    /**
     * @Route("/EffectuerOperationCredit", name="effectuer_operationCredit")
     */
    public function effectuerOperationCredit(Request $request): Response
    {
        $operationCredit = new OperationCredit();
        $date = new \DateTime('@'.strtotime('now'));
        $operationCredit->setDateOperationCredit($date);
        $form = $this->createForm(OperationCreditType::class,$operationCredit);
        $form->add("Ajouter OperationCredit",SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($operationCredit);
            $entityManager->flush();
            return $this->redirectToRoute('mon_compte');
        }
        return $this->render('operationCredit/FrontOffice/ajouterOperationCredit.html.twig', [
            'operationCredit' => $operationCredit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param OperationCreditRepository $repository
     * @return Response
     * @Route ("/operationCreditFront",name="afficherOC_front")
     */
    function AfficheOperationCreditFront(OperationCreditRepository $repository){
        $operationCredit=$repository->findAll();
        return $this->render("operationCredit/afficherOperationCreditFront.html.twig",["operationCredit"=>$operationCredit]);
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/ajoutOperationCreditFront",name="ajoutOperationCreditFront")
     */
    function AjoutOperationCreditFront(Request $request){
        $operationCredit=new OperationCredit();
        $form=$this->createForm(OperationCreditType::class,$operationCredit);
        $form->add("Ajouter",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($operationCredit);
            $em->flush();
            $this->addFlash('success', 'Operation créé avec succès ');


            return $this->redirectToRoute('afficherOC_front');
        }
        return $this->render("operationCredit/ajoutOperationCreditFront.html.twig",["form"=>$form->createView()]);
    }

}
