<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Bookmark;
/**
 * Post
 *
 * @ORM\Table(name="video_bookmark")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VideoBookmarkRepository")
 */
class VideoBookmark extends Bookmark
{

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return VideoBookmark
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

}
