<?php
namespace Blogger\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity(repositoryClass="Blogger\BlogBundle\Repository\BlogRepository")
* @ORM\Table(name="blog")
* @ORM\HasLifecycleCallbacks()
*/
class Blog
{
	public function __construct()
	{
		$this->created = new \DateTime("now");
	}
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $title;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $author;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $author_email;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $keywords;
	
	/**
     * @ORM\ManyToOne(targetEntity="Classify", inversedBy="blogs")
     * @ORM\JoinColumn(name="classify_id", referencedColumnName="id")
     */
	protected $classify;
	
	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $important;
	
	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $is_open;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $link;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $description;

	/**
	 * @ORM\Column(type="text")
	 */
	protected $content;
	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $created;

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
     * Set title
     *
     * @param string $title
     * @return Blog
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Blog
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set author_email
     *
     * @param string $authorEmail
     * @return Blog
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->author_email = $authorEmail;
    
        return $this;
    }

    /**
     * Get author_email
     *
     * @return string 
     */
    public function getAuthorEmail()
    {
        return $this->author_email;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     * @return Blog
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    
        return $this;
    }

    /**
     * Get keywords
     *
     * @return string 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set is_open
     *
     * @param integer $isOpen
     * @return Blog
     */
    public function setIsOpen($isOpen)
    {
        $this->is_open = $isOpen;
    
        return $this;
    }

    /**
     * Get is_open
     *
     * @return integer 
     */
    public function getIsOpen()
    {
        return $this->is_open;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Blog
     */
    public function setLink($link)
    {
        $this->link = $link;
    
        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Blog
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Blog
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set created
     *
     * @param integer $created
     * @return Blog
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return integer 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set classify
     *
     * @param integer $classify
     * @return Blog
     */
    public function setClassify($classify)
    {
        $this->classify = $classify;
    
        return $this;
    }

    /**
     * Get classify
     *
     * @return integer 
     */
    public function getClassify()
    {
        return $this->classify;
    }

    /**
     * Set important
     *
     * @param integer $important
     * @return Blog
     */
    public function setImportant($important)
    {
        $this->important = $important;
    
        return $this;
    }

    /**
     * Get important
     *
     * @return integer 
     */
    public function getImportant()
    {
        return $this->important;
    }
}