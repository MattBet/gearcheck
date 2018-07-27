<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/stripe", name="checkout")
 */
class CheckoutController extends Controller
{
    /**
     * @Route("/prepare", name="stripe_prepare")
     */
    public function prepareAction()
    {
        return $this->render('stripe/prepare.html.twig');
    }

    /**
     * @Route("/checkout", name="stripe_checkout")
     */
    public function checkoutAction(Request $request)
    {
        \Stripe\Stripe::setApiKey("sk_test_HiUCqL5zNE8MXwhfkCMFMPiH");

        // Get the credit card details submitted by the form
        $token = $_POST['stripeToken'];

        // Create a charge: this will charge the user's card
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => 1000, // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => "Paiement Stripe - OpenClassrooms Exemple"
            ));
            $this->addFlash("success","Bravo ça marche !");
            return $this->redirectToRoute("stripe_prepare");
        } catch(\Stripe\Error\Card $e) {

            $this->addFlash("error","Snif ça marche pas :(");
            return $this->redirectToRoute("stripe_prepare   ");
            // The card has been declined
        }
    }
}
