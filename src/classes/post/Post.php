<?php

namespace iutnc\touiteur\post;

use Exception;
use iutnc\touiteur\user\User;
use iutnc\touiteur\exception\InvalidPropertyNameException;

class Post
{
    protected int $id;
    protected string $postText;
    protected ?string $image;
    protected int $score;
    protected string $postDate;
    protected User $user;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @throws Exception
     */
    public function __set(string $at, mixed $val): void
    {
        if (property_exists($this, $at)) {
            $this->$at = $val;
        } else throw new InvalidPropertyNameException();
    }

    /**
     * @throws Exception
     */
    public function __get(string $at): mixed
    {
        if (property_exists($this, $at)) return $this->$at;
        throw new Exception ("$at: invalid property");
    }
}