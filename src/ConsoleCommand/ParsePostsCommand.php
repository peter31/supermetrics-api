<?php declare(strict_types=1);

namespace App\ConsoleCommand;

use App\Command\SyncPostsCommand;
use App\Command\SyncPostsCommandHandler;

class ParsePostsCommand
{
    /** @var SyncPostsCommandHandler */
    private $syncPostsCommandHandler;

    public function __construct(SyncPostsCommandHandler $syncPostsCommandHandler)
    {
        $this->syncPostsCommandHandler = $syncPostsCommandHandler;
    }

    public function execute()
    {
        for ($page = 1; $page <= 10; $page++) {
            $this->syncPostsCommandHandler->execute((new SyncPostsCommand())->setPage($page));

            echo sprintf('### Page %s parsed', $i) . PHP_EOL;
        }
    }
}