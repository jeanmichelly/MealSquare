<?php

namespace MealSquare\RecetteBundle\Controller;

use MealSquare\RecetteBundle\Entity\Recette;
use MealSquare\RecetteBundle\Entity\GroupVersions;
use MealSquare\RecetteBundle\Entity\GroupVariantes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use MealSquare\RecetteBundle\Form\RecetteType;
use MealSquare\RecetteBundle\Form\RecetteEditType;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\UserBundle\Model\UserInterface;

class RecetteController extends Controller {

    public function listAction(Request $request) {
        $em         = $this->get('doctrine.orm.entity_manager');
        $dql        = "SELECT a FROM MealSquareRecetteBundle:Recette a WHERE a.visibilite = true AND a.archive =false ORDER BY a.dateCreation DESC";
        $query      = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->get('page', 1)/* page number */, 20/* limit per page */
        );
        $raccourcis = $this->getDoctrine()->getRepository("MealSquareRecetteBundle:Raccourci")->findBy(array('actif'=>true));

        // parameters to template
        return $this->render('MealSquareRecetteBundle:Recette:list.html.twig', array(
            'pagination' => $pagination,
            'raccourcis' => $raccourcis
        ));
    }
    
    public function raccourciAction($id, $slug) {
        $raccourci     = $this->getDoctrine()->getRepository("MealSquareRecetteBundle:Raccourci")->find($id);
        if(is_null($raccourci)){
            throw new NotFoundHttpException("Désolé, la page que vous avez demandée semble introuvable !");
        }else{
            $recettes       = $this->getDoctrine()->getRepository("MealSquareRecetteBundle:Recette")->findRecipesByRaccourci($raccourci->getData());
            $raccourcis     = $this->getDoctrine()->getRepository("MealSquareRecetteBundle:Raccourci")->findBy(array('actif'=>true));
            $paginator      = $this->get('knp_paginator');
            $pagination     = $paginator->paginate($recettes, $this->get('request')->query->get('page', 1), 20);

            // parameters to template
            return $this->render('MealSquareRecetteBundle:Recette:list.html.twig', array(
                'pagination' => $pagination,
                'current_raccourci'=>$raccourci,
                'raccourcis'=>$raccourcis
            ));
        }
    }

    public function showAction($id) {
        $repository     = $this->getDoctrine()->getRepository("MealSquareRecetteBundle:Recette");
        $likeRepository = $this->getDoctrine()->getRepository('MealSquareRecetteBundle:Like\Like');
        $rateRepository = $this->getDoctrine()->getRepository('MealSquareRecetteBundle:Note\Rate');
        $groupVersionsRepository = $this->getDoctrine()->getRepository("MealSquareRecetteBundle:GroupVersions");

        $recette        = $repository->findOneById($id);
        $user           = $this->get('security.context')->getToken()->getUser();
        
        $recetteisVisible   = (!is_null($recette) && !$recette->getArchive() && $recette->getVisibilite())? true :false;
        $userIsOwner        = (!is_null($user) && $user instanceof UserInterface && !is_null($recette->getAuteur()) && $user->getId()==$recette->getAuteur()->getId());
        
        if(
            is_null($recette) || 
            (!$recetteisVisible && !$userIsOwner)
        ){
                throw new NotFoundHttpException("Désolé, la page que vous avez demandée semble introuvable !");
        }else{
            
            $likers     = $likeRepository->findBy(
                                                array('thread' => $recette->getLike()),
                                                array('id' => 'desc'),
                                                9,
                                                0
                        );
            // On vérifie si le user a déjà liker la recette
            $isLiker    = (!is_null($likeRepository->findOneBy(array('thread' => $recette->getLike(), 'liker'=>  $user))))?true:false;
            // On vérifie si le user a déjà cette recette en favoris
            $isFavoris  = ($user instanceof \Application\Sonata\UserBundle\Entity\User && $user->getRecettesFavoris()->contains($recette));
            // On vérifie si le user a déjà noter la recette
            $isRater    = (!is_null($rateRepository->findOneBy(array('thread' => $recette->getLike(), 'rater'=>  $user))))?true:false;

            if ( $recette->hasVersion() ) {
                $versions = $recette->getVersions();
                $groupVersions = $groupVersionsRepository->findOneById($versions[0]->getId());
                $isRecetteMere = (!is_null($groupVersions->getRecetteMere()) && $groupVersions->getRecetteMere()->getId() == $recette->getId()) ? true:false;
            } else {
                $isRecetteMere = null;
            }
            
            return $this->render('MealSquareRecetteBundle:Recette:show.html.twig', array(
                'recette'           => $recette,
                'isLiker'           => $isLiker,
                'likers'            => $likers,
                'isFavoris'         => $isFavoris,
                'isRater'           => $isRater,
                'hasVersion'        => $recette->hasVersion(),
                'isRecetteMere'     => $isRecetteMere,
                'recetteisVisible'  => $recetteisVisible
            ));
        }
        
    }
    
    public function printAction($id) {
        $repository     = $this->getDoctrine()->getRepository("MealSquareRecetteBundle:Recette");
        $recette        = $repository->findOneById($id);
        
        if(is_null($recette)){
                throw new NotFoundHttpException("Désolé, la page que vous avez demandée semble introuvable !");
        }else{
            
            return $this->render('MealSquareRecetteBundle:Recette:print.html.twig', array(
                'recette' => $recette
            ));
        }
        
    }
    
    public function deleteFavorisAction($id) {
        
        $em = $this->getDoctrine()->getManager();
        
        $repository = $em->getRepository("MealSquareRecetteBundle:Recette");
        $recette = $repository->findOneById($id);
        
        $user= $this->get('security.context')->getToken()->getUser();
        $user->removeRecettesFavori($recette);
        
        $em->persist($user);
        $em->flush();

        return $this->redirect( $this->generateUrl('fos_user_profile_show') );
    }
    
    public function addFavorisAction($id) {
        
        $em = $this->getDoctrine()->getManager();
        
        $repository = $em->getRepository("MealSquareRecetteBundle:Recette");
        $recette = $repository->findOneById($id);
        
        $user= $this->get('security.context')->getToken()->getUser();
        $user->addRecettesFavori($recette);
        
        $em->persist($user);
        $em->flush();

        return $this->redirect( $this->generateUrl( 'meal_square_recette_show', array('id' => $recette->getId()) ));
    }
    
    public function deleteAction($id) {
        
        $em = $this->getDoctrine()->getManager();
        
        $repository = $em->getRepository("MealSquareRecetteBundle:Recette");
        $recette = $repository->findOneById($id);
        
        $user= $this->get('security.context')->getToken()->getUser();
        
        if ( is_null($recette) || $user->getId()!==$recette->getAuteur()->getId() ) {
                throw new NotFoundHttpException("Désolé, la page que vous avez demandée semble introuvable !");
        } else {

            if ( $recette->hasVersion() && $recette->isRecetteMere() ) {
                $versions = $recette->getVersions();
                $groupVersionsRepository = $em->getRepository("MealSquareRecetteBundle:GroupVersions");
                $groupVersions = $groupVersionsRepository->findOneById($versions[0]->getId());

                $groupVersions->setRecetteMere();
                $em->persist($groupVersions);
            }
        
            $em->remove($recette);
            $em->flush();

            return $this->redirect( $this->generateUrl('fos_user_profile_show') );
        }
        
    }

    public function versionsDeleteAction() {
        $em                 = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("MealSquareRecetteBundle:Recette");
        $idVersionsToRemove = split(',', $this->container->get('request')->get('idVersionsToRemove'));
        $currentRecetteToRemove = $this->container->get('request')->get('currentRecetteToRemove');

        foreach ( $idVersionsToRemove as $idVersionToRemove ) {
            $recette = $repository->findOneById(intval($idVersionToRemove));
            
            if ( $recette->isRecetteMere() ) {
                $versions = $recette->getVersions();
                $groupVersionsRepository = $em->getRepository("MealSquareRecetteBundle:GroupVersions");
                $groupVersions = $groupVersionsRepository->findOneById($versions[0]->getId());

                $groupVersions->setRecetteMere();
                $em->persist($groupVersions);
            }
            $em->remove($recette);
        }
        $em->flush();

        $response = new Response();
        $response->setContent(json_encode(array("success"=>true, "currentRecetteToRemove"=>$currentRecetteToRemove)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }
    
    public function editAction($id) {
        
        $em = $this->getDoctrine()->getManager();
        
        $usr= $this->get('security.context')->getToken()->getUser();

        $repository = $em->getRepository("MealSquareRecetteBundle:Recette");
        $recette = $repository->findOneById($id);
        
        if(is_null($recette) || $usr->getId() !== $recette->getAuteur()->getId()){
                throw new NotFoundHttpException("Désolé, la page que vous avez demandée semble introuvable !");
        }else{
            
            $recette->getSpecialiteSaisonDifficulteIndex();
            $form = $this->createForm(new RecetteEditType(), $recette);

            if ( $recette->hasVersion() ) {
                $isVersion = true;
                $isRecetteMere = $recette->isRecetteMere();
            } else {
                $isVersion = false;
                $isRecetteMere = null;
            }

            $form->handleRequest($this->getRequest());

            if ($form->isValid()) {
                $recette = $form->getData();

                // On recupere la saison, la difficulté et la specialité
                $recette->setDifficulte($this->manageAttribut('difficulte',$recette->getDifficulte()));
                $recette->setSaison($this->manageAttribut('saison',$recette->getSaison()));
                $recette->setSpecialite($this->manageAttribut('specialite',$recette->getSpecialite()));
            
                $em->persist($recette);
                $em->flush();  

 
                return $this->redirect( $this->generateUrl( 'meal_square_recette_show', array('id' => $recette->getId()) ));
            }

            return $this->render('MealSquareRecetteBundle:Recette:add.html.twig', array(
                    'form'          => $form->createView(), 
                    'edit'          => true,
                    'isVersion'     => $isVersion,
                    'isRecetteMere' => $isRecetteMere
                )
            );
        }
        
    }
    
    public function addAction() {
        
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new RecetteType(), new Recette());

        $form->handleRequest($this->getRequest());
        
        
        if ($form->isValid()) {
            $recette = $form->getData();
            // On recupere la saison, la difficulté et la specialité
            $recette->setDifficulte($this->manageAttribut('difficulte',$recette->getDifficulte()));
            $recette->setSaison($this->manageAttribut('saison',$recette->getSaison()));
            $recette->setSpecialite($this->manageAttribut('specialite',$recette->getSpecialite()));
            // On recupere le current user
            $usr     = $this->get('security.context')->getToken()->getUser();
            $recette->setAuteur($usr);
            
            $em->persist($recette);
            $em->flush();  
            
            $recette->createThread();
            
            $em->persist($recette);
            $em->flush();

            
            if(!$recette->getArchive())
                return $this->redirect( $this->generateUrl( 'meal_square_recette_show', array('id' => $recette->getId()) ));
            else
                return $this->redirect( $this->generateUrl('fos_user_profile_show'));

        }

        return $this->render('MealSquareRecetteBundle:Recette:add.html.twig', array('form' => $form->createView()));
    }
    
    public function manageAttribut  ($type, $position ){
        $tabs = array();
        $tabs['difficulte'] = array(
                                '0' => 'Très facile',
                                '1' => 'Facile',
                                '2' => 'Moyen',
                                '3' => 'Difficile',
                                '4' => 'Délicat'
                    
                        );
        $tabs['saison'] = array(
                            '0' => 'Été',
                            '1' => 'Printemps',
                            '2' => 'Automne',
                            '3' => 'Hiver'

                        );
        
        $tabs['specialite'] = array('0' => 'Saint-Valentin' , '1' => 'Recettes anglo-saxonne' , '2' => 'Chic et facile' , '3' => 'Recettes méditerranéennes' , '4' => 'Spécialités antillaises' , '5' => 'Exotique' , '6' => 'Recettes de Chef' , '7' => 'Pâques' , '8' => 'Provence' , '9' => 'Orientale' , '10' => 'Repas de fête' , '11' => 'Cuisine légère' , '12' => 'Cuisine rapide' , '13' => 'Mardi Gras' , '14' => 'Asie' , '15' => 'Nordique' , '16' => 'Bretagne' , '17' => 'Sud-ouest' , '18' => 'Spécialités ibériques' , '19' => 'Normandie' , '20' => 'Thanksgiving' , '21' => 'Auvergne' , '22' => 'Halloween' , '23' => 'Recettes américaines' , '24' => 'Pentecôte');

        $tab = $tabs[$type];
        
        return (isset($tab[$position]))? $tab[$position]: $position;
    }
    
    public function cloneAction($id) {
        
        $em         = $this->getDoctrine()->getManager();
        $usr        = $this->get('security.context')->getToken()->getUser();
        $repository = $em->getRepository("MealSquareRecetteBundle:Recette");
        $recette    = $repository->findOneById($id);
        
        if(is_null($recette)){
                throw new NotFoundHttpException("Désolé, la page que vous avez demandée semble introuvable !");
        }else{
            
            $isVersion  = (!is_null($recette->getAuteur()) && $usr->getId() == $recette->getAuteur()->getId());
            $clone      = $recette->copy();
            
            if($isVersion) $clone->setImage($recette->getImage());
            $clone->getSpecialiteSaisonDifficulteIndex();
            $form = $this->createForm(new RecetteEditType(), $clone);

            $form->handleRequest($this->getRequest());
            

            if ($form->isValid()) {
                $clone = $form->getData();

                // On recupere la saison, la difficulté et la specialité
                $clone->setDifficulte($this->manageAttribut('difficulte',$clone->getDifficulte()));
                $clone->setSaison($this->manageAttribut('saison',$clone->getSaison()));
                $clone->setSpecialite($this->manageAttribut('specialite',$clone->getSpecialite()));
                $clone->setAuteur($usr);
                
                $em     ->persist($clone);
                $em     ->flush(); 
                
                if($isVersion) {
                    if ( count($recette->getVersions()) == 0 ) {
                        $groupVersions = new GroupVersions();
                        $recette->setVisibilite(false);
                        $groupVersions->addVersion($recette);
                        $groupVersions->addVersion($clone);
                        $em->persist($groupVersions);
                        $recette->addVersion($groupVersions);
                    } else {
                        $versions = $recette->getVersions();
                        $groupVersionsRepository = $em->getRepository("MealSquareRecetteBundle:GroupVersions");
                        $groupVersions = $groupVersionsRepository->findOneById($versions[0]->getId());
                    }

                    $clone->addVersion($groupVersions);
                }
                else {
                    if ( count($recette->getVariantes()) == 0 ) {
                        $groupVariantes = new GroupVariantes();
                        $groupVariantes->addVariante($recette);
                        $groupVariantes->addVariante($clone);
                        $em->persist($groupVariantes);
                        $recette->addVariante($groupVariantes);
                    } else {
                        $variantes = $recette->getVariantes();
                        $groupVariantesRepository = $em->getRepository("MealSquareRecetteBundle:GroupVariantes");
                        $groupVariantes = $groupVariantesRepository->findOneById($variantes[0]->getId());
                    }

                    $clone->addVariante($groupVariantes);
                }
                
                $clone->createThread();
                $em     ->persist($recette);
                $em     ->persist($clone);
                $em     ->flush();  
                
                if(!$clone->getArchive())
                    return $this->redirect( $this->generateUrl( 'meal_square_recette_show', array('id' => $clone->getId()) ));
                else
                    return $this->redirect( $this->generateUrl('fos_user_profile_show'));

            }

            return $this->render('MealSquareRecetteBundle:Recette:add.html.twig', array('form' => $form->createView(),'isVersion'=>$isVersion));            
        }
    }

}
