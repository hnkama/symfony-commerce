<?php

namespace Jiwen\BookmarkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bookmark
 *
 * @ORM\Table(name="sylius_bookmark")
 * @ORM\Entity(repositoryClass="Jiwen\BookmarkBundle\Entity\BookmarkRepository")
 */
class Bookmark
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
	 * @ORM\ManyToOne(targetEntity="Sylius\Bundle\CoreBundle\Entity\User", inversedBy="bookmarks")
	 * @ORM\JoinColumn(name="user", referencedColumnName="id")
	 */
    private $user;

    /**
	 * @ORM\ManyToOne(targetEntity="Sylius\Bundle\CoreBundle\Entity\Product", inversedBy="Bookmarks")
	 * @ORM\JoinColumn(name="product", referencedColumnName="id")
	 */
    private $product;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

	public function __construct()
	{
		$this->created = new \DateTime;
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
     * Set user
     *
     * @param integer $user
     * @return Bookmark
     */
    public function setUser($user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return integer 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set product
     *
     * @param integer $product
     * @return Bookmark
     */
    public function setProduct($product)
    {
        $this->product = $product;
    
        return $this;
    }

    /**
     * Get product
     *
     * @return integer 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Bookmark
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }
}
