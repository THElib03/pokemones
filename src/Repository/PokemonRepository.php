<?php

namespace App\Repository;

use App\Entity\Pokemon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pokemon>
 */
class PokemonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokemon::class);
    }

    public function getUserPokemons($user): array{
        return $this->createQueryBuilder('p')
            ->where('p.user = :user')
            ->setParameter('user', $user)
            ->getQuery() -> getResult();
    }

    public function getPokemonById($id): Pokemon|null{
        return $this -> createQueryBuilder('p')
            -> where('p.id = :id')
            -> setParameter('id', $id)
            -> getQuery() -> getResult()[0] ?? null;
    }

    public function getDeadPokemones($user): array|null{
        return $this -> createQueryBuilder('p')
            -> where('p.user = :user')
            -> setParameter('user', $user)
            -> andWhere('p.isAlive = :false')
            -> setParameter('false', false)
            -> getQuery() -> getResult();
    }

//    /**
//     * @return Pokemon[] Returns an array of Pokemon objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Pokemon
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
