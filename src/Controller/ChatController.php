<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Form\ChatType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ChatController extends Controller
{
    /**
     * @Route("/chat", name="chat")
     */
    public function index(Request $request)
    {
        //get User
        //get current User
        $token = $this->get('security.token_storage')->getToken();
        $user = $token->getUser();

        //create chat object
        $message = new Chat();
        //get Form
        $form = $this->createForm(ChatType::class, $message, array('action' => '/chat'));
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $message->setUser($user);

            $em = $this->getDoctrine()->getManager();

            $em->persist($message);
            $em->flush();

            return new JsonResponse(array('user'=>$message->getUser()->getUsername(),'message'=>$message->getMessage()));
        }

        return $this->render('chat/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/chat/allMessages", name="all_msg")
     */
    public function getAllMessages()
    {
        $chat = $this->getDoctrine()->getRepository(Chat::class)->findBy(array(),array('posted_at' => 'ASC'));

        return $this->render('chat/messages.html.twig', array('messages' => $chat));
    }
}
