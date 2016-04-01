<?php

namespace MealSquare\RecetteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GroupVersions
 *
 * @ORM\Table("group_versions")
 * @ORM\Entity
 */
class GroupVersions
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
     * @ORM\ManyToMany(targetEntity="Recette", mappedBy="versions", cascade={"persist"})
     */
    private $versions;

    /**
     * @ORM\OneToOne(targetEntity="Recette")
     * @ORM\JoinColumn(name="recetteMere_id", referencedColumnName="id", nullable=true)
     */
    private $recetteMere;

    public function __construct() {
        $this->versions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set id
     *
     * @param integer $id
     *
     * @return GroupVersions
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Add version
     *
     * @param \MealSquare\RecetteBundle\Entity\Recette $version
     *
     * @return GroupVersions
     */
    public function addVersion(\MealSquare\RecetteBundle\Entity\Recette $version)
    {
        $this->versions[] = $version;

        return $this;
    }

    /**
     * Remove version
     *
     * @param \MealSquare\RecetteBundle\Entity\Recette $version
     */
    public function removeVersion(\MealSquare\RecetteBundle\Entity\Recette $version)
    {
        $this->versions->removeElement($version);
    }

    /**
     * Get versions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVersions()
    {
        return $this->versions;
    }

    /**
     * Set recetteMere
     *
     * @param \MealSquare\RecetteBundle\Entity\Recette $recetteMere
     *
     * @return GroupVersions
     */
    public function setRecetteMere(\MealSquare\RecetteBundle\Entity\Recette $recetteMere = null)
    {
        $this->recetteMere = $recetteMere;

        return $this;
    }

    /**
     * Get recetteMere
     *
     * @return \MealSquare\RecetteBundle\Entity\Recette
     */
    public function getRecetteMere()
    {
        return $this->recetteMere;
    }
}
