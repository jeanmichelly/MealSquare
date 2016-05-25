<?php

namespace MealSquare\RecetteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class IngredientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('description')
            ->add('type', 'choice', array(
                'choices'   => array(
                                    'Autres' => 'Autres',
                                    'Boisson' => 'Boisson',
                                    'Cereale' => 'Cereale',
                                    'Champignon' => 'Champignon',
                                    'Charcuterie' => 'Charcuterie',
                                    'Epice' => 'Epice',
                                    'Fleur' => 'Fleur',
                                    'Fruit' => 'Fruit',
                                    'Fruit de mer' => 'Fruit de mer',
                                    'Legume' => 'Legume',
                                    'Plante' => 'Plante',
                                    'Poisson' => 'Poisson',
                                    'Produit laitier' => 'Produit laitier',
                                    'Sauce' => 'Sauce',
                                    'Viande' => 'Viande'
                                ),
                'required'  => true,
                'empty_value' => "Type d'ingredient",
            ))
            ->add('image', 'sonata_media_type', array(
                    'provider' => 'sonata.media.provider.image',
                    'context' => 'ingredient',
                    'required'=>true
            ))
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
            'data_class' => 'MealSquare\RecetteBundle\Entity\Ingredient'
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
        return 'ingredient_add';
    }
}
