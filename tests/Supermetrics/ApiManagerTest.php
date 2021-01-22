<?php declare(strict_types=1);

namespace Test\Supermetrics;

use App\Supermetrics\ApiManager;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ApiManagerTest extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject|StreamInterface */
    private $body;

    /** @var \PHPUnit\Framework\MockObject\MockObject|ResponseInterface */
    private $response;

    private function getClient(int $statusCode, string $responseString): ClientInterface
    {
        $this->body = $this->createMock(StreamInterface::class);
        $this->body->expects($this->any())->method('getContents')->willReturn($responseString);

        $this->response = $this->createMock(ResponseInterface::class);
        $this->response->expects($this->any())->method('getStatusCode')->willReturn($statusCode);
        $this->response->expects($this->any())->method('getBody')->willReturn($this->body);

        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->any())->method('sendRequest')->willReturn($this->response);

        return $client;
    }

    /** @test */
    public function testGetSlTokenSuccess()
    {
        $apiManager = new ApiManager();
        $apiManager->setClient(
            $this->getClient(
                200,
                '{"meta": {}, "data": {"sl_token": "test"}}'
            )
        );

        $this->assertEquals('test', $apiManager->getSlToken());
    }

    /**
     * @test
     * @dataProvider wrongResponseDataProvider
     */
    public function testWrongResponse(int $statusCode, string $responseString, string $expectedErrorMessage)
    {
        $apiManager = new ApiManager();
        $apiManager->setClient(
            $this->getClient($statusCode, $responseString)
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage($expectedErrorMessage);

        $apiManager->getSlToken();
    }

    static public function wrongResponseDataProvider()
    {
        return [
            [200, '{"meta": {}, "data": {}}', 'Unable to retrieve sl_token'],
            [500, '{"meta": {}, "data": {}}', 'Incorrect response status'],
            [200, '{"data": {}}', 'Incorrect response JSON structure'],
            [200, '{"meta": {}}', 'Incorrect response JSON structure'],
        ];
    }

    /** @test */
    public function testGetPostsSuccess()
    {
        $apiManager = new ApiManager();
        $apiManager->setClient(
            $this->getClient(
                200,
                '{"meta": {}, "data": {"posts": []}}'
            )
        );

        $this->assertEquals([], $apiManager->getPosts('', 1));
    }
}