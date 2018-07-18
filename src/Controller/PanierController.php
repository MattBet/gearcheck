<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PanierController extends Controller
{
    /**
     * @Route("/panier", name="panier")
     */
    public function showAction()
    {
        $em = $this->getDoctrine()->getManager();

        //get current User
        $user = $this->getUser();

        //Check if an user is logged in
        if(!$user)
        {
            return $this->redirectToRoute('home');
        }


        return $this->render('panier/index.html.twig', array(
            'user' => $user
        ));
    }
}
