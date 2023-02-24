<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Transaction;
use App\Entity\Utilisateur;
use App\Form\CompteModifBackType;
use App\Form\CompteType;
use App\Form\SearchCompteType;

use App\Repository\CompteRepository;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Services\QrcodeService;
use Dompdf\Dompdf;
use Dompdf\Options;


class CompteController extends AbstractController
{
    /**
     * @Route("/Compte", name="compte")
     */
    public function index(): Response
    {
        return $this->render('compte/index.html.twig', [
            'controller_name' => 'CompteController',
        ]);
    }

//   ************************** PARTIE BACK ******************************

    /**
     * @Route("/admin/Comptes", name="comptes")
     * Affiche tous les comptes (A utiliser dans la partie Back uniquement)
     */
    public function AfficherComptes(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(Compte::class);
        $comptes = $repository->findAll();
        $searchForm = $this->createForm(SearchCompteType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $name = $searchForm['fullname_client']->getData();
            $comptes = $repository->findCompteByName($name);
//            return $this->render("compte/BackOffice/affichage_back.html.twig", array(
//                "comptes" => $comptes,
//                "form" => $searchForm->createView()));
        }
        return $this->render('compte/BackOffice/affichage_back.html.twig', [
            'comptes' => $comptes,
            "form" => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/admin/supprimerCompte/{id}", name="supprimerCompte")
     * Suppression d'un compte
     */
    public function SupprimerCompte($id): Response
    {
        $compte = $this->getDoctrine()->getRepository(Compte::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($compte);
        $entityManager->flush();
        $this->addFlash('message', 'Compte supprimé avec succès ');
        return $this->redirectToRoute('comptes');

    }

    /**
     * @Route("/admin/ajouterCompte", name="ajouter_compte")
     * Ajouter un compte
     */
    public function ajouterCompte(Request $request): Response
    {
        $compte = new Compte();
        $date = new \DateTime('@' . strtotime('now'));
        $compte->setDateCreation($date);
        $num = $this->GénérerNumCompte();
        $compte->setNumCompte($num);
        $compte->setRIBCompte('123451234 ' . $num . ' 12');
        $compte->setEtatCompte(0);
        $form = $this->createForm(CompteType::class, $compte);
        $form->add("Ajouter Compte", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($compte);
            $entityManager->flush();
            $this->addFlash('message', 'Compte créé avec succès ');
            return $this->redirectToRoute('comptes');
        }
        return $this->render('compte/BackOffice/ajouterCompte.html.twig', [

            'compte' => $compte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/modifierCompte/{id}", name="modifier_compte")
     * Modifier un compte
     */
    public function modifierCompte(Request $request, $id): Response
    {
        $compte = $this->getDoctrine()->getRepository(Compte::class)->find($id);
        $form = $this->createForm(CompteModifBackType::class, $compte);
        $form->add("Modifier Compte", SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $this->addFlash('message', 'Compte modifié avec succès ');
            return $this->redirectToRoute('comptes');
        }
        return $this->render('compte/BackOffice/modifierCompte.html.twig', [
            'compte' => $compte,
            'form' => $form->createView(),
        ]);
    }




    //   ************************** PARTIE FRONT ******************************

    /**
     * @Route("/MonCompte/{id}", name="mon_compte")
     */
    public function AfficherMonCompte($id): Response
    {
        $date = new \DateTime('@' . strtotime('now'));

        $repository = $this->getDoctrine()->getRepository(Compte::class);
        $repository2 = $this->getDoctrine()->getRepository(Transaction::class);
        $compte = $repository->findOneByFullname($id);

        $ajd = $repository2->TriDate();
        if ($compte) {
            if ($compte->getEtatCompte() == 1) {
                $repository2 = $this->getDoctrine()->getRepository(Transaction::class);

                $transactions = $repository2->findByRIBEmetteur($compte->getRIBCompte());
//                $transactions2 = $repository2->StatsEmetteur($compte->getRIBCompte());
//                $transactions3 = $repository2->findByRIBRecepteur2($compte->getRIBCompte());
//
//                var_dump($ajd);
//                exit();
//                $emetteur = [];
//                $recepteur = [];
//                foreach ($transactions2 as $trans) {
//                    $emetteur[] = $trans->getMontantTransaction();
//                }
//                foreach ($transactions3 as $trans) {
//                    $recepteur[] = $trans->getMontantTransaction();
//                }
                return $this->render('compte/FrontOffice/affichage_front.html.twig', [
                    'comptes' => $compte,
                    'transactions' => $transactions,
                    'comptes2' => json_encode($compte->getSeuilCompte()),
                    'ajd' => json_encode($ajd)
//                    'emetteur' => json_encode($emetteur),
//                    'recepteur' => json_encode($recepteur),
                ]);
            } elseif ($compte->getEtatCompte() == 0) {
                return $this->render('compte/FrontOffice/affichage_encours.html.twig', ['comptes' => $compte,]);
            } else {
                return $this->render('compte/FrontOffice/affichage_desactive.html.twig', ['comptes' => $compte,]);
            }
        } else {
            return $this->render('compte/FrontOffice/affichage_inexistant.html.twig');
        }
    }



    /**
     * @Route("/ajouterCompteCourant/{id}", name="ajouter_compte_courant")
     * Ajouter un compte
     */
    public function ajouterCompteCourant($id, Request $request): Response
    {
        $compte = new Compte();
        $user = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id);
        $compte->setFullnameClient($user);
        $num = $this->GénérerNumCompte();
        $compte->setNumCompte($num);
        $compte->setSoldeCompte(0);
        $compte->setTypeCompte('Courant');
        $compte->setSeuilCompte(0);
        $compte->setTauxInteret(0);
        $compte->setRIBCompte('123451234 ' . $num . ' 12');
        $compte->setEtatCompte(1);
        $date = new \DateTime('@' . strtotime('now'));
        $compte->setDateCreation($date);
//        $form = $this->createForm(CompteType::class, $compte);
//        $form->add("Ajouter Compte", SubmitType::class);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($compte);
        $entityManager->flush();
//            return $this->redirectToRoute('comptes');
//        }
        return $this->render('compte/FrontOffice/affichage_encours.html.twig');
    }

    /**
     * @Route("/ajouterCompteEpargne/{id}", name="ajouter_compte_epargne")
     * Ajouter un compte
     */
    public function ajouterCompteEpargne($id, Request $request): Response
    {
        $compte = new Compte();
        $user = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id);
        $compte->setFullnameClient($user);
        $num = $this->GénérerNumCompte();
        $compte->setNumCompte($num);
        $compte->setSoldeCompte(0);
        $compte->setTypeCompte('Epargne');
        $compte->setSeuilCompte(0);
        $compte->setTauxInteret(0);
        $compte->setRIBCompte('123451234 ' . $num . ' 12');
        $compte->setEtatCompte(1);
        $date = new \DateTime('@' . strtotime('now'));
        $compte->setDateCreation($date);
//        $form = $this->createForm(CompteType::class, $compte);
//        $form->add("Ajouter Compte", SubmitType::class);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($compte);
        $entityManager->flush();
//            return $this->redirectToRoute('comptes');
//        }
        return $this->render('compte/FrontOffice/affichage_encours.html.twig');
    }

//   ********************* Fonctions Autres ***************

    public function GénérerNumCompte()
    {
        $random = random_int(00000000000, 99999999999);
        return $random;
    }
}