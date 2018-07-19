<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\Review;
use App\Entity\Shipping;
use App\Entity\User;
use App\Form\ProductType;
use App\Form\ReviewType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{
    /**
     * @Route("/", name="product_index", methods="GET")
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('image_dir'),$fileName
            );

            $product->setImage($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show")
     */
    public function show($id, Request $request): Response
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        //get current User
        $token = $this->get('security.token_storage')->getToken();
        $user = $token->getUser();

        $user_cart = $this->getDoctrine()->getRepository(Cart::class)->findBy(['user' => $user]);

        $user_products = null;
        if($user_cart) {
            $user_products = $this->getDoctrine()->getRepository(Shipping::class)->findBy(['cart' => $user_cart[0]->getId()]);
        }


        $review = new Review();
        $formReview = $this->createForm(ReviewType::class, $review);
        $formReview->handleRequest($request);
        if ($formReview->isSubmitted() && $formReview->isValid())
        {
            $review->setCreatedAt(new \DateTime());
            $review->setUser($this->getUser());
            $review->setProductId($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            return $this->redirect($this->generateUrl('product_show', array('id' => $product->getId())), 301);
        }
        return $this->render('product/show.html.twig', ['product' => $product,
                                                                'formReview' => $formReview->createView(),
                                                                'user_products' => $user_products]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods="GET|POST")
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_edit', ['id' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods="DELETE")
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/{product_id}/vote/{review_id}", name="vote_review", methods={"POST"})
     */
    public function voteAction($product_id, $review_id)
    {
        $review = $this->getDoctrine()->getRepository(Review::class)->findOneBy(['product' => $product_id, 'id' => $review_id]);

        return $this->json(['vote' => $review->getVote()]);
    }
}
