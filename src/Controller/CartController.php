<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

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

    #[Route('/cart/add/{id}/{quantity}', name:'cart_add')]
    public function add($id, SessionInterface $session, $quantity): Response
    {
        $cart = $session->get('cart', []);

        if(!empty($cart[$id])){
            if($quantity == 1){
                $cart[$id]++;
            }else{
                $cart[$id]+=(int)$quantity;
            }
            
        }else{
            $cart[$id] = $quantity;
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

    #[Route('/cart/delete_all', name:'cart_delete_all')]
    public function deleteAll(SessionInterface $session): Response
    {
        $cart = $session->get('cart',[]);
        unset($cart);
        $session->set('cart',[]);
        return $this->redirectToRoute('app_cart');
    }

     #[Route('/cart/checkout', name:'cart_checkout')]
     public function checkout(SessionInterface $session,ProductRepository $product): Response
     {
         $usuario=$this->getUser();
         $cart = $session->get('cart',[]);
         if(empty($cart)){
            return $this->redirectToRoute('app_cart');
         }else{
             if($usuario===null){
                return $this->redirectToRoute('app_login');
             }else{
                 
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

                return $this->render('cart/checkout.html.twig',[
                    'total' => $total
                ]);
                
             }
         }
     }

     #[Route('/cart/pay', name:'cart_pay')]
     public function pay(ManagerRegistry $doctrine,SessionInterface $session,ProductRepository $product,UserRepository $user)
     {
        $usuario=$this->getUser();
        if($usuario===null){
            return $this->redirectToRoute('app_home');
         }
        if(!empty($_POST['nameOnCard']) && preg_match('/\d{16}/',$_POST['creditCardNumber'])  && preg_match('/\d{2}\/\d{2}/',$_POST['expiryDate']) && preg_match('/\d{3}/',$_POST['securityCode']) ){
            $entityManager = $doctrine->getManager();
            $cart = $session->get('cart',[]);
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
                $item['product']->setStock($item['product']->getStock() - $item['quantity']);
                $total+=$totalItem;
                $entityManager->persist($item['product']);
                $entityManager->flush();
            }

            foreach($cart as $id => $quantity){
                $order_products[] = [
                    'product' => $id,
                    'quantity' => $quantity
                ];
            }
            $info_usuario=$user->findOneBy(['email'=>$usuario->getUserIdentifier()]);
            // $date=new \DateTimeInterface();
            $date = new DateTime();
            $order_date = $date->format('Y-m-d');
            // $entityManager = $doctrine->getManager();
            $order = new Order();
            $order->setStatus("realizado");
            $order->setProduct($order_products);
            $order->setTotalPrice($total);
            $order->setUserId($info_usuario->getId());
            $order->setAddress($info_usuario->getAddress());
            $order->setCity($info_usuario->getCity());
            $order->setOrderDate($order_date);

            $entityManager->persist($order);

            $entityManager->flush();

            

            unset($cart);
            $session->set('cart',[]);

            return $this->redirectToRoute('app_home');
        }else{
            return $this->redirectToRoute('cart_pay');
        }
     }
}