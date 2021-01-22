<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\PostStatistics;
use App\Repository\PostRepository;
use App\Repository\PostStatisticsRepository;
use App\Supermetrics\Model\PostModel;
use Doctrine\ORM\EntityManager;

class SyncPostsCommandHandler
{
    /** @var EntityManager */
    private $em;

    /** @var PostRepository */
    private $postRepository;

    public function __construct(EntityManager $em, PostRepository $postRepository)
    {
        $this->em = $em;
        $this->postRepository = $postRepository;
    }

    public function execute(SyncPostsCommand $command)
    {
        /** @var PostStatisticsRepository $postStatisticsRepository */
        $postStatisticsRepository = $this->em->getRepository(PostStatistics::class);

        $posts = $this->postRepository->getPosts($command->getPage());
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
            }

            $postStatistics->setMessageLength(mb_strlen($post->getMessage()));
            $this->em->persist($postStatistics);
        }

        $this->em->flush();
        $this->em->clear();
    }
}
