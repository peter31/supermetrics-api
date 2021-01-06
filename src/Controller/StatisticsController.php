<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\PostStatistics;
use App\Repository\PostStatisticsRepository;
use Doctrine\ORM\EntityManager;

class StatisticsController
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function index()
    {
        /** @var PostStatisticsRepository $repository */
        $repository = $this->em->getRepository(PostStatistics::class);

        return json_encode([
            'average_length_per_month' => $repository->getAverageLengthPerMonth(),
            'longest_post_per_month' => $repository->getLongestPostPerMonth(),
            'total_posts_per_week' => $repository->getTotalPostsPerWeek(),
            'average_posts_per_user_per_month' => $repository->getAveragePostsPerUserPerMonth(),
        ], JSON_PRETTY_PRINT);
    }
}