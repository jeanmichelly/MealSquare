<?php

namespace MealSquare\RecetteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use MealSquare\RecetteBundle\Form\DataTransformer\IngredientDataTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;

class IngredientRecetteType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder
            ->add('quantity', 'integer', array('required'=>true, 'attr' => array('min' => '1'))
            )
            ->add('unitMeasurement', 'choice', array(
                'choices'   => array(
                    'unité' => 'unité',
                    'ml' => 'ml',
                    'l' => 'l', 
                    'mg' => 'mg', 
                    'g' => 'g',
                    'kg' => 'kg',
                    'livre' => 'livre',
                    'gousse' => 'gousse',
                    'cuillère à soupe' => 'cuillère à soupe',
                    'cuillère à café' => 'cuillère à café',
                    'tasse' => 'tasse'),
                'required'  => true,
                'empty_value' => 'Unité de mesure',
            ))
            ->add('ingredient','ingredient_type', array(
                'compound' => true)
            )
            ->add('ingredient', 'entity', array(
                'class' => 'MealSquare\RecetteBundle\Entity\Ingredient',
                'query_builder' => function(EntityRepository $er ) {
                    return $er->createQueryBuilder('c')
                              ->orderBy('c.libelle', 'ASC');
                },
                'required'  => true,
                'empty_value' => 'Ingrédient',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MealSquare\RecetteBundle\Entity\IngredientRecette'
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
        return 'ingredient_recette_type';
    }
}
