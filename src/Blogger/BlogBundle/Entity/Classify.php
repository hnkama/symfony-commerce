<?php
namespace Blogger\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="classify")
 */
class Classify
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $classify;
	
	/**
	 * 
	 * @ORM\OneToMany(targetEntity="Blog", mappedBy="category")
	 */
	protected $blogs;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $f_id;

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
     * Set classify
     *
     * @param string $classify
     * @return Classify
     */
    public function setClassify($classify)
    {
        $this->classify = $classify;
    
        return $this;
    }

    /**
     * Get classify
     *
     * @return string 
     */
    public function getClassify()
    {
        return $this->classify;
    }

    /**
     * Set f_id
     *
     * @param integer $fId
     * @return Classify
     */
    public function setFId($fId)
    {
        $this->f_id = $fId;
    
        return $this;
    }

    /**
     * Get f_id
     *
     * @return integer 
     */
    public function getFId()
    {
        return $this->f_id;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->blogs = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add blogs
     *
     * @param \Blogger\BlogBundle\Entity\Blog $blogs
     * @return Classify
     */
    public function addBlog(\Blogger\BlogBundle\Entity\Blog $blogs)
    {
        $this->blogs[] = $blogs;
    
        return $this;
    }

    /**
     * Remove blogs
     *
     * @param \Blogger\BlogBundle\Entity\Blog $blogs
     */
    public function removeBlog(\Blogger\BlogBundle\Entity\Blog $blogs)
    {
        $this->blogs->removeElement($blogs);
    }

    /**
     * Get blogs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBlogs()
    {
        return $this->blogs;
    }
    
    public function __toString()
    {
    	return $this->classify;
    }
}