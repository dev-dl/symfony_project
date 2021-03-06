<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class IndexController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function home(): Response
    {        
               
        return $this->render('index/index.html.twig', [
                'controller_name' => 'IndexController',]);

    }

    /**
    * @Route("/", name="index")
     */
    public function index(): Response
    {                        
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); 
        $user = $this->getUser();
        $developer = $user->getDeveloper();
        return $this->render('index/index_authenticated.html.twig', [
            'controller_name' => 'IndexController',
            'email' => $user->getUsername(),
            'developer' => $developer
        ]);     
           
    }


}
