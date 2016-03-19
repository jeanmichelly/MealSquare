<?php

namespace MealSquare\RecetteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use MealSquare\RecetteBundle\Form\DataTransformer\IngredientDataTransformer;
use Doctrine\Common\Persistence\ObjectManager;

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
        
        // cela suppose que le gestionnaire d'entité a été passé en option
        $transformer = new IngredientDataTransformer($this->om);
        
        $builder
            ->add('quantite', 'integer', array('required'=>true, 'attr' => array('min' => '1'))
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
            ->add(
                $builder->create('ingredient','text', array(
                    'label' => false,
                    'attr' => array(
                            'placeholder' => 'Ingredient',
                            'data-id' => 'libelle'
                        ),
                    'required'=>true
                ))->addModelTransformer($transformer)
            )
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
