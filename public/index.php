<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

$application = new \App\Application(true);

$em = $application->getEntityManager();
$postsRepository = $em->getRepository(\App\Entity\PostStatistics::class);


// Average character length of posts per month
// SELECT DATE_FORMAT(created_time, '%Y-%m') as month, ROUND(AVG(message_length)) FROM posts_statistics GROUP BY month ORDER BY month;
$averageLengthPerMonth = $postsRepository->createQueryBuilder('p')
    ->select('DATE_FORMAT(p.createdTime, :monthFormat) as month, ROUND(AVG(p.messageLength)) as value')
    ->setParameter('monthFormat', '%Y-%m')
    ->groupBy('month')
    ->orderBy('month')
    ->getQuery()->getArrayResult();

// Longest post by character length per month
// SELECT DATE_FORMAT(created_time, '%Y-%m') as month, MAX(message_length) FROM posts_statistics GROUP BY month ORDER BY month;
$longestPostPerMonth = $postsRepository->createQueryBuilder('p')
    ->select('DATE_FORMAT(p.createdTime, :monthFormat) as month, MAX(p.messageLength) as value')
    ->setParameter('monthFormat', '%Y-%m')
    ->groupBy('month')
    ->orderBy('month')
    ->getQuery()->getArrayResult();

// Total posts split by week number
//    SELECT DATE_FORMAT(created_time, '%x-%v') as week, COUNT(id) FROM posts_statistics GROUP BY week ORDER BY week;
$totalPostsPerWeek = $postsRepository->createQueryBuilder('p')
    ->select('DATE_FORMAT(p.createdTime, :weekFormat) as week, COUNT(p.id) as value')
    ->setParameter('weekFormat', '%x-%v')
    ->groupBy('week')
    ->orderBy('week')
    ->getQuery()->getArrayResult();

// Average number of posts per user per month
//    SELECT s.from_id, ROUND(AVG(s.value)) as avg_value FROM (
//        SELECT DATE_FORMAT(created_time, '%Y-%m') as month, from_id, COUNT(id) as value
//        FROM posts_statistics
//        GROUP BY month, from_id
//        ORDER BY month
//    ) as s
//    GROUP BY s.from_id
//    ORDER BY s.from_id;
$sql = "SELECT s.from_id, ROUND(AVG(s.value)) as avg_value FROM (
            SELECT DATE_FORMAT(created_time, '%Y-%m') as month, from_id, COUNT(id) as value
            FROM posts_statistics
            GROUP BY month, from_id
            ORDER BY month
        ) as s
        GROUP BY s.from_id
        ORDER BY s.from_id";
$averagePostsPerUserPerMpnth = $em->getConnection()->fetchAll($sql);

echo json_encode([
    'average_length_per_month' => $averageLengthPerMonth,
    'longest_post_per_month' => $longestPostPerMonth,
    'total_posts_per_week' => $totalPostsPerWeek,
    'average_posts_per_user_per_month' => $averagePostsPerUserPerMpnth,
]);
