<?php declare(strict_types=1);

namespace App\Repository;

use App\Supermetrics\ApiManager;
use App\Supermetrics\Model\PostModel;

class PostRepository
{
    /** @var ApiManager */
    private $apiManager;

    /** @var string */
    private $slToken;

    public function __construct(ApiManager $apiManager)
    {
        $this->apiManager = $apiManager;
    }

    /**
     * @return PostModel]
     */
    public function getPosts(int $page = 1): array
    {
        return $this->apiManager->getPosts($this->getCachedSlToken(), $page);
    }
    
    public function getCachedSlToken(): string
    {
        if (null === $this->slToken) {
            $this->slToken = $this->apiManager->getSlToken();
        }

        return $this->slToken;
    }
}
