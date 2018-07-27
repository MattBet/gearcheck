<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Review;
use App\Form\ReviewType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReviewController extends Controller
{
    public function newAction($product_id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($product_id);

        if (!$product) {
            throw $this->createNotFoundException('Unable to find Products.');
        }

        $review = new Review();
        $formReview = $this->createForm(ReviewType::class, $review);
        $review->setProduct($product);

        return $this->render("product/review/form.html.twig", ['formReview' => $formReview->createView(), 'review' => $review]);
    }

    /**
     * @Route("/review/create/{product_id}", name="create_review")
     */
    public function createAction($product_id, Request $request)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($product_id);


        if (!$product) {
            throw $this->createNotFoundException('Unable to find Products.');
        }

        //get current User
        $token = $this->get('security.token_storage')->getToken();
        $user = $token->getUser();

        $review  = new Review();
        $review->setProduct($product);
        $formReview = $this->createForm(ReviewType::class, $review);
        $formReview->handleRequest($request);

        if ($formReview->isSubmitted() && $formReview->isValid()) {
            $review->setCreatedAt(new \DateTime());
            $review->setUser($user);
            $review->setProduct($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            return $this->redirect($this->generateUrl('product_show', array(
                'id' => $review->getProduct()->getId()))
            );
        }

        return $this->render('product/review/create.html.twig', array(
            'review' => $review,
            'formReview'    => $formReview->createView()
        ));
    }
}
