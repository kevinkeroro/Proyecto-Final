<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/')]
    public function home(): Response
    {
        return $this->render('main/home.html.twig',[
            'name' => 'Phoner',
        ]);
    }

    #[Route('/search/{search}')]
    public function search(string $search = null): Response
    {
        if($search){
            $search='Search : '.$search;
        }else{
            $search='Search Something';
        }
        return new Response($search);
    }
}