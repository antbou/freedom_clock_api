<?php

namespace App\Repository;

use App\Service\Paginator;
use App\Entity\Participant;
use App\Entity\Quiz;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Participant>
 *
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    /**
     * @param array|null<uuid> $ids
     */
    public function findByQuiz(Quiz $quiz, ?int $page, ?int $limit): Paginator
    {
        $query = $this->createQueryBuilder('t')
            ->where('t.quiz = :quizId')
            ->setParameter('quizId', $quiz->getId())
            ->orderBy('t.id', 'ASC')->getQuery();

        return new Paginator($query, currentPage: $page, limit: $limit, name: 'participants');
    }

    public function findByIdsAndQuiz(array $ids, Quiz $quiz): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.quiz = :quizId')
            ->andWhere('t.id IN (:ids)')
            ->setParameter('quizId', $quiz->getId())
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Participant[] Returns an array of Participant objects
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

    //    public function findOneBySomeField($value): ?Participant
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
