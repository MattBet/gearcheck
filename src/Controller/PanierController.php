<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\Shipping;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
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
        $token = $this->get('security.token_storage')->getToken();
        $user = $token->getUser();
        //Get user state
        $auth_checker = $this->get('security.authorization_checker');
        $user_logged = $auth_checker->isGranted("IS_AUTHENTICATED_FULLY");

        //Check if an user is logged in
        if(!$user_logged)
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



        return $this->render('panier/index.html.twig');
    }

    /**
     * @Route("/panier/add/{id}", name="add_to_cart")
     */
    public function addAction(Request $request, $id)
    {
        //Get user state
        $auth_checker = $this->get('security.authorization_checker');
        $user_logged = $auth_checker->isGranted("IS_AUTHENTICATED_FULLY");

            if ($user_logged) {
                //Get entity manager
                $em = $this->getDoctrine()->getManager();
                //Get user
                $token = $this->get('security.token_storage')->getToken();
                $user = $token->getUser();
                //Get product
                $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
                //Get user cart
                $exist_cart = $this->getDoctrine()->getRepository(Cart::class)->findBy(['user' => $user]);

                if (!$exist_cart) {
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
                } else {
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

                return new JsonResponse(['message' => 'success',
                    'entity' => $product]);
            }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/panier/delete/{product_id}/{cart_id}", name="cart_delete")
     */
    public function deleteAction(Request $request, $product_id, $cart_id)
    {
        if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $repo = $this->getDoctrine()->getRepository(Shipping::class);

            $ship = $repo->findOneBy(['product' => $product_id, 'cart' => $cart_id]);

            $new_price = $ship->getCart()->getTotalPrice() - ($ship->getProduct()->getPrice() * $ship->getQuantity());


            $ship->getCart()->setTotalPrice($new_price);

            $em->remove($ship);

            $em->flush();

            if ($ship->getCart()->getTotalPrice() == 0) {
                $cart = $this->getDoctrine()->getRepository(Cart::class)->find($cart_id);

                $em->remove($cart);
                $em->flush();
            }
        }

        return $this->redirectToRoute('panier');
    }

     /*
     * @Route("/panier/clear/{cart_id}", name="cart_clear")

    public function clearAction(Request $request, $cart_id){

        if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            $repo = $this->getDoctrine()->getRepository(Shipping::class);

            $ship = $repo->findBy(["cart" => $cart_id]);

            foreach ($ship as $product) {
                $em->remove($product);
                $em->flush();
            }

            $cart_repo = $this->getDoctrine()->getRepository(Cart::class);
            $cart = $cart_repo->find($cart_id);

            $em->remove($cart);
            $em->flush();

            return new JsonResponse(['message' => 'success', 'cart' => $cart]);
        }

        return $this->redirectToRoute('panier');
    }*/
}
