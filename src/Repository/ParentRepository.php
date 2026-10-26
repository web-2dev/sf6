<?php

namespace App\Repository;

use DateTime;
use App\Entity\ParentEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * ⚠ la classe doit être abstraite pour empêcher l'instanciation par Symfony qui essaye de relier le repository à une entité 
 * Méthodes : 
 *  • nb                                    : nombre d'enregistrement de la table
 *  • dbName                                : nom de la BDD actuellement utilisée
 *  • findAll                               : peut trier le résultat
 *  • findAllPaginate(page, nb, tri)        : permet gérer auto la pagination
 *  • findByPaginate(filtre, page, nb, tri) : permet gérer auto la pagination
 *  • save                                  : enregistrer une entité en BD
 *  • remove                                : supprimer une entité de la BDD 
 *  • search(champs, cherché, filtre)       : recherche champ LIKE valeur 
 *  • findAllOrderedByRelation              : tri selon un champ d'une table reliée (⚠ à tester)
 *  • 
 */
abstract class ParentRepository extends ServiceEntityRepository
{
    /** Renvoie le nombre d'enregistrement de la table
     * 
     * @return int
     */
    public function nb() : int {
        $requestResult = $this->createQueryBuilder("t")
                              ->select("COUNT(t.id) as nb")
                              ->getQuery()->getOneOrNullResult();
        return $requestResult ? $requestResult["nb"] : 0;
    }

    /** La méthode findAll
     *  de la classe Doctrine\ORM\EntityRepository appelle $this->findBy([])
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
        /* VERSION ArrayCollection
        return new ArrayCollection(parent::findBy([], $orderBy));
        */
    }

    /**
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function findByCollection(array $criteria, array|null $orderBy = null, int|null $limit = null, int|null $offset = null):  ArrayCollection {
        
        return new ArrayCollection(parent::findBy($criteria, $orderBy, $limit, $offset));
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

    /** Le nom de la BDD actuellement utilisée
     * 
     */
    public function dbName(){
        return $this->getEntityManager()->getConnection()->getDatabase();
    }

    public function save(ParentEntity $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) { $this->getEntityManager()->flush(); }
    }

    public function remove(ParentEntity $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) { $this->getEntityManager()->flush(); }
    }

    public function search(string|array $fields, string $search, array $sort = []) {
        $search = $search ?? "%";
        if( is_string($fields) ) {
            $fields = [ $fields ];
        }
        $query = $this->createQueryBuilder("a");
        foreach ($fields as $field) {
            $query->orWhere("a.$field LIKE :$field");
            $query->setParameter($field, "%$search%");
        }
        
        if( $sort ) {
            foreach ($sort as $champ => $sens) {
                dump("$champ => $sens" );
                $query->addOrderBy("a.$champ", $sens);
            }
        }
        return $query->getQuery()->getResult();
    }

    /**
     * Tri par un champ d'une table reliée
     * TODO : tester
     */
    public function findAllOrderedByRelation($champRelie, array $champsPourTri)
    {

        $queryBuilder = $this->createQueryBuilder('t')
            ->leftJoin("t.$champRelie", $champRelie)
            ->addSelect($champRelie);
        foreach ($champsPourTri as $champ => $sens) {
            $queryBuilder->addOrderBy("$champRelie.$champ", $sens);
        }
        
        return $queryBuilder->getQuery()->getResult();
    }



}
