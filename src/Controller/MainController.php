<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/',name: 'app_home')]
    public function home(): Response
    {
        return $this->render('main/home.html.twig',[
            'name' => 'Phoner',
        ]);
    }

    /*
    #[Route('/',name: 'app_home')]
    public function home(Environment $twig): Response
    {
        $html = $twig->render('main/home.html.twig',[
            'name' => 'Phoner',
        ]);

        return new Response($html);
    }
    */

    #[Route('/search/{search}',name: 'app_search')]
    public function search(string $search = null): Response
    {
        if($search){
            $search='Search : '.$search;
        }else{
            $search='Search Something';
        }
        return new Response($search);
    }

    // return JsonResponse($a) || $this->json()
    // debug:autowiring para revisar los servicios
}