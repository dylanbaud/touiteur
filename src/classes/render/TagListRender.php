<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\post\PostList;
use iutnc\touiteur\tag\TagList;

class TagListRender{

    public TagList $tagList;

    public function __construct(TagList $tagList)
    {
        $this->tagList = $tagList;
    }

    public function render(): string
    {
        $html = '';
        foreach ($this->tagList->tags as $tag){
            $html .= $tag->idTag . $tag->libelle;
        }

        return $html;
    }
}
