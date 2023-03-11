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
    public function inscription(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer): Response
    {
        $Utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $Utilisateur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($Utilisateur, $Utilisateur->getMotDePasse());
            $Utilisateur->setMotDePasse($hash);
            //on gere le token d'activation -> lors de l'inscription le user avoir automatiquement un token aletoire unique
            $Utilisateur->setActivationToken(md5(uniqid()));

            $Utilisateur = $form->getData();
            $Utilisateur->setEtat("Debloquer");
            $em = $this->getDoctrine()->getManager();
            $em->persist($Utilisateur);
            $em->flush();

            //o cree un msg
            $message = (new \Swift_Message('Activation de votre compte'))
                //on attribut l'expediteur
                ->setFrom('elbankservices@gmail.com')
                //on atribut le destinataire
                ->setTo($Utilisateur->getEmailU())
                // on met le contenu
                ->setBody(
                    $this->renderView(
                        'emails/activation.html.twig', ['token' => $Utilisateur->getActivationToken()]
                    ),
                    'text/html'
                );
            //on envoie le mail
            $mailer->send($message);
            $this->addFlash('message', 'Un email de verification de compte a ete envoyee');


            //return $this->redirectToRoute('login');
        }
        return $this->render('utilisateur/inscription.html.twig', [
            'formInscription' => $form->createView()]);
    }

    /**
     * @Route ("/activation/{token}",name="activation")
     */
    public function activation($token, UtilisateurRepository $utilisateurRep)
    {
        //on verfie si un utilisateur a un token
        $utilisateur = $utilisateurRep->findOneBy(['activation_token' => $token]);
        //si aucun utilisateur existe avec ce token
        if (!$utilisateur) {
            //error 404
            throw $this->createNotFoundException('cet utilisateur n\'existe pas');
        }
        //on supprime le token
        $utilisateur->setActivationToken(null);
        $enityManager = $this->getDoctrine()->getManager();
        $enityManager->persist($utilisateur);
        $enityManager->flush();
        //on envoie un msg flash
        $this->addFlash('message', 'vous avez bien activer votre compte');
        //retourne a le profile
        return $this->redirectToRoute('login');
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
     * @Route ("/mdpOubliee" , name = "mdpOubliee")
     */
    public function forgottenPass(Request                 $request, UtilisateurRepository $utilisateurRepo, \Swift_Mailer $mailer,
                                  TokenGeneratorInterface $tokenGenerator)
    {
        //on cree le formulaire
        $form = $this->createForm(ResetPassType::class);
        //on fait le traitement
        $form->handleRequest($request);
        //si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {
            //on recupere les donnees
            $donnees = $form->getData();
            //on cherche si l'utilisateur a cet email
            $Utilisateur = $utilisateurRepo->findOneBy(array('email_u' => $donnees));
            //si l'utilisateur n'existe pas
            if (!$Utilisateur) {
                //on envoie un msg flash
                $this->addFlash('danger', 'cette adresse email n\'existe pas');
                return $this->redirectToRoute('login');
            }
            $token = $tokenGenerator->generateToken();
            try {
                $Utilisateur->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($Utilisateur);
                $entityManager->flush();

            } catch (\Exception $e) {
                $this->addFlash('warning', 'une erreur est survenue :' . $e->getMessage());
                return $this->redirectToRoute('login');
            }

            //on genere l url de reinisialisation de mdp
            $url = $this->generateUrl('resetPassword', ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL);
            // on envoie le message
            $message = (new \Swift_Message('EL BANK - Mot de passe oublié'))
                //on attribut l'expediteur
                ->setFrom('elbankservices@gmail.com')
                //on atribut le destinataire
                ->setTo($Utilisateur->getEmailU())
                // on met le contenu
                ->setBody(
                    " <p>Bonjour,</p><p>Une demande de reinitialisation de mot de passe a ete effectuee pour le
                        site EL Bank . Veuiller cliquer sur le lien suivant :  $url  </p>",
                    'text/html'

                );
            //on envoie le mail
            $mailer->send($message);
            //on cree le msg flash
            $this->addFlash('message', 'Un email de reinitialisation de mot de passe a ete envoyee');
            //return $this->redirectToRoute('login');

        }
        //on envoie vers la page de demande de l email
        return $this->render('utilisateur/motDePasseOubliee.html.twig', ['emailForm' => $form->createView()]);
    }

    /**
     * @Route ("/resetPassword/{token}" ,name = "resetPassword")
     */
    public function resetPassword($token, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        //chercher l'utilisateur avec le token fourni
        $Utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(['reset_token' => $token]);
        if (!$Utilisateur) {
            $this->addFlash('danger', 'Token inconnu');
            return $this->redirectToRoute('login');
        }
        //si le formulaire est envoee avec methode post
        if ($request->isMethod('POST')) {
            //on supprime le token
            $Utilisateur->setResetToken(null);
            //on chiffre le mdp
            $Utilisateur->setMotDePasse($passwordEncoder->encodePassword($Utilisateur, $request->request->get('password')));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Utilisateur);
            $entityManager->flush();

            $this->addFlash('message', 'mot de passe bien modifier');
            return $this->redirectToRoute('login');
        } else {
            return $this->render('utilisateur/resetPassword.html.twig', ['token' => $token]);
        }
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