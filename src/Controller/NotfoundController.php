<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotfoundController extends AbstractController
{
    /**
     * @Route("/notfound", name="notfound")
     */
    public function index(): Response
    {
        return $this->render('notfound/index.html.twig', [
            'controller_name' => 'NotfoundController',
        ]);
    }
}
