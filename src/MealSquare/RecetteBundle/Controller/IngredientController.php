<?php

namespace MealSquare\RecetteBundle\Controller;

use MealSquare\RecetteBundle\Entity\Ingredient;
use MealSquare\RecetteBundle\Form\IngredientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IngredientController extends Controller {

    public function listFilterableAction(Request $request, $sorted) {
        $query = $this->getDoctrine()->getRepository("MealSquareRecetteBundle:Ingredient")->createQueryBuilder('i')
           ->where('i.libelle LIKE :sorted')
           ->setParameter('sorted', $sorted.'%')
           ->getQuery()
           ->getResult();        

        $raccourcis = $this->getDoctrine()->getRepository("MealSquareRecetteBundle:Raccourci")->findBy(array('actif'=>true));
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->get('page', 1)/* page number */, 20/* limit per page */
        );
        // parameters to template
        return $this->render('MealSquareRecetteBundle:Ingredient:listFilterable.html.twig', array(
            'ingredients'   => $pagination,
            'raccourcis'    => $raccourcis,
            'sorted'        => $sorted
        ));
    }

    public function showAction($id) {
        $repository = $this->getDoctrine()->getRepository("MealSquareRecetteBundle:Ingredient");
        $ingredient = $repository->findOneById($id);
        
        if(is_null($ingredient)){
                throw new NotFoundHttpException("Désolé, la page que vous avez demandée semble introuvable !");
        }else{
            $recettes   = $this->getDoctrine()->getRepository("MealSquareRecetteBundle:Recette")->findByIngredient($ingredient->getLibelle());
            return $this->render('MealSquareRecetteBundle:Ingredient:show.html.twig', array(
                'ingredient' => $ingredient,
                'recettes' => $recettes
            ));
        }
    }

    public function addAction() {
        
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new IngredientType(), new Ingredient());

        $form->handleRequest($this->getRequest());
        
        
        if ($form->isValid()) {
            $ingredient = $form->getData();
            
            $em->persist($ingredient);
            $em->flush();

            return $this->redirect( $this->generateUrl( 'meal_square_recette_ingredient_show', array('id' => $ingredient->getId()) ));
        }

        return $this->render('MealSquareRecetteBundle:Ingredient:add.html.twig', array('form' => $form->createView()));
    }

}
