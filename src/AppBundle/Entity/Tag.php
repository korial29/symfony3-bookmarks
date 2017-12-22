<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagRepository")
 */
class Tag
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @ORM\ManyToOne(targetEntity="Bookmark", inversedBy="tags")
     */
    private $bookmark;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Tag
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Get bookmark
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBookmark()
    {
        return $this->bookmark;
    }

    /**
     * Set bookmark
     *
     * @param \AppBundle\Entity\Bookmark $bookmark
     *
     * @return Tag
     */
    public function setBookmark(\AppBundle\Entity\Bookmark $bookmark = null)
    {
        $this->bookmark = $bookmark;

        return $this;
    }

    public function __toString()
    {
        return $this->label;
    }
}
