<?php

namespace MealSquare\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MealSquare\RecetteBundle\Entity\Recette;

class DefaultController extends Controller {

    public function indexAction() {

        $em             = $this->getDoctrine()->getManager();
        $recetteRepo    = $em->getRepository('MealSquareRecetteBundle:Recette');
        $ingredientRepo = $em->getRepository('MealSquareRecetteBundle:Ingredient');
        $userRepo       = $em->getRepository('ApplicationSonataUserBundle:User');
        $postRepo       = $em->getRepository('ApplicationSonataNewsBundle:Post');
        $galleryRepo    = $em->getRepository('ApplicationSonataMediaBundle:Gallery');
        $mediaRepo      = $em->getRepository('ApplicationSonataMediaBundle:Media');
        
        $nbuser = $userRepo->createQueryBuilder('l')
                ->select('COUNT(l)')
                ->getQuery()
                ->getSingleScalarResult();
        $nbrecette = $recetteRepo->getNb();
        $nbingredient = $ingredientRepo->getNb();
        
        $nbpost = $postRepo->createQueryBuilder('l')
                ->select('COUNT(l)')
                ->getQuery()
                ->getSingleScalarResult();
        
        $nbmedia = $mediaRepo->createQueryBuilder('l')
                ->select('COUNT(l)')              
                ->where('l.context = :ct ')
                ->setParameter('ct','ingredient')
                ->orWhere('l.context = :ct_i ')
                ->setParameter('ct_i','recette')
                ->getQuery()
                ->getSingleScalarResult();
        
        $nbastuce = $ingredientRepo->createQueryBuilder('i')
                ->select('COUNT(m)')
                ->join('i.galerie','g')
                ->join('g.galleryHasMedias','ghm')
                ->join('ghm.media','m')
                ->getQuery()
                ->getSingleScalarResult();
        
        $idrecetteofday = $recetteRepo->getDayRecipe();
        $recette_de_la_journee = $recetteRepo->findOneById($idrecetteofday);
        
        $idrecetteofweek = $recetteRepo->getWeekRecipe();
        $recette_de_la_semaine = $recetteRepo->findOneById($idrecetteofweek);

        $idrecetteofmonth = $recetteRepo->getMonthRecipe();
        $recette_du_mois = $recetteRepo->findOneById($idrecetteofmonth);
        
        $idrecetteclassic = $recetteRepo->getClassicRecipe();
        $recette_classic = $recetteRepo->findOneById($idrecetteclassic);
        
        $idrecetteselected = $recetteRepo->getSelectedRecipe();
        $recette_selection = $recetteRepo->findOneById($idrecetteselected);
        
        
        $query = $em->createQuery(
                        'SELECT r FROM MealSquareRecetteBundle:Recette r
                         WHERE r.visibilite = true
                         AND  r.archive = false
                         ORDER BY r.dateCreation DESC'
                );
        $query->setMaxResults(6);
        $recettes = $query->getResult();

        $queryarticle = $em->createQuery(
                        'SELECT p FROM ApplicationSonataNewsBundle:Post p
                         WHERE p.enabled = true
                         ORDER BY p.createdAt DESC'
                );
        $queryarticle->setMaxResults(3);
        $articles = $queryarticle->getResult();

        $queryuser = $em->createQuery(
                        'SELECT p FROM ApplicationSonataUserBundle:User p
                         WHERE p.enabled = true
                         ORDER BY p.createdAt DESC'
                );
        $queryuser->setMaxResults(9);
        $users = $queryuser->getResult();
        
        $raccourcis = $this->getDoctrine()->getRepository("MealSquareRecetteBundle:Raccourci")->findBy(array('actif'=>true));
        
        return $this->render('MealSquareCommonBundle:Default:index.html.twig', array(
            'nbrecette' => $nbrecette,
            'nbpost' => $nbpost,
            'nbmedia' => $nbmedia,
            'articles' => $articles,
            'nbingredient' => $nbingredient, 
            'nbuser' => $nbuser,
            'recette_de_la_journee' => $recette_de_la_journee,
            'recette_de_la_semaine' => $recette_de_la_semaine,
            'recette_du_mois' => $recette_du_mois,
            'recette_classic' => $recette_classic,
            'recette_selection' => $recette_selection,
            'new_users' => $users,
            'dernieres_recette' => $recettes,
            'nbastuce' => $nbastuce,
            'raccourcis' => $raccourcis
        ));
    }
    
}
