<?php
declare(strict_types=1);

namespace Mlab\BudetControl\Services;

use Illuminate\Support\Facades\Cache;
use Mlab\BudetControl\Entities\Posts;
use Mlab\BudetControl\Facade\WordpressCLient;
use Mlabfactory\WordPress\Entities\Media;

class WordpressPost extends Wordpress {

    const CACHE_TTL = 86400;
    const CACHE_KEY = 'WordpressPostsId';

    /**
     * Retrieves a list of article IDs.
     *
     * @return Posts A collection of post IDs.
     */
    public static function getArticles(): Posts
    {
        if(Cache::has(self::CACHE_KEY)) {
            return Cache::get(self::CACHE_KEY);
        }

        $wordpress = WordpressCLient::post()->list();
        $posts = $wordpress->getPosts();
        $postsId = new Posts();

        /** @var \Mlabfactory\WordPress\Entities\Post $post */
        foreach ($posts as $post) {
            $postUrl = str_replace(WordpressCLient::getServer(), '', $post->getLink());
            $postsId->addPost(
                $postUrl, $post,
                self::getMedia($post->getFeaturedMedia())
            );
        }

        Cache::put(self::CACHE_KEY, $postsId, self::CACHE_TTL);

        return $postsId;

    }

    /**
     * Retrieves the media associated with the given ID.
     *
     * @param int $id The ID of the media to retrieve.
     * @return Media The media object associated with the given ID.
     */
    public static function getMedia(int $id): Media
    {
        $media = WordpressCLient::media()->get($id);
        return $media;
    }

    /**
     * Clears the cache for a specific WordPress post.
     *
     * @param string $cacheKey The key identifying the cache entry to be cleared.
     *
     * @return void
     */
    public static function clearPostCache(string $cacheKey): void
    {
        Cache::forget($cacheKey);
    }

}