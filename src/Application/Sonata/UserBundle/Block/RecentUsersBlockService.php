<?php

namespace Application\Sonata\UserBundle\Block;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Model\BlockInterface;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Validator\ErrorElement;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Mapping as ORM;

class RecentUsersBlockService extends BaseBlockService
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
            'title'      => 'Recent Users',
            'template'   => 'SonataUserBundle:Block:recent_users.html.twig',
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

        $users     = $this->em->getRepository("ApplicationSonataUserBundle:User")->findAll();

        return $this->renderResponse($blockContext->getTemplate(), array(
        'users'     => $users,
        'block'     => $blockContext->getBlock(),
        'settings'  => $settings
        ), $response);

    }
}
