<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\Shipping;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PanierController extends Controller
{
    /**
     * @Route("/panier", name="panier")
     */
    public function showAction()
    {
        //get current User
        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $state = $auth_checker->isGranted("IS_AUTHENTICATED_FULLY");
        $user = $token->getUser();

        //Check if an user is logged in
        if(!$user)
        {
            return $this->redirectToRoute('home');
        }


        $user_cart = $this->getDoctrine()->getRepository(Cart::class)->findBy(['user' => $user]);

        if($user_cart)
        {
            $user_products = $this->getDoctrine()->getRepository(Shipping::class)->findBy(['cart' => $user_cart[0]->getId()]);

            return $this->render('panier/index.html.twig', array(
                'user_products' => $user_products,
                'cart_data' => $user_cart[0],
            ));
        }



        return $this->render('panier/index.html.twig', array(
            'auth' => $auth_checker,
            'user' => $user,
            'user_cart' => $user_cart,
            'state' => $state
        ));
    }

    /**
     * @Route("/panier/add/{id}", name="add_to_cart")
     */
    public function addAction(Request $request, $id)
    {
        //Get user state
        $auth_checker = $this->get('security.authorization_checker');
        $user_logged = $auth_checker->isGranted("IS_AUTHENTICATED_FULLY");

        if ($user_logged)
        {
            $em = $this->getDoctrine()->getManager();

            //Get user
            $token = $this->get('security.token_storage')->getToken();
            $user = $token->getUser();

            //Get product id
            $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

            //Check if user has cart
            $exist_cart = $this->getDoctrine()->getRepository(Cart::class)->findBy(['user' => $user]);

            if (!$exist_cart)
            {
                $cart = new Cart();

                $cart->setUser($user);

                $cart->setTotalPrice($product->getPrice());

                $em->persist($cart);

                $em->flush();

                $ship = new Shipping();

                $ship->setQuantity(1);

                $ship->setProduct($product);

                $ship->setCart($cart);

                $em->persist($ship);

                $em->flush();
            }
            else
            {
                $cart = $exist_cart[0];

                $cart->setTotalPrice($cart->getTotalPrice() + $product->getPrice());

                $em->persist($cart);

                $em->flush();


                $ship = new Shipping();

                $ship->setQuantity(1);

                $ship->setProduct($product);

                $ship->setCart($cart);

                $em->persist($ship);

                $em->flush();
            }

            return $this->redirectToRoute('panier');
        }

        return $this->redirectToRoute('home');
    }
}
