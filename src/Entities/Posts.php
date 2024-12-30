<?php 
declare(strict_types=1);

namespace Mlab\BudetControl\Entities;

use Mlabfactory\WordPress\Entities\Media;
use Mlabfactory\WordPress\Entities\Post;

class Posts {

    private array $posts = [];

    public function addPost(string $name, Post $post, ?Media $media): self
    {
        $name = md5($name);
        $this->posts[$name] = [
            'post' => $post,
            'media' => $media
        ];
        return $this;
    }

    public function getPostId(string $name): ?int
    {
        $name = md5($name);
        return $this->posts[$name]['post']?->getId();
    }

    public function getPost(string $name): ?int
    {
        $name = md5($name);
        return $this->posts[$name]['post'];
    }

    /**
     * Get the value of media
     *
     * @return ?Media
     */
    public function getMedia(string $name): ?Media
    {
        $name = md5($name);
        return $this->posts[$name]['media'];
    }

    /**
     * Get the value of posts
     *
     * @return array
     */
    public function getPosts(): array
    {
        return $this->posts;
    }
}