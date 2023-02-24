<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Transaction;
use App\Form\CompteType;
use App\Form\TransactionFrontQRType;
use App\Form\TransactionFrontType;
use App\Form\TransactionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TransactionController extends AbstractController
{
    /**
     * @Route("/transaction", name="transaction")
     */
    public function index(): Response
    {
        return $this->render('transaction/index.html.twig', [
            'controller_name' => 'TransactionController',
        ]);
    }

    /**
     * @Route("/admin/Transactions", name="transactions")
     */
    public function AfficherTransactions(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Transaction::class);
        $transactions = $repository->findAll();
        return $this->render('transaction/BackOffice/affichage_transaction.html.twig', [
            'transactions' => $transactions,
        ]);
    }

    /**
     * @Route("/admin/supprimerTransaction/{id}", name="supprimerTransaction")
     */
    public function SupprimerTransaction($id): Response
    {
        $transaction = $this->getDoctrine()->getRepository(Transaction::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($transaction);
        $entityManager->flush();
        return $this->redirectToRoute('transactions');

    }

    /**
     * @Route("/admin/ajouterTransaction", name="ajouter_transaction")
     */
    public function ajouterTransaction(Request $request): Response
    {
        $transaction = new Transaction();
        $date = new \DateTime('@' . strtotime('now'));

        $form = $this->createForm(TransactionType::class, $transaction);
        $form->add("Ajouter Transaction", SubmitType::class);
        $form->handleRequest($request);
//        var_dump($transaction);
//        exit();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

//            Diminution/augmentation du solde
            $RIB_emetteur = $form["RIB_emetteur"]->getData();
//            $RIB_recepteur = $form["RIB_recepteur"]->getData();
//            $montant = $form["montant_transaction"]->getData();
//
            $emetteur = $this->getDoctrine()->getRepository(Compte::class)->find($RIB_emetteur);
//            $emetteur->setSoldeCompte($emetteur->getSoldeCompte() - $montant);
//
//            $recepteur = $this->getDoctrine()->getRepository(Compte::class)->findOneByRIB($RIB_recepteur);
//            if ($recepteur) {
//                $recepteur->setSoldeCompte($recepteur->getSoldeCompte() + $montant);
//                $entityManager->persist($recepteur);
//            }
            $transaction->setDateTransaction($date);
            $transaction->setFullnameEmetteur($emetteur);
            $transaction->setEtatTransaction(0);
            $entityManager->persist($transaction);
//            $entityManager->persist($emetteur);
            $entityManager->flush();
            $this->addFlash("message", "Transaction effectuée avec succès");
            return $this->redirectToRoute('transactions');
        }
        return $this->render('transaction/BackOffice/ajouterTransaction.html.twig', [
            'transaction' => $transaction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/modifierTransaction/{id}", name="modifier_transaction")
     */
    public function modifierTransaction(Request $request, $id): Response
    {
        $transaction = $this->getDoctrine()->getRepository(Transaction::class)->find($id);
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->add("Modifier Transaction", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('transactions');
        }
        return $this->render('transaction/BackOffice/modifierTransaction.html.twig', [
            'transaction' => $transaction,
            'form' => $form->createView(),
        ]);
    }









//    ****************** PARTIE FRONT *******************

    /**
     * @Route("/EffectuerTransaction/{id}", name="effectuer_transaction")
     */
    public function effectuerTransaction(Request $request, $id): Response
    {
        $transaction = new Transaction();
        $repository = $this->getDoctrine()->getRepository(Compte::class);
        $compte = $repository->findOneByFullname($id);
        if ($compte) {
            if ($compte->getEtatCompte() == 1) {
                $date = new \DateTime('@' . strtotime('now'));
                $transaction->setDateTransaction($date);
                $transaction->setEtatTransaction(1);
                $transaction->setFullnameEmetteur($compte);
                $transaction->setRIBEmetteur($compte);
                $form = $this->createForm(TransactionFrontType::class, $transaction);
                $form->add("Ajouter Transaction", SubmitType::class);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager = $this->getDoctrine()->getManager();


                    //            Diminution/augmentation du solde
                    $RIB_recepteur = $form["RIB_recepteur"]->getData();
                    $montant = $form["montant_transaction"]->getData();

                    $emetteur = $this->getDoctrine()->getRepository(Compte::class)->find($compte);
                    $emetteur->setSoldeCompte($emetteur->getSoldeCompte() - $montant);

                    $recepteur = $this->getDoctrine()->getRepository(Compte::class)->findOneByRIB($RIB_recepteur);
                    if ($recepteur) {
                        $recepteur->setSoldeCompte($recepteur->getSoldeCompte() + $montant);
                        $entityManager->persist($recepteur);
                    }

                    $entityManager->persist($transaction);
                    $entityManager->persist($emetteur);
                    $entityManager->flush();
                    $this->addFlash("message", "Transaction effectuée avec succès");

                    return $this->redirectToRoute('mon_compte', ['id' => $transaction->getRIBEmetteur()->getFullnameClient()->getId()]);
                }
                return $this->render('transaction/FrontOffice/ajouterTransaction.html.twig', [
                    'transaction' => $transaction,
                    'compte' => $compte,
                    'form' => $form->createView(),
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


}