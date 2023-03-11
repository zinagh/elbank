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
    public function ajouter(Request $request,SmsGatewayService $smsGateway, $id): Response
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
        $cheques->setEtatChequier(0);
        $cheques->setClientTel($user->getNumTel());

        $number = $cheques->getClientTel();
        $form=$this->createForm(ChequierFormType::class,$cheques);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($cheques);
            $em->flush();
            try {
                $smsGateway->send([
                    [
                        "to" => '00216'.$number,
                        "from" => "EL Bank",
                        "message" => "Votre chequier a été bloqué"
                    ]
                ]);
            } catch (\Throwable $apiException) {
                // HANDLE THE EXCEPTION
                dump($apiException);
            }

            $this->addFlash("success","L'employer a reçu un SMS de bloquage du chequier") ;

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
    public function supprimer($id,SmsGatewayService $smsGateway): Response
    {

        $em = $this->getDoctrine()->getManager();

        $chequier = $em->getRepository(Chequier::class)->find($id);
        $number = $chequier->getClientTel();
        $em->remove($chequier);
        $em->flush();

        try {
            $smsGateway->send([
                [
                    "to" => $number,
                    "from" => "EL Bank",
                    "message" => "Votre chequier a été supprimé"
                ]
            ]);
        } catch (\Throwable $apiException) {
            // HANDLE THE EXCEPTION
            dump($apiException);
        }

        $this->addFlash("success","L'employer a reçu un SMS de bloquage du chequier") ;
        return $this->redirectToRoute('afficherchequierb');
    }
    /**
     * @Route("/afficherchequierb", name="afficherchequierb")
     * @IsGranted("ROLE_USER")
     */
    public function afficheE(Request $request, PaginatorInterface $paginator)
    {
        $repo = $this->getDoctrine()->getRepository(Chequier::class)->findAll();
        $chequier = $paginator->paginate(
            $repo,
            $request->query->getInt('page', 1),
            2
        );
        return $this->render('chequier/index.html.twig',
            ['chequier'=>$chequier]);
    }

    /**
     * @Route ("/triup", name="croissant")
     * @IsGranted("ROLE_USER")
     */
    public function orderSujetASC(ChequierRepository $repository){
        $plans=$repository->triSujetASC();
        return $this->render('chequier/index.html.twig', [
            'chequier' => $plans,
        ]);
    }

    /**
     * @Route("/tridown", name="decroissant")
     * @IsGranted("ROLE_USER")
     */
    public function orderSujetDESC(ChequierRepository $repository){
        $plans=$repository->triSujetDESC();
        return $this->render('chequier/index.html.twig', [
            'chequier' => $plans,

        ]);
    }

    /**
     * @Route("/ConfirmerChequier/{id}", name="confirmation")
     * @param $id
     * @param SmsGatewayService $smsGateway
     * @return
     */
    public function ActiverCompte($id,SmsGatewayService $smsGateway)
    {
        $cheques = $this->getDoctrine()->getRepository(Chequier::class)->find($id);
        $cheques->setEtatChequier(1);
        $number = $cheques->getClientTel();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        try {
            $smsGateway->send([
                [
                    "to" => $number,
                    "from" => "EL Bank",
                    "message" => "Confirmation du votre chequier a été effectué"
                ]
            ]);
        } catch (\Throwable $apiException) {
            // HANDLE THE EXCEPTION
            dump($apiException);
        }

        $this->addFlash("success","L'employer a reçu un SMS de confirmation du chequier") ;
        return $this->redirectToRoute('afficherchequierb', ['id' => $cheques->getNomClient()->getId()]);
    }

    /**
     * @Route("/BloquerChequier/{id}", name="bloquage")
     * @param $id
     * @param SmsGatewayService $smsGateway
     * @return
     */
    public function BloquerCompte($id,SmsGatewayService $smsGateway)
    {
        $cheques = $this->getDoctrine()->getRepository(Chequier::class)->find($id);
        $cheques->setEtatChequier(0);
        $number = $cheques->getClientTel();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        try {
            $smsGateway->send([
                [
                    "to" => $number,
                    "from" => "EL Bank",
                    "message" => "Votre chequier a été bloqué"
                ]
            ]);
        } catch (\Throwable $apiException) {
            // HANDLE THE EXCEPTION
            dump($apiException);
        }

        $this->addFlash("success","L'employer a reçu un SMS de bloquage du chequier") ;
        return $this->redirectToRoute('afficherchequierb', ['id' => $cheques->getNomClient()->getId()]);
    }


    /**
     * @Route("/search", name="search_chequier", requirements={"id":"\d+"})

     */
    public function searchChequier(Request $request, NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Chequier::class);
        $requestString = $request->get('searchValue');
        $chequier = $repository->findChequierByMotif($requestString);
        $jsonContent = $Normalizer->normalize($chequier, 'json',[]);

        return new Response(json_encode($jsonContent));

    }
}