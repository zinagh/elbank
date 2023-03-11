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

    /**
     * @Route("/admin/triNomClient", name="tri_nom_client")
     */
    public function TriNomClient(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(Compte::class);
        $comptes = $this->getDoctrine()->getRepository(Compte::class)->TriParNomClient();
        $searchForm = $this->createForm(SearchCompteType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $name = $searchForm['fullname_client']->getData();
            $comptes = $repository->findCompteByName($name);
        }
        return $this->render("compte/BackOffice/affichage_back.html.twig", array('comptes' => $comptes, "form" => $searchForm->createView()));
    }

    /**
     * @Route("/admin/triDateCreation", name="tri_date")
     */
    public function TriParDateCreation(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(Compte::class);
        $comptes = $this->getDoctrine()->getRepository(Compte::class)->TriParDateCreation();
        $searchForm = $this->createForm(SearchCompteType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $name = $searchForm['fullname_client']->getData();
            $comptes = $repository->findCompteByName($name);
        }
        return $this->render("compte/BackOffice/affichage_back.html.twig", array('comptes' => $comptes, "form" => $searchForm->createView()));
    }

    /**
     * @Route("/admin/triEtatCompte", name="tri_etat")
     */
    public function TriParEtatCompte(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(Compte::class);
        $comptes = $this->getDoctrine()->getRepository(Compte::class)->TriParEtatCompte();
        $searchForm = $this->createForm(SearchCompteType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $name = $searchForm['fullname_client']->getData();
            $comptes = $repository->findCompteByName($name);
        }
        return $this->render("compte/BackOffice/affichage_back.html.twig", array('comptes' => $comptes, "form" => $searchForm->createView()));
    }

    /**
     * @Route("/admin/ActiverCompte/{id}", name="activer_compte_back")
     */
    public function ActiverCompteBack($id, \Swift_Mailer $mailer)
    {
        $compte = $this->getDoctrine()->getRepository(Compte::class)->find($id);
        $user = $this->getDoctrine()->getRepository(Utilisateur::class)->find($compte->getFullnameClient());
        $compte->setEtatCompte(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        $this->addFlash("message","Le compte bancaire a bien été activé") ;

//        ****** Partie Mailing ******
        $message = (new \Swift_Message('Activation de votre compte EL BANK'))
            ->setFrom('elbankservices@gmail.com')
            ->setTo($user->getEmailU())
            ->setBody(
                $this->renderView(
                    'emails/activer_compteBancaire.html.twig', ['compte' => $compte,]
                ),
                'text/html'
            );
        $mailer->send($message);
//
//        $this->addFlash("success","Le compte bancaire a bien été activé") ;
        return $this->redirectToRoute('comptes');
    }

    /**
     * @Route("/admin/DesactiverCompte/{id}", name="desactiver_compte_back")
     */
    public function DesactiverCompteBack($id, \Swift_Mailer $mailer)
    {
        $compte = $this->getDoctrine()->getRepository(Compte::class)->find($id);
        $user = $this->getDoctrine()->getRepository(Utilisateur::class)->find($compte->getFullnameClient());
        $compte->setEtatCompte(2);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        $this->addFlash("message","Le compte bancaire a bien été désactivé") ;

//        ****** Partie Mailing ******
        $message = (new \Swift_Message('Désactivation de votre compte EL BANK'))
            ->setFrom('elbankservices@gmail.com')
            ->setTo($user->getEmailU())
            ->setBody(
                $this->renderView(
                    'emails/desactiver_compteBancaire.html.twig', ['compte' => $compte,]
                ),
                'text/html'
            );
        $mailer->send($message);
//
        return $this->redirectToRoute('comptes');
    }
    //   ************************** PARTIE FRONT ******************************

    /**
     * @Route("/MonCompte/{id}", name="mon_compte")
     */
    public function AfficherMonCompte($id, QrcodeService $qrcodeService): Response
    {
        $date = new \DateTime('@' . strtotime('now'));

        $repository = $this->getDoctrine()->getRepository(Compte::class);
        $repository2 = $this->getDoctrine()->getRepository(Transaction::class);
        $compte = $repository->findOneByFullname($id);

        $ajd = $repository2->TriDate();
        if ($compte) {
            if ($compte->getEtatCompte() == 1) {
                $repository2 = $this->getDoctrine()->getRepository(Transaction::class);
                $qrCode = $qrcodeService->qrcode($compte->getFullnameClient()->getId(), $compte->getRIBCompte());
                $transactions = $repository2->findByRIBEmetteur($compte->getRIBCompte());
                $seuilCompte = json_encode($compte->getSeuilCompte());


                $montantTransactions = [];
                foreach ($transactions as $transaction) {
                    $montantTransactions[] = $transaction->getMontantTransaction();
                }


                return $this->render('compte/FrontOffice/affichage_front.html.twig', [
                    'comptes' => $compte,
                    'qrCode' => $qrCode,
                    'transactions' => $transactions,
                    'comptes2' => $seuilCompte,
                    'ajd' => json_encode($ajd),
                    'montantTransactions' => $montantTransactions
//
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
     * @Route("/ReleveCompte/{id}", name="releve_compte")
     */
    public function ReleveCompte($id)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
//        $pdfOptions->set('isRemoteEnabled', true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $repository = $this->getDoctrine()->getRepository(Compte::class);
        $compte = $repository->findOneByFullname($id);
        $repository2 = $this->getDoctrine()->getRepository(Transaction::class);
        $transactionsE = $repository2->findByRIBEmetteur2($compte->getRIBCompte());
        $transactionsR = $repository2->findByRIBRecepteur2($compte->getRIBCompte());

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('compte/FrontOffice/releve_compte.html.twig', [
            'comptes' => $compte,
            'transactionE' => $transactionsE,
            'transactionR' => $transactionsR,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);


        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("RelevéDeCompte.pdf", [
            "Attachment" => false
        ]);
    }

    /**
     * @Route("/ActiverCompteFront/{id}", name="activer_compte_front")
     */
    public function ActiverCompteFront($id, \Swift_Mailer $mailer)
    {
        $compte = $this->getDoctrine()->getRepository(Compte::class)->find($id);
        $user = $this->getDoctrine()->getRepository(Utilisateur::class)->find($compte->getFullnameClient());
        $compte->setEtatCompte(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

//        ****** Partie Mailing ******
        $message = (new \Swift_Message('Activation de votre compte EL BANK'))
            ->setFrom('elbankservices@gmail.com')
            ->setTo($user->getEmailU())
            ->setBody(
                $this->renderView(
                    'emails/activer_compteBancaire.html.twig', ['compte' => $compte,]
                ),
                'text/html'
            );
        $mailer->send($message);
//
        $this->addFlash("success", "Le compte bancaire a bien été activé");
        return $this->redirectToRoute('mon_compte', ['id' => $compte->getFullnameClient()->getId()]);
    }

    /**
     * @Route("/DesactiverCompteFront/{id}", name="desactiver_compte_front")
     */
    public function DesactiverCompteFront($id, \Swift_Mailer $mailer)
    {
        $compte = $this->getDoctrine()->getRepository(Compte::class)->find($id);
        $user = $this->getDoctrine()->getRepository(Utilisateur::class)->find($compte->getFullnameClient());
        $compte->setEtatCompte(2);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

//        ****** Partie Mailing ******
        $message = (new \Swift_Message('Désactivation de votre compte EL BANK'))
            ->setFrom('elbankservices@gmail.com')
            ->setTo($user->getEmailU())
            ->setBody(
                $this->renderView(
                    'emails/desactiver_compteBancaire.html.twig', ['compte' => $compte,]
                ),
                'text/html'
            );
        $mailer->send($message);
//
        $this->addFlash("success", "Le compte bancaire a bien été activé");
        return $this->redirectToRoute('mon_compte', ['id' => $compte->getFullnameClient()->getId()]);
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
        $compte->setEtatCompte(0);
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
        $compte->setEtatCompte(0);
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