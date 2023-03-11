<?php

namespace App\Controller;

use App\Entity\Cheques;
use App\Entity\Chequier;
use App\Entity\Compte;
use App\Form\ChequeFormType;
use App\Form\SearchCompteType;
use App\Repository\ChequesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class ChequeController extends AbstractController
{
    /**
     * @Route("/cheque", name="cheque")
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        return $this->render('Cheque/index.html.twig', [
            'controller_name' => 'ChequeController',
        ]);
    }
    /**
     * @Route("/ajouterE/{id}", name="addE")
     * @IsGranted("ROLE_USER")
     */
    public function ajouterE(Request $request, $id): Response
    {
        $cheques=new Cheques();
        $repos= $this->getDoctrine()->getRepository(Compte::class);
        $emt = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $cheques->setClientTel($user->getNumTel());
        //$cheques->
        $form=$this->createForm(ChequeFormType::class,$cheques);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $cheques->setProprietaire($repos->findOneByFullname($id));
            $cheques->setRIBSender($repos->findOneByFullname($id)->getRIBCompte());
            $cheques->setRIBReciever($cheques->getDestinataire()->getRIBCompte());
            $em->persist($cheques);
            $em->flush();
            return $this->redirectToRoute('afficheE');
        }
        return $this->render('Cheque/AjouterCheque.html.twig',
            [
                'form'=>$form->createView()
            ]);
    }
    /**
     * @Route("/afficheE", name="afficheE")
     * @IsGranted("ROLE_USER")
     */
    public function afficheE()
    {
        $user=$this->getUser();
        $chequiers=$user->getChequiers();
        if (!$chequiers->isEmpty())
        {
            $test=false;

            $l_chequier =  $chequiers->last();
            $a=count($chequiers);
            while(!$test)
            {
                if($l_chequier->getEtatChequier()==0)
                {
                    $l_chequier=$chequiers[$a-1];
                    $a--;
                }
                else $test=true;
            }
        }
        $cheques=$l_chequier->getCheques();
        return $this->render('Cheque/AfficherCheque.html.twig',
            ['c'=>$cheques]
        );

    }

    /**
     * @Route("/ModifierE/{id}", name="ModifierE")
     * @IsGranted("ROLE_USER")
     */
    public function ModifierE(Request $req, $id)
    {

        $em=$this->getDoctrine()->getManager();
        $prod = $em->getRepository(Cheques::class)->find($id);
        $form = $this->createForm(ChequeFormType::class,$prod);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('afficheE');
        }
        return $this->render('Cheque/ModifierCheque.html.twig',array("form"=>$form->createView()));
    }
    /**
     * @Route ("/triupcheque", name="croissantcheque")
     * @IsGranted("ROLE_USER")
     */
    public function orderSujetASC(ChequesRepository $repository){
        $plans=$repository->triSujetASC();
        return $this->render('Cheque/AfficherCheque.html.twig', [
            'c' => $plans,
        ]);
    }

    /**
     * @Route("/tridowncheque", name="decroissantcheque")
     * @IsGranted("ROLE_USER")
     */
    public function orderSujetDESC(ChequesRepository $repository){
        $plans=$repository->triSujetDESC();
        return $this->render('Cheque/AfficherCheque.html.twig', [
            'c' => $plans,

        ]);
    }


    /**
     * @Route("/afficheAll", name="afficheAll")
     */
    public function afficheAll(ChequesRepository $repo)
    {
        return $this->render('Cheque/AfficheChecks.html.twig',
            ['c'=>$repo->findAll(),'Message'=>""]
        );
    }

    /**
     * @Route("/encaisser/{id}", name="encaissement")
     */
    public function encaisserId($id,Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Cheques::class);
        $cheque=$repo->find($id);
        $currP=$cheque->getProprietaire()->getSoldeCompte();
        $currD=$cheque->getDestinataire()->getSoldeCompte();
        if($cheque->getMontant() > $currP)
        {
            $cheque->getDestinataire()->setSoldeCompte($currD+$cheque->getMontant());
            $cheque->getProprietaire()->setSoldeCompte($currP-$cheque->getMontant());
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->render('Cheque/AfficheChecks.html.twig',['c'=>$repo->findAll(),'Message'=>'Succès Transaction']);
        }
        return $this->render('Cheque/AfficheChecks.html.twig',['c'=>$repo->findAll(),'Message'=>'Fonds unsufisants']);

    }
    /**
     * @Route("/supprimerCheque/{id}", name="supprimerCheque")
     */
    public function SupprimerCompte($id): Response
    {

        $em=$this->getDoctrine()->getManager();
        $cheque = $em->getRepository(Cheques::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($cheque);
        $entityManager->flush();
        $this->addFlash('message', 'Cheque supprimé avec succès ');
        return $this->redirectToRoute('afficheAll');

    }
}