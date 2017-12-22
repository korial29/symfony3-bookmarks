<?php

namespace AppBundle\Repository;

/**
 * BookmarkRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookmarkRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllBookmarkTag(int $bookmarkId)
    {
        return $this->createQueryBuilder('r')
            ->select('r, i')
            ->leftJoin('r.tags', 'i')
            ->where('r.id = :bookmarkId')
            ->setParameter('bookmarkId', $bookmarkId)
            ->getQuery()
            ->getSingleResult();
    }

    public function findBookmark(int $bookmarkId)
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.id = :bookmarkId')
            ->setParameter('bookmarkId', $bookmarkId)
            ->getQuery()
            ->getSingleResult();
    }
}