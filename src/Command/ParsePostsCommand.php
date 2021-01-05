<?php declare(strict_types=1);

namespace App\Command;

use App\Configuration;
use App\Entity\PostStatistics;
use App\Repository\PostStatisticsRepository;
use App\Supermetrics\ApiManager;
use App\Supermetrics\Model\PostModel;
use Doctrine\ORM\EntityManager;

class ParsePostsCommand
{
    /** @var EntityManager */
    private $em;

    /** @var ApiManager */
    private $apiManager;

    public function __construct(EntityManager $em, ApiManager $apiManager)
    {
        $this->em = $em;
        $this->apiManager = $apiManager;
    }

    public function execute()
    {
        /** @var PostStatisticsRepository $postStatisticsRepository */
        $postStatisticsRepository = $this->em->getRepository(PostStatistics::class);

        for ($i = 1; $i <= 10; $i++) {
            $posts = $this->apiManager->getPosts($i);
            $originalIds = array_map(function (PostModel $post) {return $post->getId();}, $posts);

            $existingPostsStatistics = [];
            foreach ($postStatisticsRepository->findBy(['originalId' => $originalIds]) as $exitingPostStatistics) {
                $existingPostsStatistics[$exitingPostStatistics->getOriginalId()] = $exitingPostStatistics;
            }

            foreach ($posts as $post) {
                if (array_key_exists($post->getId(), $existingPostsStatistics)) {
                    $postStatistics = $existingPostsStatistics[$post->getId()];
                } else {
                    $postStatistics = new PostStatistics();
                    $postStatistics->setOriginalId($post->getId());
                    $postStatistics->setFromName($post->getFromName());
                    $postStatistics->setFromId($post->getFromId());
                    $postStatistics->setCreatedTime($post->getCreatedTime());

                    echo sprintf('[%s] New post statistics created', $postStatistics->getOriginalId()) . PHP_EOL;
                }

                $postStatistics->setMessageLength(mb_strlen($post->getMessage()));
                $this->em->persist($postStatistics);
            }

            $this->em->flush();
            $this->em->clear();

            echo sprintf('### Page %s parsed', $i) . PHP_EOL;
        }
    }
}