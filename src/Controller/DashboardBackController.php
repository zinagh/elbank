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
        $Countutilisateur = $repository1->Countutilisateur();
        $Connecteutilisateur = $repository1->Connecteutilisateur();
        $data = $repository1->findAll();
        $da =[];
        $count = 0 ;
        $countEmp= 0;
        $countall = 0;
        foreach ($data as $d){
            $countall++;
            if( in_array('ROLE_USER', $d->getRoles())){
                array_push($da , $d);
                $count++ ;

            }
            if( in_array('ROLE_EMPLOYEE', $d->getRoles())){
                array_push($da , $d);
                $countEmp++ ;

            }

        }

        return $this->render('dashboard_back/index.html.twig'
            , array('countutilisateur' => $Countutilisateur ,
                'connecteutilisateur' => $Connecteutilisateur,
                'count' => $count , 'countEmp' => $countEmp , "countall"=>$countall));
    }


}
