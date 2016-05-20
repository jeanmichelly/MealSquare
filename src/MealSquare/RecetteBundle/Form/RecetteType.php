<?php

namespace MealSquare\RecetteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class RecetteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('source', 'text', array('required'=>false))
            ->add('specialite', 'choice', array(
                'choices'   => array('0' => 'Saint-Valentin' , '1' => 'Recettes anglo-saxonne' , '2' => 'Chic et facile' , '3' => 'Recettes méditerranéennes' , '4' => 'Spécialités antillaises' , '5' => 'Exotique' , '6' => 'Recettes de Chef' , '7' => 'Pâques' , '8' => 'Provence' , '9' => 'Orientale' , '10' => 'Repas de fête' , '11' => 'Cuisine légère' , '12' => 'Cuisine rapide' , '13' => 'Mardi Gras' , '14' => 'Asie' , '15' => 'Nordique' , '16' => 'Bretagne' , '17' => 'Sud-ouest' , '18' => 'Spécialités ibériques' , '19' => 'Normandie' , '20' => 'Thanksgiving' , '21' => 'Auvergne' , '22' => 'Halloween' , '23' => 'Recettes américaines' , '24' => 'Pentecôte'),
                'required'  => false,
                'empty_value' => 'Spécialité',
            ))
            ->add('type', 'choice', array(
                'choices'   => array('0' => 'Dessert' , '1' => 'Végétarien', '2' => 'Enfant', '3' => 'Salade', '4' => 'Pizza'),
                'required'  => false,
                'empty_value' => 'Type',
            ))
            ->add('nbPersonne', 'integer', array('attr' => array('min' => '1')))
            ->add('description')
            ->add('visibilite', 'checkbox', array(
                    'required'=>false
                ))
            ->add('difficulte', 'choice', array(
                'choices'   => array(
                                    '0' => 'Très facile',
                                    '1' => 'Facile',
                                    '2' => 'Moyen',
                                    '3' => 'Difficile',
                                    '4' => 'Délicat'
                    
                                ),
                'required'  => true,
                'empty_value' => 'Niveau de difficulté?',
            ))
            ->add('tempsCuisson', 'integer', array('attr' => array('min' => '1'), 'required'=>false))
            ->add('tempsPreparation', 'integer', array('attr' => array('min' => '1')))
            ->add('ingredients', 'collection', array(
                    'type' => 'ingredient_recette_type',
                    'prototype' => true,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false
            ))
            ->add('sectionStep', 'text', array('mapped'=>false))
            ->add('pays',"genemu_jqueryselect2_country", array(
                'required'  => false,
                'empty_value' => 'Pays',
            ))
            ->add('saison', 'choice', array(
                'choices'   => array(
                                    '0' => 'Été',
                                    '1' => 'Printemps',
                                    '2' => 'Automne',
                                    '3' => 'Hiver'
                    
                                ),
                'required'  => false,
                'empty_value' => 'Saison idéale',
            ))
            ->add('image', 'sonata_media_type', array(
                    'provider' => 'sonata.media.provider.image',
                    'context' => 'recette',
                    'required'=>true
                ))
            ->add('categorie', 'entity', array(
                'class' => 'Application\Sonata\ClassificationBundle\Entity\Category',
                'query_builder' => function(EntityRepository $er ) {
                    return $er->createQueryBuilder('c')
                              ->join('c.context','co')
                              ->where('co.name  = :context')
                              ->setParameter('context', 'recette')
                              ->orderBy('c.name', 'ASC');
                }))
            //->add('tags')
        ;
        $builder->get('image')->add('contentType', 'hidden');
        $builder->get('image')->add('unlink', 'hidden', ['mapped' => false, 'data' => false]);
        $builder->get('image')->add('binaryContent', 'file', ['label' => false]);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MealSquare\RecetteBundle\Entity\Recette'
        ));
        $resolver->setDefaults(array(
            'csrf_protection'   => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'recette_type';
    }
}
