<?php

namespace Jiwen\CommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Comment
 *
 * @ORM\Table(name="sylius_comment")
 * @ORM\Entity(repositoryClass="Jiwen\CommentBundle\Entity\CommentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Comment
{
	protected $uploadDir = 'comments';

	protected $temp;

	/**
	 * @Assert\File(maxSize="6000000")
	 */
	private $file;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
	 * @ORM\ManyToOne(targetEntity="Sylius\Bundle\CoreBundle\Entity\Product", inversedBy="Comments")
	 * @ORM\JoinColumn(name="product", referencedColumnName="id")
	 */
    private $product;

    /**
	 * @ORM\ManyToOne(targetEntity="Sylius\Bundle\CoreBundle\Entity\User", inversedBy="Comments")
	 * @ORM\JoinColumn(name="user", referencedColumnName="id")
	 */
    private $user;

    /**
	 * @ORM\ManyToOne(targetEntity="Sylius\Bundle\CoreBundle\Entity\Order", inversedBy="Comments")
	 * @ORM\JoinColumn(name="myorder", referencedColumnName="id")
	 */
	private $myorder;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @var integer
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    protected $path;


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
     * Set product
     *
     * @param integer $product
     * @return Comment
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
     * Set user
     *
     * @param integer $user
     * @return Comment
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
     * Set myorder
     *
     * @param integer $myorder
     * @return Comment
     */
    public function setMyorder($myorder)
    {
        $this->myorder = $myorder;
    
        return $this;
    }

    /**
     * Get myorder
     *
     * @return integer 
     */
    public function getMyorder()
    {
        return $this->myorder;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set score
     *
     * @param integer $score
     * @return Comment
     */
    public function setScore($score)
    {
        $this->score = $score;
    
        return $this;
    }

    /**
     * Get score
     *
     * @return integer 
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Comment
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

    /**
     * Set path
     *
     * @param string $path
     * @return Comment
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return 'comments/'.$this->path;
    }


	public function getAbsolutePath($path)
    {
        return null === $path
            ? null
            : $this->getUploadRootDir().'/'.$path;
    }

    public function getWebPath()
    {
        return null === $path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'media/image/'.$this->uploadDir;
    }

	/**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

	/**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

	/**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
	public function upload()
	{
		if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            if(file_exists($this->getUploadRootDir().'/'.$this->temp) && is_file($this->getUploadRootDir().'/'.$this->temp)) {
                unlink($this->getUploadRootDir().'/'.$this->temp);
            }
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
	}

	/**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath($this->path)) {
            unlink($file);
        }
    }

}
