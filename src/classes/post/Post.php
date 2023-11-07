<?php

namespace iutnc\touiteur\post;

use Exception;
use iutnc\touiteur\user\User;
use iutnc\touiteur\exception\InvalidPropertyNameException;

class Post
{
    private int $id;
    private string $postText;
    private string $image;
    private int $score;
    private string $postDate;
    private User $user;

    function __construct(int $id, string $postText, string $image, int $score, string $postDate, User $user) {
        $this->id = $id;
        $this->postText = $postText;
        $this->image = $image;
        $this->score = $score;
        $this->postDate = $postDate;
        $this->user = $user;
    }

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