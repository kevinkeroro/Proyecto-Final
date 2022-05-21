<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController
{
    #[Route('/')]
    public function homePage(): Response
    {
        return new Response('Title ');
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