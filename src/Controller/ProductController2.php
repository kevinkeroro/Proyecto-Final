<?php
namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('api/product/{id<\d+>}',name: 'app_getproduct', methods: ['GET'])]
    public function getProduct(int $id, LoggerInterface $logger): Response
    {
        
        
        $product=[
            'id' => $id,
            'brand' => 'apple',
            'name' => 'iphone 13'
        ];

        $logger->info("RETURNING PRODUCT {product} WITH ID {id}",[
            'product' => $product['name'],
            'id' => $id
        ]);

        $this->addFlash(
            'login',
            'User logged'
        );

        return $this->redirectToRoute('app_home',$product);
    }
}