<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use CalendarBundle\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

class UtilisateurApiController extends AbstractController
{
    /**
     * @Route("/user/add", name="ajouterUtilisateurMobile")
     */
    public function addUser(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {

        $em = $this->getDoctrine()->getManager();


        // $category_id = $request->get("category_id");


        $username = $request->get("username");
        $email = $request->get("email");
        $roles = $request->get("roles");
        $password = $request->get("password");
        $num_tel=$request->get("numTel");


        $date_naissance = new \DateTime('@' . strtotime('now'));

        // $category = $em->getRepository(Category::class)->find($category_id);

        $utilisateur = new Utilisateur();
        $utilisateur->setCinU(12345678);
        $utilisateur->setNomU("EL BANK");
        $utilisateur->setPrenomU($username);
        $utilisateur->setDateNaissance($date_naissance);
        $utilisateur->setEmailU($email);
        $utilisateur->setNumTel($num_tel);
        $utilisateur->setRole($roles);
        $utilisateur->setMotDePasse($passwordEncoder->encodePassword(
            $utilisateur,$password));

        $em->persist($utilisateur);
        $em->flush();



        //RESPONSE JSON FROM OUR SERVER
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($utilisateur);

        return new JsonResponse($formatted);
    }


    /**
     * @Route("/user/verif", name="app_logina")
     */
    public function signinAction(Request $request,UserPasswordEncoderInterface $passwordEncoder){

        $email_u = $request->get("email");

        $em= $this->getDoctrine()->getManager();
        $utilisateur= $em->getRepository(Utilisateur::class)->findByEmailU($email_u);// find user by email

        if($utilisateur){
            $mot_de_passe = $passwordEncoder->encodePassword($utilisateur,$request->get("password"));
            if($mot_de_passe== $utilisateur->getPassword()){

                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($utilisateur);
                $utilisateur = $this->getUser();
                return new JsonResponse($formatted, 200);

            }
            else{
                return new Response("failed");
            }

        }
        else{
            return new Response("failed2");
        }
    }


    /**
     * @Route("/user/edit", name="editUtilisateurMobile", methods={"POST"})
     */
    public function editUtilisateurMobile(Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy([
            'email_u' => $request->get('email')
        ]);


        // Update user properties
        $utilisateur->setEmailU($request->get("email"));
        $utilisateur->setMotDePasse($request->get("password"));
        $utilisateur->setRole($request->get("roles"));
        $utilisateur->setPrenomU($request->get("username"));
        $utilisateur->setNumTel($request->get("numTel"));
        // ...

        // Persist changes to database
        $entityManager->persist($utilisateur);
        $entityManager->flush();

        return new JsonResponse(['success' => 'Utilisateur mis à jour avec succès'], 200);
    }




    /**
     * @Route("/user", name="afficherUtilisateurMobile", methods={"GET"})
     */
    public function afficherUtilisateurMobile(): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        $query = $entityManager->createQueryBuilder()
            ->select('u.id, u.prenom_u, u.email_u, u.role, u.mot_de_passe, u.num_tel')
            ->from(Utilisateur::class, 'u')
            ->getQuery();

        $users = $query->getResult();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);

        $normalizedUsers = [];
        foreach ($users as $user) {
            $normalizedUsers[] = [
                'id' => $user['id'],
                'username' => $user['prenom_u'],
                'email' => $user['email_u'],
                'roles' => $user['role'],
                'password' => $user['mot_de_passe'],
                'numTel' => $user['num_tel']
            ];
        }

        $jsonData = $serializer->serialize(['root' => $normalizedUsers], 'json');

        return new JsonResponse($jsonData, 200, [], true);
    }


    /**
     * @Route("/user/delete", name="deleteutilisateur")
     */
    public function deleteutilisateur(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $entityManager->getRepository(Utilisateur::class);

        $email = $request->get("email");
        $user = $userRepository->findOneBy(['email_u' => $email]);

            $entityManager->remove($user);
            $entityManager->flush();
            return new JsonResponse("User deleted.");

    }


    /**
     * @Route("/upload", name="upload", methods={"POST"})
     */
    public function upload(Request $request): Response
    {
        $file = $request->files->get('file');
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        $file->move(
            $this->getParameter('FrontOffice/img'),
            $fileName
        );

        return new Response('File uploaded successfully');
    }


}
