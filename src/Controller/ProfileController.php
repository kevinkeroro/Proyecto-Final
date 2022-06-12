<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(UserRepository $usuario,OrderRepository $order): Response
    {
        $user = $this->getUser();
        if($user===null){
            return $this->redirectToRoute('app_home');
        }else{
            $usu = $usuario->findOneBy(['email'=>$user->getUserIdentifier()]);
            $orders = $order->findBy(['user_id'=>$usu->getId()]);
            return $this->render('profile/index.html.twig',[
                'orders'=>$orders
            ]);
        }
    }

    #[Route('/profile/order/{id}', name: 'app_order')]
    public function loadOrder(OrderRepository $orderRepository,$id,ProductRepository $productRepository): Response
    {
        $user = $this->getUser();
        if($user===null){
            return $this->redirectToRoute('app_home');
        }else{
            $order = $orderRepository->findOneBy(['id' => $id]);
            $order_products=[];
            $products = $order->getProduct();
            foreach($products as $product){
                $prod = $productRepository->findOneBy(['id'=>$product['product']]);
                array_push($order_products,array('model'=>$prod->getModel(),'quantity'=>$product['quantity']));
            }
            return $this->render('profile/order.html.twig',[
                'products'=>$order_products
            ]);
        }
    }

    #[Route('/profile/update', name: 'app_profile_update')]
    public function updateProfile(ManagerRegistry $doctrine,UserRepository $usuario): Response
    {
        $user = $this->getUser();
        if($user===null && empty($_POST)){
            return $this->redirectToRoute('app_home');
        }else{
            $entityManager = $doctrine->getManager();
            $usu = $usuario->findOneBy(['email'=>$user->getUserIdentifier()]);
            if(!empty($_POST['firstName'])){
                $usu->setFirstName($_POST['firstName']);
            }
            if(!empty($_POST['lastName'])){
                $usu->setLastName($_POST['lastName']);
            }
            if(!empty($_POST['address'])){
                $usu->setAddress($_POST['address']);
            }
            if(!empty($_POST['city'])){
                $usu->setCity($_POST['city']);
            }
            if(preg_match('/\d{5,9}/',$_POST['phone'])){
                $usu->setPhone($_POST['phone']);
            }
            $entityManager->persist($usu);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }
    }

}
