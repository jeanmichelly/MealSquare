<?php
namespace MealSquare\RecetteBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of RecetteSearchType
 *
 * @author LE MOPORG
 */
class RecetteSearchType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
   {
       $builder
            ->add('titre', 'text',array('required' => false))
            ->add('difficulte', 'choice', array(
                'choices'   => array(
                                    '0' => 'Très facile',
                                    '1' => 'Facile',
                                    '2' => 'Moyen',
                                    '3' => 'Difficile',
                                    '4' => 'Délicat'
                    
                                ),
                'required'  => False,
                'empty_value' => 'Difficulté',
            ))
            ->add('specialite', 'choice', array(
                'choices'   => array('0' => 'Saint-Valentin' , '1' => 'Recettes anglo-saxonne' , '2' => 'Chic et facile' , '3' => 'Recettes méditerranéennes' , '4' => 'Spécialités antillaises' , '5' => 'Exotique' , '6' => 'Recettes de Chef' , '7' => 'Pâques' , '8' => 'Provence' , '9' => 'Orientale' , '10' => 'Repas de fête' , '11' => 'Cuisine légère' , '12' => 'Cuisine rapide' , '13' => 'Mardi Gras' , '14' => 'Asie' , '15' => 'Nordique' , '16' => 'Bretagne' , '17' => 'Sud-ouest' , '18' => 'Spécialités ibériques' , '19' => 'Normandie' , '20' => 'Thanksgiving' , '21' => 'Auvergne' , '22' => 'Halloween' , '23' => 'Recettes américaines' , '24' => 'Pentecôte'),
                'required'  => False,
                'empty_value' => 'Spécialité',
            ))
            ->add('type', 'choice', array(
                'choices'   => array('0' => 'Dessert' , '1' => 'Végétarien', '2' => 'Enfant', '3' => 'Salade'),
                'required'  => false,
                'empty_value' => 'Type',
            ))
            ->add('pays',"genemu_jqueryselect2_country", array(
                'empty_value' => 'Pays',
                'required' => false
            )) 
            ->add('nbPersonne', 'integer', array(
                'required' => false
            ))
            ->add('categorie', 'entity', array(
                'class' => 'Application\Sonata\ClassificationBundle\Entity\Category',
                'query_builder' => function(EntityRepository $er ) {
                    return $er->createQueryBuilder('c')
                              ->join('c.context','co')
                              ->where('co.name  = :context')
                              ->setParameter('context', 'recette')
                              ->orderBy('c.name', 'ASC');
                    },
                'empty_value' => 'Catégorie',
                'required'  => False,
            ))
            ->add('ingredients', 'collection', array(
                'type' => 'entity',
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'options'  => array(
                    'class' => 'MealSquare\RecetteBundle\Entity\Ingredient',
                    'query_builder' => function(EntityRepository $er ) {
                        return $er->createQueryBuilder('c')
                                 ->orderBy('c.libelle', 'ASC');
                    },
                    'required'  => true,
                    'empty_value' => 'Ingrédient',
                )
            ))
            ->setMethod('GET')
        ;
   }
   
   /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        
        $resolver->setDefaults(array(
            'csrf_protection'   => false,
        ));
    }
   
    public function getName() {
        return 'recipe_search';
    }
//put your code here
}