<?php declare(strict_types=1);

namespace App\Supermetrics\Model;

class PostModel
{
    /** @var string */
    private $id;

    /** @var string */
    private $fromName;

    /** @var string */
    private $fromId;

    /** @var string */
    private $message;

    /** @var string */
    private $type;

    /** @var \DateTime */
    private $createdTime;

    public static function fromArray(array $post): self
    {
        $model = new self();
        $model->id = $post['id'];
        $model->fromName = $post['from_name'];
        $model->fromId = $post['from_id'];
        $model->message = $post['message'];
        $model->type = $post['type'];
        $model->createdTime = new \DateTime($post['created_time']);

        return $model;
    }

    public function getId(): string
    {
        return $this->id;
    }


    public function getFromName(): string
    {
        return $this->fromName;
    }

    public function getFromId(): string
    {
        return $this->fromId;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCreatedTime(): \DateTime
    {
        return $this->createdTime;
    }
}
