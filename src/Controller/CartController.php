<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function cart(SessionInterface $session, ProductRepository $product): Response
    {
        $cart = $session->get('cart', []);

        $cartData = [];

        foreach($cart as $id => $quantity){
            $cartData[] = [
                'product' => $product->find($id),
                'quantity' => $quantity
            ];
        }

        $total = 0;
        foreach($cartData as $item){
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total+=$totalItem;
        }

        return $this->render('cart/index.html.twig', [
            'items' => $cartData,
            'total' => $total
        ]);
    }

    #[Route('/cart/add/{id}', name:'cart_add')]
    public function add($id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if(!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }
        
        $session->set('cart', $cart);
        return $this->redirectToRoute('app_product',[
            'id' => $id
        ]);
    }

    #[Route('/cart/delete/{id}', name:'cart_delete')]
    public function delete($id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        if(!empty($cart[$id])){
            if($cart[$id]!=1){
                $cart[$id]--;
            }else{
                unset($cart[$id]);
            }
            
        }

        $session->set('cart',$cart);

        return $this->redirectToRoute('app_cart');
    }
}