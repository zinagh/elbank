<?php

namespace App\Controller;

use App\Entity\Credit;
use App\Entity\Operation;
use App\Entity\OperationCredit;
use App\Form\OperationCreditType;
use App\Repository\OperationCreditRepository;
use App\Repository\CreditRepository;
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/tst",name="tst")
     */
    function Simuler(){

        return $this->render("simulation/index.html.twig");
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
    /**
     * @Route("/TriOperationCredit", name="etatOperationCredit")
     */
    public function TriEtatOperationCredit()
    {
        $operationCredit= $this->getDoctrine()->getRepository(OperationCredit::class)->TriEtatOperationCredit();
        return $this->render("operationCredit/affichOperationCredit.html.twig",array('operationCredit'=>$operationCredit));
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
     * @param CreditRepository  $creditrepository
     * @return Response
     * @Route ("/operationCreditFront",name="afficherOC_front")
     */
    function AfficheOperationCreditFront(OperationCreditRepository $repository, CreditRepository $creditRepository){
        $operationCredits=$repository->findAll();
        $credits = $creditRepository->findAll();
        $montantOperations = [];
        $NumOperations = [];
        $i = 1;
        $montCredit = 0;
        $sommeOperation = 0;
        $variations = [];
        foreach ($operationCredits as $operationCredit) {
            $montantOperations[] = $operationCredit->getMontPayer();

        }
        foreach ($credits as $credit) {
            foreach ($operationCredits as $operationCredit)
            { $sommeOperation = $sommeOperation + $operationCredit->getMontPayer();
                $NumOperations[] = $i;
                $i++;
                $variations[] = ($credit->getMontCredit()) - $sommeOperation;}

        }
        return $this->render("operationCredit/afficherOperationCreditFront.html.twig",[
            "operationCredit"=>$operationCredits,
            "montantOperations"=> $montantOperations,
            "variations" => $variations,
            "NumOperations"=>$NumOperations,

        ]);
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/ajoutOperationCreditFront",name="ajoutOperationCreditFront")
     */
    function AjoutOperationCreditFront(Request $request,\Swift_Mailer $mailer){
        $operationCredit=new OperationCredit();
        $form=$this->createForm(OperationCreditType::class,$operationCredit);
        $form->add("Ajouter",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($operationCredit);
            $em->flush();
            $this->addFlash('success', 'Operation créé avec succès ');

            $message = (new \Swift_Message('operation credit'))
                ->setFrom('pidevbrainovation@gmail.com')
                ->setTo('mohamedamine.amdouni@esprit.tn')
                ->setBody(
                    $this->renderView('operationCredit/mailOperationCredit.html.twig'),
                    'text/html'
                );
            $mailer->send($message);
            return $this->redirectToRoute('afficherOC_front');
        }
        return $this->render("operationCredit/ajoutOperationCreditFront.html.twig",["form"=>$form->createView()]);
    }
    /**
     * @param OperationCreditRepository $repository
     * @return Response
     * @Route ("/rechercheByNumerot",name="rechercheByNumero")
     */
    public function rechercheByNumero(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $operationCredit = $em->getRepository(OperationCredit::class)->findAll();
        if ($request->isMethod("POST")) {
            $credit = $request->get('credit');

            $operationCredit = $em->getRepository(OperationCredit::class)->findBy(array('credit' => $credit));
        }
        return $this->render("operationCredit/RechercheOP.html.twig", array('operationCredit' => $operationCredit));


    }
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *@throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route ("/AjoutOperationCreditmobile",name="AjoutOperationCreditmobile")
     */

    function AjoutOperationCreditmobile(Request $request, FlashyNotifier $flashy,NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $dateOp =  new \DateTime('@' . strtotime('now'));
        $montPayer= $request->get("montPayer");
        $echeance =  new \DateTime('@' . strtotime('now'));
        $tauxInteret = $request->get("tauxInteret");
        $solvabilite = $request->get("solvabilite");
        $typeOperation = $request->get("typeOperation");
        $credit= $request->get('credit');



        $credit = $this->getDoctrine()->getRepository(Credit::class)->find($credit);

        $operationCredit = new OperationCredit();
        $operationCredit->setDateOp($dateOp);
        $operationCredit->setMontPayer($montPayer);
        $operationCredit->setEcheance($echeance);
        $operationCredit->setTauxInteret($tauxInteret);
        $operationCredit->setSolvabilite($solvabilite);
        $operationCredit->setTypeOperation($typeOperation);
        $operationCredit->setCredit($credit);


        $em->persist($operationCredit);
        $em->flush();
        $jsonContent=$normalizer->normalize($operationCredit,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));


    }
    /**

     * @param OperationCreditRepository $repository
     * @param NormalizerInterface $normalizer
     *@return Response
     *@throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route ("/AfficheOperationCreditMobile",name="AfficheOperationCreditMobile")
     */

    function AfficheOperationCreditMobile(OperationCreditRepository $repository,NormalizerInterface $normalizer): Response
    {
        $operationCredit = $repository->findAll();

        $jsonContent=$normalizer->normalize($operationCredit,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));


    }
    /**
     * @Route ("/operationCreditFront/charti",name="Operationscharti")
     */
    public function chartAction(CreditRepository $creditRepository, OperationCreditRepository $repository)
    {
        $operationCredits=$repository->findAll();
        $credits = $creditRepository->findAll();
        $montantOperations = [];
        $NumOperations = [];
        $i = 1;
        $montCredit = 0;
        $sommeOperation = 0;
        $variations = [];
        foreach ($operationCredits as $operationCredit) {
            $montantOperations[] = $operationCredit->getMontPayer();

        }
        foreach ($credits as $credit) {
            foreach ($operationCredits as $operationCredit)
            { $sommeOperation = $sommeOperation + $operationCredit->getMontPayer();
                $NumOperations[] = $i;
                $i++;
                $variations[] = ($credit->getMontCredit()) - $sommeOperation;}

        }
        $data = [];
        $labels = [];
        foreach ($variations as $row)
            foreach ($row as $item) {
                $data[] = $item;
                foreach ($montantOperations as $row1)
                    foreach ($row1 as $item1){

                        $labels[] = $item1;

                    }

            }
        $series = [
            [
                "name" => "Statistiques de vos Opérations de paiement",
                "data" => $data,
            ]
        ];

        $ob = new Highchart();
        $ob->chart->renderTo('linechart');
        $ob->title->text('Credits');
        $ob->xAxis->title(array('text' => ""));
        $ob->yAxis->title(array('text' => ""));
        $ob->xAxis->categories($labels);
        $ob->series($series);
        return $this->render("operationCredit/afficherOperationCreditFront.html.twig"
            ,[
                "operationCredit"=>$operationCredits,
                "montantOperations"=> $montantOperations,
                "variations" => $variations,
                "NumOperations"=>$NumOperations,

            ]
        );
    }

}
