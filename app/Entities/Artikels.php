<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Artikels extends Entity
{
    protected $attributes = [
        'title' => null,
        'content' => null,
        'img' => null,
        'slug' => null,
        'views' => null,
    ];

    public function setTitle(string $title): self
    {
        $this->attributes['title'] = strtoupper($title);
        return $this;
    }

    public function setContent(string $content): self
    {
        $this->attributes['content'] = $content;
        return $this;
    }

    public function setImg(string $img): self
    {
        $this->attributes['img'] = $img;
        return $this;
    }

    public function setSlug(string $title): self
    {
        $this->attributes['slug'] = url_title($title, '-', true);
        return $this;
    }
    public function setViews(int $views): self
    {
        $this->attributes['views'] = $views;
        return $this;
    }
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
}
