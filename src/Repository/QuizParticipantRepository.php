<?php

namespace App\Repository;

use App\Entity\QuizParticipant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuizParticipant>
 *
 * @method QuizParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizParticipant[]    findAll()
 * @method QuizParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizParticipant::class);
    }

//    /**
//     * @return QuizParticipant[] Returns an array of QuizParticipant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?QuizParticipant
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
