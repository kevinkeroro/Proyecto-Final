<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/',name: 'app_home')]
    public function home(ProductRepository $productRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'products' => $productRepository->findAll(),
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
    // npm run watch      assets/app.js importar todos los archivos en este documento
}