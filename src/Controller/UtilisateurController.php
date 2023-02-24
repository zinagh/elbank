<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\EditProfileType;
use App\Form\ModiferUType;
use App\Form\ModifierUBackType;
use App\Form\ResetPassType;
use App\Form\UtilisateurBackType;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use http\Client\Curl\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


class UtilisateurController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $Utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $Utilisateur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($Utilisateur, $Utilisateur->getMotDePasse());
            $Utilisateur->setMotDePasse($hash);

            $Utilisateur = $form->getData();
            $Utilisateur->setEtat("Debloquer");
            $em = $this->getDoctrine()->getManager();
            $em->persist($Utilisateur);
            $em->flush();




            //return $this->redirectToRoute('login');
        }
        return $this->render('utilisateur/Inscription.html.twig', [
            'formInscription' => $form->createView()]);
    }



    /**
     * @Route ("/login" , name="login")
     */
    public function login()
    {
        return $this->render('utilisateur/login.html.twig');
    }


    /**
     * @Route ("/logout" , name="logout")
     */
    public function logout()
    {
    }

    /**
     * @Route ("/base_front_connecte" , name="base_front_connecte")
     */

    public function base_front_connecte()
    {
        return $this->render('base_front_connecte.html.twig');
    }

    /**
     * @Route ("/error" , name="error")
     */

    public function error()
    {
        return $this->render('error.html.twig');
    }

    /**
     * @Route ("/profile" , name="profile")
     */

    public function profile()
    {
        return $this->render('utilisateur/profile.html.twig');
    }

    /**
     * @Route ("/mesReclamations" , name="mesReclamations")
     */

    public function mesReclamations()
    {
        return $this->render('utilisateur/mesReclamations.html.twig');
    }

    /**
     * @Route("/editProfile", name="editProfile")
     */
    public function editProfile(Request $request): Response
    {
        //Récupérer le classroom à supprimer
        $Utilisateur = $this->getUser();

        // Construction du formulaire
        $form = $this->createForm(EditProfileType::class, $Utilisateur);

        //recuperer les donnees saisies
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            $this->addFlash('message', 'profile mis a jour avec succès');
            return $this->redirectToRoute('profile');
        }
        return $this->render('utilisateur/editProfile.html.twig', [
            //'controller_name' => 'ClassroomController',
            'formEdit' => $form->createView()
        ]);
    }


    /**
     * @Route("/editPass", name="editPass")
     */
    public function editPass(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($request->isMethod('post')) {
            $em = $this->getDoctrine()->getManager();
            $utilisateur = $this->getUser();
            $ancien = $utilisateur->getMotDePasse();
            $test = $passwordEncoder->encodePassword($utilisateur, $request->request->get('pass0'));
            if ($ancien == $test) {
                if ($request->request->get('pass1') == $request->request->get('pass2')) {
                    $utilisateur->setMotDePasse($passwordEncoder->encodePassword($utilisateur, $request->request->get('pass1')));
                    $em->flush();
                    $this->addFlash('message', 'mot de passe changé avec succes');

                    return $this->redirectToRoute('profile');
                } else {
                    $this->addFlash('error', 'Les deux champs de nouveau mot de passe ne sont pas identiques');
                }
            } else {
                $this->addFlash('error', 'ancien mot de passe incorrect');
            }

        }
        return $this->render('utilisateur/editPass.html.twig');
    }



    /**
     * @Route ("/logout2" , name="logout2")
     */
    public function logout2()
    {
        $utilisateur = $this->getUser();
        $utilisateur->setConnecteToken(null);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('logout');

    }

    /**
     * @Route ("/check" , name="check")
     */
    public function check(TokenGeneratorInterface $tokenGenerator,
                          TokenStorageInterface   $tokenStorage)
    {
        $utilisateur = $this->getUser();
        $token = $tokenGenerator->generateToken();

        if ($utilisateur->getBloquerToken() != null) {
            $tokenStorage->setToken();
            $this->addFlash('danger', "Sorry , vous êtes bloqué par l'admin du site");
            return $this->redirectToRoute("login");
        } elseif ($utilisateur->getActivationToken() != null) {
            $tokenStorage->setToken();
            $this->addFlash('warning', "Merci de verfier l'activation de votre compte");
            return $this->redirectToRoute("login");
        } else {
            $utilisateur->setConnecteToken($token);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            if (in_array('ROLE_ADMIN', $utilisateur->getRoles())) {
                return $this->redirectToRoute('admin');
            } elseif (in_array('ROLE_EMPLOYEE', $utilisateur->getRoles())) {
                return $this->redirectToRoute('admin');
            } elseif (in_array('ROLE_USER', $utilisateur->getRoles())) {
                return $this->redirectToRoute('base_front_connecte');
            } else {
                return $this->render('notfound/index.html.twig');
            }
        }
    }
}