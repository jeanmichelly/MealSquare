<?php

namespace MealSquare\RecetteBundle\Block;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Model\BlockInterface;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Validator\ErrorElement;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Mapping as ORM;

class RecentRecipesBlockService extends BaseBlockService
{

    protected $em;

    public function __construct($type, $templating, $em)
    {
        $this->type = $type;
        $this->templating = $templating;
        $this->em = $em;
    }

    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'number'     => '5',
            'title'      => 'Recent Recipes',
            'template'   => 'MealSquareRecetteBundle:Block:recent_recipes.html.twig',
        ));
    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper
        ->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('number', 'integer', array('required' => true)),
                array('title', 'text', array('required' => false)),
            )
        ));
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {

    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $settings = $blockContext->getSettings();

        $recipes     = $this->em->getRepository("MealSquareRecetteBundle:Recette")->findAll();

        return $this->renderResponse($blockContext->getTemplate(), array(
        'recipes'     => $recipes,
        'block'     => $blockContext->getBlock(),
        'settings'  => $settings
        ), $response);

    }
}
