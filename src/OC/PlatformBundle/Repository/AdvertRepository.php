<?php

namespace OC\PlatformBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdvertRepository extends EntityRepository
{
    public function getAdvertWithCategories(array $categoryNames){
        $qb=$this->createQueryBuilder('a');
        $qb->innerJoin('a.categories','c')->addSelect('c');
        $qb->where($qb->expr()->in('c.name',$categoryNames));
        return $qb->getQuery()->getResult();
    }
    public function getApplicationsWithAdvert($limit){
        $qb=$this->createQueryBuilder('a');
        $qb->innerJoin('a.advert','adv')->addSelect('adv');
        $qb->setMaxResults($limit);
        return $qb->getQuery()->getResult();
    }
    public function getAdverts($page, $nbPerPage){
        $query=$this->createQueryBuilder('a')
            ->leftJoin('a.image','i')
            ->addSelect('i')
            ->leftJoin('a.categories','c')
            ->addSelect('c')
            ->orderBy('a.date','DESC')
            ->getQuery();
        $query->setFirstResult(($page-1)*$nbPerPage)->setMaxResults($nbPerPage);
        return new Paginator($query,true);
    }
}
