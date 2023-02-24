<?php

namespace App\Controller;

use App\Entity\Chequier;
use App\Entity\Compte;
use App\Form\ChequierFormType;
use App\Repository\ChequierRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Services\SmsGatewayService;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ChequierController extends AbstractController
{
    /**
     * @Route("/chequier", name="chequier", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function index(ChequierRepository $chequierRepository): Response
    {
        return $this->render('chequier/AfficherChequier.html.twig', [
            'chequiers' => $chequierRepository->findAll(),
        ]);
    }
    /**
     * @Route("/AjouterChequier/{id}", name="AjouterChequier")
     * @IsGranted("ROLE_USER")
     */
    public function ajouter(Request $request, $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Compte::class);
        $compte = $repository->findOneByFullname($id);

        $cheques=new Chequier();
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $test = $em->getRepository(Compte::class)->find($compte);
        $RIB_Compte = $test->getRIBCompte();
        // $cheques->setNumCompte($test->getRIBCompte());
        $cheques->setNomClient($user);
        $cheques->setEtatChequier(1);
        $cheques->setClientTel($user->getNumTel());

        $number = $cheques->getClientTel();
        $form=$this->createForm(ChequierFormType::class,$cheques);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($cheques);
            $em->flush();

            $this->addFlash("success","L'employer a reçu une demande du chequier") ;

            return $this->redirectToRoute('cheque');
        }
        return $this->render('chequier/AjouterChequier.html.twig',
            [
                'form'=>$form->createView()
            ]);
    }
    /**
     * @Route("/supprimer/{id}", name="suppE")
     * @IsGranted("ROLE_USER")
     */
    public function supprimer($id): Response
    {

        $em = $this->getDoctrine()->getManager();

        $chequier = $em->getRepository(Chequier::class)->find($id);
        $number = $chequier->getClientTel();
        $em->remove($chequier);
        $em->flush();



        $this->addFlash("success","L'employer a reçu un message de suppression du chequier") ;
        return $this->redirectToRoute('afficherchequierb');
    }
    /**
     * @Route("/afficherchequierb", name="afficherchequierb")
     * @IsGranted("ROLE_USER")
     */
    public function afficheE(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Chequier::class)->findAll();
        $chequier = $repo;
        return $this->render('chequier/index.html.twig',
            ['chequier'=>$chequier]);
    }


}