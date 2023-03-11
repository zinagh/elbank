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
use Knp\Component\Pager\PaginatorInterface;
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
    function AfficheCredit(CreditRepository $repository,Request $request, PaginatorInterface $paginator)
    {
        $repo = $repository->findAll();
        $credits = $paginator->paginate(
            $repo,
            $request->query->getInt('page', 1),
            2
        );
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
     * @Route ("/credit/test",name="test")
     */
    public function chartAction(CreditRepository $repository)
    {
        $result = $repository->getecheancesnumber();
        $data = [];
        $labels = [];
        foreach ($result as $row)
            foreach ($row as $item) {
                $data[] = $item;
                $result1=$repository->getecheances();
                foreach ($result1 as $row1)
                    foreach ($row1 as $item1){

                        $labels[] = $item1->format( 'd/m/Y' );

                    }

            }
        $series = [
            [
                "name" => "test series",
                "data" => $data,
            ]
        ];

        $ob = new Highchart();
        $ob->chart->renderTo('linechart');
        $ob->title->text('Credits');
        $ob->xAxis->title(array('text' => "Date d'échéance "));
        $ob->yAxis->title(array('text' => "Nombre de echeances"));
        $ob->xAxis->categories($labels);
        $ob->series($series);
        return $this->render('index.html.twig', array(
            'chart' => $ob,
            'c' => $result,
        ));
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

    /**
     *Method("Post")
     * @Route("/credit/rechercheBytype", name="rechercheBytype")
     */
    public function rechercheBytype(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $Credit = $em->getRepository(Credit::class)->findAll();
        if ($request->isMethod("POST")) {
            $typeCredit = $request->get('typeCredit');
            $Credit = $em->getRepository(Credit::class)->findBy(array('typeCredit' => $typeCredit));
        }
        return $this->render("Credit/Recherche.html.twig", array('Credit' => $Credit));


    }

    /**
     * @Route("/TrimontCredit", name="TrimontCredit")
     */
    public function TrimontCredit()
    {
        $Credit = $this->getDoctrine()->getRepository(Credit::class)->TrimontCredit();
        return $this->render("Credit/Recherche.html.twig", array('Credit' => $Credit));
    }

    /**
     * @Route("/TriDatepe", name="TriDatepe")
     */
    public function TriDatepe()
    {
        $Credit = $this->getDoctrine()->getRepository(Credit::class)->TriDatepe();
        return $this->render("Credit/Recherche.html.twig", array('Credit' => $Credit));
    }

    /**
     * @Route("/TriDatede", name="TriDatede")
     */
    public function TriDatede()
    {
        $Credit = $this->getDoctrine()->getRepository(Credit::class)->TriDatede();
        return $this->render("Credit/Recherche.html.twig", array('Credit' => $Credit));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *@throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route ("/credit/AjoutCreditmobile",name="AjoutCreditmobile")
     */

    function AjoutCreditmobile(Request $request, FlashyNotifier $flashy,NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();

        $montCredit = $request->get("montCredit");
        $datepe =  new \DateTime('@' . strtotime('now'));
        $datede = new \DateTime('@' . strtotime('now'));
        $dureeC = $request->get("dureeC");
        $echeance =  new \DateTime('@' . strtotime('now'));
        $tauxInteret = $request->get("tauxInteret");
        $decision = $request->get("decision");
        $etatCredit = $request->get("etatCredit");
        $typeCredit = $request->get("typeCredit");
        $numero_compte = $request->get("numero_compte");
        $compte = $this->getDoctrine()->getRepository(Compte::class)->find($numero_compte);

        //$numerocompte = $request->get('numerocompte', EntityType::class, ['class' => Compte::class, 'choice_label' => function($compte){return $compte->getNumCompte();}])
        //$numerocompte = $request->get("numerocompte");

        //$numero_compte = $this->getDoctrine()->getRepository(compte::class)->find($numero_compte);

        $credit = new Credit();
        $credit->setMontCredit($montCredit);
        $credit->setDatepe($datepe);
        $credit->setDatede($datede);
        $credit->setDureeC($dureeC);
        $credit->setEcheance($echeance);
        $credit->setTauxInteret($tauxInteret);
        $credit->setDecision($decision);
        $credit->setEtatCredit($etatCredit);
        $credit->setTypeCredit($typeCredit);
        $credit->setNumeroCompte($compte);
        //$credit->setNumeroCompte(?Compte $numerocompte);

        $em->persist($credit);
        $em->flush();
        $jsonContent=$normalizer->normalize($credit,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));


    }


    /**

     * @param CreditRepository $repository
     * @param NormalizerInterface $normalizer
     *@return Response
     *@throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route ("/credit/AfficheCreditMobile",name="AfficheCreditMobile")
     */

    function AfficheCreditMobile(CreditRepository $repository,NormalizerInterface $normalizer): Response
    {
        $credits = $repository->findAll();

        $jsonContent=$normalizer->normalize($credits,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));


    }
    /**
     * @Route("/modifierP/{id}", name="modifierPublicationMobile")
     */
    /**
     * @param CreditRepository $repository
     * @param $id
     * @param $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/credit/modifierCreditMobile/{id}",name="modifierCreditMobile")
     */
    public function modifierCreditMobile(Request $request, NormalizerInterface $normalizer, $id)
    {

        $em = $this->getDoctrine()->getManager();

        $montCredit = $request->get("montCredit");
        $datepe =  new \DateTime('@' . strtotime('now'));
        $datede = new \DateTime('@' . strtotime('now'));
        $dureeC = $request->get("dureeC");
        $echeance =  new \DateTime('@' . strtotime('now'));
        $tauxInteret = $request->get("tauxInteret");
        $decision = $request->get("decision");
        $etatCredit = $request->get("etatCredit");
        $typeCredit = $request->get("typeCredit");



        $credit = $em->getRepository(credit::class)->find($id);

        $credit->setMontCredit($montCredit);
        $credit->setDatepe($datepe);
        $credit->setDatede($datede);
        $credit->setDureeC($dureeC);
        $credit->setEcheance($echeance);
        $credit->setTauxInteret($tauxInteret);
        $credit->setDecision($decision);
        $credit->setEtatCredit($etatCredit);
        $credit->setTypeCredit($typeCredit);

        $em->persist($credit);
        $em->flush();

        $jsonContent=$normalizer->normalize($credit,'json',['groups'=>'post:read']);
        return new JsonResponse("credit modifiée avec succès".json_encode($jsonContent));

        //RESPONSE JSON FROM OUR SERVER
        /* $normalizer = new ObjectNormalizer();
         $normalizer->setCircularReferenceHandler(function ($object) {
             return $object->getId();
         }); */

        //  $serializer = new Serializer([$normalizer],[$encoder]);
        //$formatted = $serializer->normalize($prod);

    }
    /**
     * @param NormalizerInterface $normalizer
     * @param $id
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route ("/credit/DeleteCreditMobile/{id}",name="DeleteCreditMobile")
     */
    function DeleteCreditMobile(NormalizerInterface $normalizer,$id): Response
    {
        $em=$this->getDoctrine()->getManager();
        $credit=$em->getRepository(Credit::class)->find($id);
        $em->remove($credit);
        $em->flush();
        $jsonContent=$normalizer->normalize($credit,'json',['groups'=>'post:read']);
        return new Response("Credit deleted successfully".json_encode($jsonContent));
    }
    /**
     * @Route("/credit/acceptCredit/{id}", name="acceptCredit")
     */
    public function acceptCredit($id): Response
    {
        $credit = $this->getDoctrine()->getRepository(Credit::class) ->find($id);
        $credit-> setEtatCredit("Obtenu");
        $credit-> setDecision(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($credit);
        $entityManager->flush();
        return $this->redirectToRoute('affichCredit');
    }
}
