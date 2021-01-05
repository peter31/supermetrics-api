<?php declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class PostStatisticsRepository extends EntityRepository
{
    public function getAverageLengthPerMonth()
    {
        return $this->createQueryBuilder('p')
            ->select('DATE_FORMAT(p.createdTime, :monthFormat) as month, ROUND(AVG(p.messageLength)) as value')
            ->setParameter('monthFormat', '%Y-%m')
            ->groupBy('month')
            ->orderBy('month')
            ->getQuery()->getArrayResult();
    }

    public function getLongestPostPerMonth()
    {
        return $this->createQueryBuilder('p')
            ->select('DATE_FORMAT(p.createdTime, :monthFormat) as month, MAX(p.messageLength) as value')
            ->setParameter('monthFormat', '%Y-%m')
            ->groupBy('month')
            ->orderBy('month')
            ->getQuery()->getArrayResult();
    }

    public function getTotalPostsPerWeek()
    {
        return $this->createQueryBuilder('p')
            ->select('DATE_FORMAT(p.createdTime, :weekFormat) as week, COUNT(p.id) as value')
            ->setParameter('weekFormat', '%x-%v')
            ->groupBy('week')
            ->orderBy('week')
            ->getQuery()->getArrayResult();
    }

    public function getAveragePostsPerUserPerMonth()
    {
        $sql = "SELECT s.from_id, ROUND(AVG(s.value)) as avg_value FROM (
            SELECT DATE_FORMAT(created_time, '%Y-%m') as month, from_id, COUNT(id) as value
            FROM posts_statistics
            GROUP BY month, from_id
            ORDER BY month
        ) as s
        GROUP BY s.from_id
        ORDER BY s.from_id";

        return $this->getEntityManager()->getConnection()->fetchAll($sql);
    }
}
