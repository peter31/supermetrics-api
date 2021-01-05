<?php declare(strict_types=1);

namespace App\Supermetrics\Model;

class ApiResponseModel
{
    /** @var array */
    private $meta;

    /** @var array */
    private $data;

    public function __construct(array $meta, array $data)
    {
        $this->meta = $meta;
        $this->data = $data;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function getData(): array
    {
        return $this->data;
    }
}