<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotfoundBackController extends AbstractController
{
    /**
     * @Route("/notfoundback", name="notfound_back")
     */
    public function index(): Response
    {
        return $this->render('notfound_back/index.html.twig', [
            'controller_name' => 'NotfoundBackController',
        ]);
    }
}
