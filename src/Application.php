<?php declare(strict_types=1);

namespace App;

use App\Controller\StatisticsController;
use App\Supermetrics\ApiManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use GuzzleHttp\Client;

class Application
{
    /** @var bool */
    private $debug;
    /** @var EntityManager */
    private $em;

    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;

        if ($this->debug) {
            ini_set('display_errors', '1');
            error_reporting(E_ALL);
        }
    }

    public function getEntityManager()
    {
        if (null === $this->em) {
            $dbParams = [
                'driver'   => 'pdo_mysql',
                'host'     => $_ENV['DB_HOST'] ?? '',
                'user'     => $_ENV['DB_USER'] ?? '',
                'password' => $_ENV['DB_PASSWORD'] ?? '',
                'dbname'   => $_ENV['DB_NAME'] ?? '',
            ];

            $config = Setup::createXMLMetadataConfiguration([dirname(__DIR__) . '/config/doctrine/'], false);
            $config->addCustomDatetimeFunction('DATE_FORMAT', \DoctrineExtensions\Query\Mysql\DateFormat::class);
            $config->addCustomDatetimeFunction('ROUND', \DoctrineExtensions\Query\Mysql\Round::class);
            $this->em = EntityManager::create($dbParams, $config);
        }

        return $this->em;
    }

    public function getSupermetricsApiManager()
    {
        $client = new Client(['base_uri' => $_ENV['SM_API_BASE_URL'] ?? '']);

        return (new ApiManager())
            ->setClient($client)
            ->setClientId($_ENV['SM_API_CLIENT_ID'])
            ->setEmail($_ENV['SM_API_EMAIL'])
            ->setName($_ENV['SM_API_NAME'])
        ;
    }

    public function getControllerByRequest(): ?array
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($requestUri, PHP_URL_PATH);
        $path = rtrim($path, '/');

        $routingConfig = [
            '' => [StatisticsController::class, 'index']
        ];

        return array_key_exists($path, $routingConfig) ? $routingConfig[$path] : null;
    }
}