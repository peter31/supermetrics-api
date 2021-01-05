<?php declare(strict_types=1);

namespace App;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

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
                'host'     => 'mysql',
                'user'     => 'root',
                'password' => 'root',
                'dbname'   => 'sm',
            ];

            $config = Setup::createXMLMetadataConfiguration([dirname(__DIR__) . '/config/doctrine/'], false);
            $config->addCustomDatetimeFunction('DATE_FORMAT', \DoctrineExtensions\Query\Mysql\DateFormat::class);
            $config->addCustomDatetimeFunction('ROUND', \DoctrineExtensions\Query\Mysql\Round::class);
            $this->em = EntityManager::create($dbParams, $config);
        }

        return $this->em;
    }
}