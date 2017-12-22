<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * Post
 * @ORM\Table(name="bookmark")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookmarkRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"photo" = "Bookmark", "video" = "VideoBookmark"})
 */
class Bookmark
{
    const VIDEO_TYPE = 'video';
    const PHOTO_TYPE = 'photo';
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string $url
     *
     * @ORM\Column(name="url", type="text")
     * @Assert\Url(groups={"urlOnly", "Default"})
     * @Assert\NotBlank(groups={"urlOnly"})
     */
    private $url;

    /**
     * @var string $authorName
     *
     * @ORM\Column(name="authorName", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $authorName;

    /**
     * @var string $addDate
     *
     * @ORM\Column(name="addDate", type="datetime")
     */
    private $addDate;

    /**
     * @var string $width
     *
     * @ORM\Column(name="width", type="integer")
     * @Assert\Type("numeric")
     */
    private $width;

    /**
     * @var string $height
     *
     * @ORM\Column(name="height", type="integer")
     * @Assert\Type("numeric")
     */
    private $height;

    /**
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="bookmark", cascade={"persist", "remove"})
     * @MaxDepth(2)
     */
    private $tags;

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
     * Set authorName
     *
     * @param string $authorName
     *
     * @return Bookmark
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Get authorName
     *
     * @return string
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Bookmark
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
     * Set url
     *
     * @param string $url
     *
     * @return Bookmark
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set addDate
     *
     * @param string $addDate
     *
     * @return Bookmark
     */
    public function setAddDate($addDate)
    {
        $this->addDate = $addDate;

        return $this;
    }

    /**
     * Get addDate
     *
     * @return string
     */
    public function getAddDate()
    {
        return $this->addDate;
    }

    /**
     * Set width
     *
     * @param string $width
     *
     * @return Bookmark
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param string $height
     *
     * @return Bookmark
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set tags
     *
     * @param string $tags
     *
     * @param \AppBundle\Entity\Tag $tags
     *
     * @return Bookmark
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function __toString()
    {
        return $this->title;
    }
}
