<?php

namespace App\Repository;

use App\Entity\ParentEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * ⚠ la classe doit être abstraite pour empêcher l'instanciation par Symfony qui essaye de relier le repository à une entité 
 * 
 */
abstract class ParentRepository extends ServiceEntityRepository
{
    /**
     * Renvoie le nombre d'enregistrement de la table
     * @return int
     */
    public function nb() : int {
        $requestResult = $this->createQueryBuilder("t")
                              ->select("COUNT(t.id) as nb")
                              ->getQuery()->getOneOrNullResult();
        return $requestResult ? $requestResult["nb"] : 0;
    }

    /**
     * La méthode findAll de la classe Doctrine\ORM\EntityRepository appelle $this->findBy([])
     * et donc ne peut pas être triée. On la redéfinie (= surcharge) dans la classe dont tous 
     * les Repositories vont hériter : 
     *
     * @param array|null $orderBy
     * 
     * @return ParentEntity[].
     */
    public function findAll($orderBy = null): array
    {
        return $this->findBy([], $orderBy);
    }

    public function findAllPaginate(int $page, int $count = 10, $orderBy = null) : array {
        $requestResult = $this->createQueryBuilder("t");
        if( $orderBy ) {
            $requestResult = $requestResult->orderBy($orderBy);
        }
        $requestResult = $requestResult->setFirstResult( ($page - 1 ) * $count)
                                       ->setMaxResults($count);
        return $requestResult->getQuery()->getResult();
    }

    public function findByPaginate(array $where, int $page, int $count = 10, $orderBy = null) : array {
        $requestResult = $this->createQueryBuilder("t")->where($where);
        if( $orderBy ) {
            $requestResult = $requestResult->orderBy($orderBy);
        }
        $requestResult = $requestResult->setFirstResult( ($page - 1 ) * $count)
                                       ->setMaxResults($count);
        return $requestResult->getQuery()->getResult();
    }

    /**
     * Le nom de la BDD actuellement utilisée
     */
    public function dbName(){
        return $this->getEntityManager()->getConnection()->getDatabase();
    }

    public function save(ParentEntity $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ParentEntity $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }





}
