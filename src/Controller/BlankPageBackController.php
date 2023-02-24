<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlankPageBackController extends AbstractController
{
    /**
     * @Route("/blankpageback", name="blank_page_back")
     */
    public function index(): Response
    {
        return $this->render('blank_page_back/index.html.twig', [
            'controller_name' => 'BlankPageBackController',
        ]);
    }
}
