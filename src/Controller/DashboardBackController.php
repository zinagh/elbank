<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UtilisateurRepository;

class DashboardBackController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard_back")
     */
    public function index(UtilisateurRepository $repository1): Response
    {

        return $this->render('dashboard_back/index.html.twig'
        );
    }
}

