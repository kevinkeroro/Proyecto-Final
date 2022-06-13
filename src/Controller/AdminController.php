<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\OrderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $user = $this->getUser();
        if($user !== null){
            if($user->getAdmin()==1){
                return $this->render('admin/index.html.twig');
            }
            else{
                return $this->redirectToRoute('app_home');
            }
        }else{
            return $this->redirectToRoute('app_home');
        }

    }

    #[Route('/admin/create', name: 'admin_create')]
    public function loadCreate(): Response{
        $user = $this->getUser();
        if($user !== null){
            if($user->getAdmin()==1){
                return $this->render('admin/create.html.twig');
            }
            else{
                return $this->redirectToRoute('app_home');
            }
        }else{
            return $this->redirectToRoute('app_home');
        }
    }

    #[Route('/admin/create/send', name: 'create_product')]
    public function createProduct(ManagerRegistry $doctrine): Response{
        $user = $this->getUser();
        if($user !== null){
            if($user->getAdmin()==1){
                if(isset($_POST['model']) && preg_match('/\d+/',$_POST['price']) && isset($_POST['brand']) && isset($_POST['description'])  && isset($_POST['banner']) && preg_match('/\d+/',$_POST['stock'])){
                    $entityManager = $doctrine->getManager();
                    $product = new Product;
                    $product->setModel($_POST['model']);
                    $product->setPrice($_POST['price']);
                    $product->setBrand($_POST['brand']);
                    $product->setDescription($_POST['description']);
                    $product->setBanner($_POST['banner']);
                    $product->setStock($_POST['stock']);
                    
                    $entityManager->persist($product);
                    $entityManager->flush();

                    return $this->redirectToRoute('app_admin');
                }else{
                    return $this->redirectToRoute('admin_create');
                }
                
            }
            else{
                return $this->redirectToRoute('app_home');
            }
        }else{
            return $this->redirectToRoute('app_home');
        }
    }

    #[Route('/admin/orders', name: 'admin_orders')]
    public function loadOrders(OrderRepository $orderRepository): Response{
        $user = $this->getUser();
        if($user !== null){
            if($user->getAdmin()==1){
                $orders = $orderRepository->findAll();
                return $this->render('admin/orders.html.twig',[
                    'orders'=>$orders
                ]);
            }
            else{
                return $this->redirectToRoute('app_home');
            }
        }else{
            return $this->redirectToRoute('app_home');
        }
    }

    #[Route('/admin/order/edit/{id}', name: 'admin_edit_order')]
    public function editOrder(OrderRepository $orderRepository,$id): Response{
        $user = $this->getUser();
        if($user !== null){
            if($user->getAdmin()==1){
                $order = $orderRepository->findOneBy(['id'=>$id]);
                return $this->render('admin/orderEdit.html.twig',[
                    'order'=>$order
                ]);
            }
            else{
                return $this->redirectToRoute('app_home');
            }
        }else{
            return $this->redirectToRoute('app_home');
        }
    }

    #[Route('/admin/order/edit/save/{id}', name: 'admin_edit_order_save')]
    public function saveOrder(OrderRepository $orderRepository,$id,ManagerRegistry $doctrine): Response{
        $user = $this->getUser();
        if($user !== null){
            if($user->getAdmin()==1){
                if(isset($_POST['address']) && isset($_POST['city']) && isset($_POST['status'])){
                    $order = $orderRepository->findOneBy(['id'=>$id]);
                    $entityManager =  $doctrine->getManager();
                    $order->setAddress($_POST['address']);
                    $order->setCity($_POST['city']);
                    $order->setStatus($_POST['status']);
                    $entityManager->persist($order);
                    $entityManager->flush();

                    return $this->redirectToRoute('admin_orders');
                }else{
                    return $this->redirectToRoute('admin_edit_order');
                }

            }
            else{
                return $this->redirectToRoute('app_home');
            }
        }else{
            return $this->redirectToRoute('app_home');
        }
    }

}
