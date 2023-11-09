<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\tag\Tag;
use iutnc\touiteur\user\User;
use iutnc\touiteur\post\PostList;

class FollowedPostsRender
{
    private array $userList;
    private array $tagList;
    public function __construct(array $userList, array $tagList){
        $this->userList = $userList;
        $this->tagList = $tagList;
    }

    public function render(): string{
        $html = '';
        $postList = [];
        foreach($this->userList as $user){
            array_merge($postList, (array)User::getPostListUser($user));
        }
        foreach($this->tagList as $tag){
            array_merge($postList, (array)Tag::getPostListTag($tag));
        }

        $postListRender = new PostListRender(new PostList($postList));
        $html .= $postListRender->render();

        return $html;
    }
}