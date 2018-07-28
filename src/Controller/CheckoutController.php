<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Shipping;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/payment")
 */
class CheckoutController extends Controller
{
    /**
     * @Route("/checkout", name="checkout")
     */
    public function checkoutAction(Request $request)
    {
        // get Entity Manager
        $em = $this->getDoctrine()->getManager();

        // get current User
        $token = $this->get('security.token_storage')->getToken();
        $user = $token->getUser();

        \Stripe\Stripe::setApiKey("sk_test_HiUCqL5zNE8MXwhfkCMFMPiH");

        // Get the credit card details submitted by the form
        $token = $_POST['stripeToken'];

        // Get user cart
        $user_cart = $this->getDoctrine()->getRepository(Cart::class)->findBy(['user' => $user]);

        // Get user cart total value
        $total_price = $user_cart[0]->getTotalPrice() * 100;

        // Create a charge: this will charge the user's card
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => $total_price, // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => "Stripe checkout"
            ));

            $old_cart = $user_cart;

            // Get user products into cart
            $ship = $this->getDoctrine()->getRepository(Shipping::class)->findBy(["cart" => $user_cart[0]->getId()]);

            $old_products = $ship;

            // delete each product
            foreach ($ship as $product) {
                $em->remove($product);
                $em->flush();
            }

            // Get user cart
            $cart = $this->getDoctrine()->getRepository(Cart::class)->find($user_cart[0]->getId());

            // delete user cart
            $em->remove($cart);
            $em->flush();

            // build success message
            $msg = "Thank you for your purchase " . $user->getUsername();

            // send mail



        } catch(\Stripe\Error\Card $e) {

            //build error message
            $msg  = "Your payment has been declined.";

            //send Mail
        }

        return $this->render('payment/checkout.html.twig',[
            'message' => $msg,
            'total_price' => $total_price,
            'old_cart' => $old_cart,
            'old_products' => $old_products
        ]);
    }
}
