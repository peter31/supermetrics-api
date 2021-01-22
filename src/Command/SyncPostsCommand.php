<?php declare(strict_types=1);

namespace App\Command;

class SyncPostsCommand
{
    /** @var int */
    private $page;

    public function getPage()
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;
        return $this;
    }
}
