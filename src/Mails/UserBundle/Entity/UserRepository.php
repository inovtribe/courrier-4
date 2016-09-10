<?php

namespace Mails\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    
    public function findAllSecretaries($role)
    {
        $qb = $this->createQueryBuilder('u');
            //Puis on filtre sur le nom des catégories à l'aide d'un IN
        $qb->where($qb->expr()->in('u.roles', $role));
      
        
        return $qb
                ->getQuery()
                ->getResult()
        ;
    }

}
