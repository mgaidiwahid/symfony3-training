<?php

namespace OC\PlatformBundle\Repository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
/**
 * AdvertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAdvertWithCategories(array $categoryNames){
        
        $list_adverts = $this->createQueryBuilder('a');
        $list_adverts->innerJoin('a.categories', 'c')
                     ->addSelect('c');
        $list_adverts->where($list_adverts->expr()->in('c.name', $categoryNames));
        
        return $list_adverts->getQuery()
                            ->getResult();

    }
    public function getAdverts($page, $nbPerPage){
        
        $query = $this->createQueryBuilder('a')
                      ->leftJoin('a.image', 'i')
                      ->addSelect('i')
                      ->leftJoin('a.categories', 'c')
                      ->addSelect('c')
                      ->orderBy('a.date', 'DESC')
                      ->getQuery();

        $query->setFirstResult(($page-1) * $nbPerPage)
              ->setMaxResults($nbPerPage);

        return new Paginator($query, true);
    }    
}