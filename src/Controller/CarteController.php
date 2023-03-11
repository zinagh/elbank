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
    /**
     * @Route("/triNum_carte", name="tri_num_carte")
     */
    public function TriIDCarte()
    {
        $carte= $this->getDoctrine()->getRepository(Carte::class)->TriParNumCarte();
        return $this->render("carte/afficherCarte.html.twig",array('carte'=>$carte));
    }
    /**
     * @Route("/recherchenum", name="recherchenum")
     */
    public function recherchenum(Request $request, CarteRepository $repository)
    {
        // Trouver tous les articles
        $carte= $repository->findAll();

        //recherche
        $searchForm = $this->createForm(RecherchenumType::class);
        $searchForm->add("Recherche",SubmitType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $num_carte = $searchForm['num_carte']->getData();
            $resultOfSearch = $repository->recherchenum($num_carte);
            return $this->render('carte/recherchenum.html.twig', array(
                "resultOfSearch" => $resultOfSearch,
                "recherchenum" => $searchForm->createView()));
        }
        return $this->render('carte/recherchenum.html.twig', array(
            "carte" => $carte,
            "recherchenum" => $searchForm->createView()));
    }
    /**
     * @param Carte $Carte
     * @Route("/carteprint/{id}", name="carteprint")
     */
    public function ReleveCarte($id)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
//        $pdfOptions->set('isRemoteEnabled', true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $repository = $this->getDoctrine()->getRepository(Carte::class);
        $Carte = $repository->findOneByid($id);



        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('Carte/pdf.html.twig', [
            'Carte' => $Carte ,

        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);


        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("carte.pdf", [
            "Attachment" => false
        ]);
    }


}