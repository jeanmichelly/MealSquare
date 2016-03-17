<?php

namespace MealSquare\RecetteBundle\Entity\Repository;

/**
 * IngredientRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class IngredientRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function findIngredientsLike( $term, $limit = 10 )
    {

            $qb = $this->createQueryBuilder('i');
            $qb ->select('i.libelle, i.id')
            ->where('i.libelle LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->setMaxResults($limit);

            $arrayAss= $qb->getQuery()
            ->getArrayResult();

            // Transformer le tableau associatif en un tableau standard
            $array = array();
            foreach($arrayAss as $data)
            {
                    $array[] = array("id"=>$data['id'], "libelle"=>$data['libelle']);
            }

            return $array;
    }   
     public function getNb() {
 
        return $this->createQueryBuilder('l')
 
                        ->select('COUNT(l)')
 
                        ->getQuery()
 
                        ->getSingleScalarResult();
 
    }
}
