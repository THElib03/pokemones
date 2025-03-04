<?php

namespace App\Repository;

use App\Entity\Pokedex;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pokedex>
 */
class PokedexRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokedex::class);
    }

    public function generateWildPokemon(): Pokedex{
        $pkmns = $this -> createQueryBuilder('p')
            -> getQuery() -> getResult();

        return $pkmns[rand(0, sizeof($pkmns) - 1)];
    }

    public function getPokedexById(int $id): Pokedex|null{
        return $this -> createQueryBuilder('p')
            -> where('p.id = :id')
            -> setParameter('id', $id)
            -> getQuery() -> getResult()[0] ?? null;
    }

//    /**
//     * @return Pokedex[] Returns an array of Pokedex objects
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

//    public function findOneBySomeField($value): ?Pokedex
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
