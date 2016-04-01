<?php

namespace MealSquare\RecetteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GroupVariantes
 *
 * @ORM\Table("group_variantes")
 * @ORM\Entity
 */
class GroupVariantes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="Recette", mappedBy="variantes", cascade={"persist"})
     */
    private $variantes;

    public function __construct() {
        $this->variantes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add variante
     *
     * @param \MealSquare\RecetteBundle\Entity\Recette $variante
     *
     * @return GroupVariantes
     */
    public function addVariante(\MealSquare\RecetteBundle\Entity\Recette $variante)
    {
        $this->variantes[] = $variante;

        return $this;
    }

    /**
     * Remove variante
     *
     * @param \MealSquare\RecetteBundle\Entity\Recette $variante
     */
    public function removeVariante(\MealSquare\RecetteBundle\Entity\Recette $variante)
    {
        $this->variantes->removeElement($variante);
    }

    /**
     * Get variantes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVariantes()
    {
        return $this->variantes;
    }
}
