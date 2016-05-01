<?php

namespace MealSquare\RecetteBundle\Entity\Repository;

/**
 * RecetteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RecetteRepository extends \Doctrine\ORM\EntityRepository {

    public function findAnnonceByParametres($data) {
        $difficultes = array(
            '0' => 'Très facile',
            '1' => 'Facile',
            '2' => 'Moyen',
            '3' => 'Difficile',
            '4' => 'Délicat'
        );


        $specialites = array('0' => 'Saint-Valentin' , '1' => 'Recettes anglo-saxonne' , '2' => 'Chic et facile' , '3' => 'Recettes méditerranéennes' , '4' => 'Spécialités antillaises' , '5' => 'Exotique' , '6' => 'Recettes de Chef' , '7' => 'Pâques' , '8' => 'Provence' , '9' => 'Orientale' , '10' => 'Repas de fête' , '11' => 'Cuisine légère' , '12' => 'Cuisine rapide' , '13' => 'Mardi Gras' , '14' => 'Asie' , '15' => 'Nordique' , '16' => 'Bretagne' , '17' => 'Sud-ouest' , '18' => 'Spécialités ibériques' , '19' => 'Normandie' , '20' => 'Thanksgiving' , '21' => 'Auvergne' , '22' => 'Halloween' , '23' => 'Recettes américaines' , '24' => 'Pentecôte');

        $types = array('0' => 'Dessert' , '1' => 'Végétarien', '2' => 'Enfant', '3' => 'Salade');
     
        $query = $this->createQueryBuilder('a');
        $query->where('a.titre LIKE  :titre ');
        $query->setParameter('titre','%' . $data['titre'] . '%');
        
        if(isset($data['ingredients'])){
            $query->leftJoin('a.ingredients', 'iLine');
            $query->leftJoin('iLine.ingredient', 'i');
            $i = 0;
            foreach ($data['ingredients'] as $ing) {
                $repository = $this->getEntityManager()->getRepository("MealSquareRecetteBundle:Ingredient");
                $ingredient = $repository->findOneById($ing);
                $ing = $ingredient->getLibelle();

                $query->andWhere(
                    $query->expr()->orX(
                        $query->expr()->like('i.libelle', ':libelle'.$i),
                        $query->expr()->like('a.full_ingredients', ':libelle'.$i)
                    ));
                $query->setParameter('libelle'.$i,'%'.$ing.'%');
                $i++;
            }
        }
        if ($data['pays'] != null) {
            $query->andWhere('a.pays = :pays');
            $query->setParameter('pays',$data['pays']);
                
        } 
        if ($data['difficulte'] != null) {
            $difficulte = $difficultes[$data['difficulte']];
            $query->andWhere('a.difficulte = :difficulte');
            $query->setParameter('difficulte',$difficulte);
                
        } 
        
        if ($data['specialite'] != null) {
            $specialite = $specialites[$data['specialite']];
            $query->andWhere('a.specialite = :specialite');
            $query->setParameter('specialite',$specialite);
        }             

        if ($data['type'] != null) {
            $type = $types[$data['type']];
            $query->andWhere('a.type = :type');
            $query->setParameter('type',$type);
        }              
        
        if ($data['categorie'] != null) {
            $categorie = $data['categorie'];
            $query->andWhere('a.categorie = :categorie');
            $query->setParameter('categorie',$categorie);
        } 

        if ($data['auteur'] != null) {
            $auteur = $data['auteur'];
            $query->andWhere('a.auteur = :auteur');
            $query->setParameter('auteur',$auteur);
        } 
        
        return $query->getQuery()->getResult();
    }
    
    public function findRecipesByRaccourci($data) {

        $specialites = array('0' => 'Saint-Valentin' , '1' => 'Recettes anglo-saxonne' , '2' => 'Chic et facile' , '3' => 'Recettes méditerranéennes' , '4' => 'Spécialités antillaises' , '5' => 'Exotique' , '6' => 'Recettes de Chef' , '7' => 'Pâques' , '8' => 'Provence' , '9' => 'Orientale' , '10' => 'Repas de fête' , '11' => 'Cuisine légère' , '12' => 'Cuisine rapide' , '13' => 'Mardi Gras' , '14' => 'Asie' , '15' => 'Nordique' , '16' => 'Bretagne' , '17' => 'Sud-ouest' , '18' => 'Spécialités ibériques' , '19' => 'Normandie' , '20' => 'Thanksgiving' , '21' => 'Auvergne' , '22' => 'Halloween' , '23' => 'Recettes américaines' , '24' => 'Pentecôte');

        $query       = $this->createQueryBuilder('a');
        
        if(isset($data['ingredient'])){
            $query->leftJoin('a.ingredients', 'iLine');
            $query->leftJoin('iLine.ingredient', 'i');
            $query->where(
                $query->expr()->orX(
                    $query->expr()->like('i.libelle', ':libelle'),
                    $query->expr()->like('a.full_ingredients', ':libelle')
                ));
            $query->setParameter('libelle','%'.$data['ingredient'].'%');
        }elseif(isset($data['pays'])) {
            $query->where('a.pays = :pays');
            $query->setParameter('pays',$data['pays']);
        }elseif(isset($data['specialite']) && isset($specialites[$data['specialite']])) {
            $specialite = $specialites[$data['specialite']];
            $query->where('a.specialite = :specialite');
            $query->setParameter('specialite',$specialite);
        }elseif(isset($data['type'])) {
            $query->where('a.type = :type');
            $query->setParameter('type',$data['type']);
        }elseif(isset($data['categorie'])) {
            $query->leftJoin('a.categorie', 'cat');
            $query->andWhere('cat.slug = :categorie');
            $query->setParameter('categorie','%'.$data['categorie'].'%');
        } 
        return $query->getQuery()->getResult();
    }

    public function findRecipesByCountry($slug) {
        $query       = $this->createQueryBuilder('a');
        $query->where('a.pays = :pays');
        $query->setParameter('pays',$slug);
        return $query->getQuery()->getResult();
    }
    
    public function findByIngredient($ingredient) {
        
        $query = $this->createQueryBuilder('r');
        $query->leftJoin('r.like', 'l');
        $query->leftJoin('r.ingredients', 'iLine');
        $query->leftJoin('iLine.ingredient', 'i');
        $query->where(
            $query->expr()->orX(
                $query->expr()->like('i.libelle', ':ingredient'),
                $query->expr()->like('r.full_ingredients', ':ingredient')
            ));
        $query->setParameter('ingredient','%'.$ingredient.'%');
        $query->setMaxResults(10);
        $query->orderBy("l.numLikes","DESC");
        
        return $query->getQuery()->getResult();
    }

    public function getNb() {
 
        return $this->createQueryBuilder('l')
 
                        ->select('COUNT(l)')
 
                        ->getQuery()
 
                        ->getSingleScalarResult();
 
    }
    
    public function getDayRecipe() {
 
        $query = $this->createQueryBuilder('a');
        $query->where('a.recetteDuJour =  true ');
        $query->andWhere('a.visibilite = true');
        return $query->getQuery()->getResult();
    }
    
    public function getWeekRecipe() {
 
        $query = $this->createQueryBuilder('a');
        $query->where('a.recetteDeLaSemaine =  true ');
        $query->andWhere('a.visibilite = true');
        return $query->getQuery()->getResult();
    }
    
    public function getMonthRecipe() {
 
        $query = $this->createQueryBuilder('a');
        $query->where('a.recetteDuMois =  true ');
        $query->andWhere('a.visibilite = true');
        return $query->getQuery()->getResult();
    }
    
     public function getSelectedRecipe() {
 
        $query = $this->createQueryBuilder('a');
        $query->where('a.selection =  true ');
        $query->andWhere('a.visibilite = true');
        return $query->getQuery()->getResult();
    }
    
     public function getMoreRatedRecipe() {

    }
    
    public function getClassicRecipe() {
 
        $query = $this->createQueryBuilder('a');
        $query->where('a.classique =  true ');
        $query->andWhere('a.visibilite = true');
        return $query->getQuery()->getResult();
    }
     public function getNewAddRecipe() {
 
        return $this->createQueryBuilder('l')
 
                        ->select('COUNT(l)')
 
                        ->getQuery()
 
                        ->getSingleScalarResult();
 
    }
}
