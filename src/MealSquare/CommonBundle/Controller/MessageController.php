<?php

namespace MealSquare\CommonBundle\Controller;

use MealSquare\CommonBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller {

    public function indexAction(\Symfony\Component\HttpFoundation\Request $request) {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("ApplicationSonataUserBundle:User");
        $messageRepository = $em->getRepository("MealSquareCommonBundle:Message");

        $user = $this->get('security.context')->getToken()->getUser();
        if( !$request->isXmlHttpRequest() ) {
            $users = $userRepository->createQueryBuilder('u')
                ->where('u.enabled = true')
                ->andWhere('u != :user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getResult();    

            $messages = $messageRepository->createQueryBuilder('m')
                ->where('m.relatedUser = :user')
                ->setParameter('user', $user)
                ->orderBy('m.date', 'DESC')
                ->getQuery()
                ->getResult(); 

            $messagesSent = $messageRepository->createQueryBuilder('m')
                ->where('m.user = :user')
                ->setParameter('user', $user)
                ->orderBy('m.date', 'DESC')
                ->getQuery()
                ->getResult();

            return $this->render('MealSquareCommonBundle:Message:layout.html.twig',array(
                'users' => $users,
                'messages' => $messages,
                'messagesSent' => $messagesSent
            ));
        } else {
        	$relatedUsers = $_POST['relatedUsers'];
        	$subject     = $_POST['subject'];
            $content     = $_POST['content'];

            $messagesToArray = array();

            foreach ($relatedUsers as $idRelatedUser){
                $relatedUser = $userRepository->findOneById(intval($idRelatedUser));
                $message = new Message($user, $relatedUser, $subject, $content);
                $em->persist($message);
                $em->flush();
                array_push($messagesToArray, array(
                    "id"            => $message->getId(),
                    "relatedUser"   => $relatedUser->getUsername(),
                ));
            }
            array_push($messagesToArray, array(
                "subject"           => $message->getSubject(),
                "content"           => $message->getContent(),
                "date"              => date_format($message->getDate(), 'm/d/Y'),
            ));      

            $response = new Response();
            $response->setContent(json_encode($messagesToArray));
            $response->headers->set('Content-Type', 'application/json');
            return $response;

            // echo '<div class="info_message">Votre message a bien été envoyé</div>';
            // exit();
        }
    }

    public function updateReceiverStateAction() {
        $em = $this->getDoctrine()->getManager();
        $id = $this->container->get('request')->get('id');
        $receiverState = $this->container->get('request')->get('receiverState');
        
        $message = $em->getRepository("MealSquareCommonBundle:Message")->findOneById(intval($id));
        $message->setReceiverState($receiverState);
        $em->flush();

        $response = new Response();
        $response->setContent(json_encode(array("success"=>true)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function updateSenderStateAction() {
        $em = $this->getDoctrine()->getManager();
        $id = $this->container->get('request')->get('id');
        $senderState = $this->container->get('request')->get('senderState');
        
        $message = $em->getRepository("MealSquareCommonBundle:Message")->findOneById(intval($id));
        $message->setSenderState($senderState);
        $em->flush();

        $response = new Response();
        $response->setContent(json_encode(array("success"=>true)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
}
