<?php

namespace MealSquare\CommonBundle\Controller;

use MealSquare\CommonBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends Controller {

    public function indexAction(\Symfony\Component\HttpFoundation\Request $request) {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("ApplicationSonataUserBundle:User");

        $user = $this->get('security.context')->getToken()->getUser();
        if( !$request->isXmlHttpRequest() ) {
            $users = $userRepository->createQueryBuilder('u')
                ->where('u.enabled = true')
                ->andWhere('u != :user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getResult();    

            return $this->render('MealSquareCommonBundle:Message:layout.html.twig',array(
                'users' => $users
            ));
        } else {
        	$relatedUsers = $_POST['relatedUsers'];
        	$subject     = $_POST['subject'];
            $content     = $_POST['content'];

            foreach ($relatedUsers as $idRelatedUser){
                $relatedUser = $userRepository->findOneById(intval($idRelatedUser));
                $message = new Message($user, $relatedUser, $subject, $content);
                $em->persist($message);
                $em->flush();
            }

            echo '<div class="info_message">Votre message a bien été envoyé</div>';
            exit();
        }
    }
    
}
