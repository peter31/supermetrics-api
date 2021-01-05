<?php declare(strict_types=1);

namespace App\Supermetrics;

use App\Supermetrics\Model\ApiResponseModel;
use App\Supermetrics\Model\PostModel;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class ApiManager
{
    /** @var Client */
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.supermetrics.com'
        ]);
    }

    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \RuntimeException
     */
    public function getSlToken(): string
    {
        $responseModel = $this->prepareResponseModel(
            $this->client->post(
                '/assignment/register',
                [
                    'form_params' => [
                        'client_id' => 'ju16a6m81mhid5ue1z3v2g0uh',
                        'email' => 'your@email.address',
                        'name' => 'Your Name',
                    ]
                ]
            )
        );

        if (!array_key_exists('sl_token', $responseModel->getData())) {
            throw new \RuntimeException(sprintf('[Supermetrics API] Unable to retrieve sl_token: key is missing - "%s"', json_encode($responseModel->getData())));
        }

        return $responseModel->getData()['sl_token'];
    }

    /**
     * @param int $page
     * @return PostModel[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \RuntimeException
     */
    public function getPosts(int $page = 1): array
    {
        $responseModel = $this->prepareResponseModel(
            $this->client->get(
            '/assignment/posts',
                [
                    'query' => [
                        'sl_token' => $this->getSlToken(),
                        'page' => $page
                    ]
                ]
            )
        );

        if (!array_key_exists('posts', $responseModel->getData())) {
            throw new \RuntimeException(sprintf('[Supermetrics API] Unable to retrieve posts: key is missing - "%s"', json_encode($responseModel->getData())));
        }

        return array_map(
            function (array $post) {
                return PostModel::fromArray($post);
            },
            $responseModel->getData()['posts']
        );
    }

    private function prepareResponseModel(ResponseInterface $response): ApiResponseModel
    {
        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException(sprintf('[Supermetrics API] Incorrect response status "%s"', $response->getStatusCode()));
        }

        $responseContent = $response->getBody()->getContents();
        $responseData = json_decode($responseContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(sprintf('[Supermetrics API] Incorrect response body type "%s"', $responseContent));
        }

        if (!array_key_exists('meta', $responseData) || !array_key_exists('data', $responseData)) {
            throw new \RuntimeException(sprintf('[Supermetrics API] Incorrect response JSON structure "%s"', $responseContent));
        }

        return new ApiResponseModel($responseData['meta'], $responseData['data']);
    }
}