<?php
declare(strict_types=1);

namespace Mlab\BudetControl\Http\Controller;

use Illuminate\Support\Facades\Cache;
use Mlab\BudetControl\Services\WordpressPost;
use Mlab\BudetControl\View\Blog\ArticlesList;
use Mlab\BudetControl\View\Render\Views;
use Mlab\BudetControl\Entities\Posts;
use Mlabfactory\WordPress\Entities\Media;
use Mlabfactory\WordPress\Entities\Post;

class BlogController
{
    const CACHE_TTL = 86400;

    public function index($request, $response, $args)
    {
        $view = new ArticlesList();
        $articles = [];

        $posts = WordpressPost::getArticles();

        /** @var Posts $post */
        foreach ($posts->getPosts() as $post) {

            /** @var Post $article */
            $article = $post['post'];
            
            /** @var Media $media */
            $media = $post['media'];

            $articles[] = [
                'title' => $article->getContent()->getTitle(),
                'link' => path_to($article->getLink(), 'articles'),
                'excerpt' => $article->getContent()->getExcerpt(),
                'date' => $article->getDate(),
                'author' => $article->getAuthor(),
                'media' => $media?->getLink()
            ];
        }

        return $view->render(['posts' => $articles]);
    }

    
    /**
     * Retrieve data from cache.
     *
     * @param string $cacheKey The key used to identify the cached data.
     * @param Views $view The view instance to be used.
     * @return mixed The cached data or other relevant result.
     */
    protected function fromCache(string $cacheKey, Views $view): mixed
    {
        if(Cache::has($cacheKey)) {
            $data = Cache::get($cacheKey);
            return $view->render($data);
        }

        return false;
    }

    /**
     * Save data in cache with the given cache key.
     *
     * @param string $cacheKey The key to identify the cached data.
     * @param mixed $data The data to be cached.
     * @return void
     */
    protected function saveInCache(string $cacheKey, mixed $data): void
    {
        Cache::forever($cacheKey, $data);
    }

    /**
     * Check if the cache exists for the given cache key.
     *
     * @param string $cacheKey The key to identify the cache.
     * @return bool True if the cache exists, false otherwise.
     */
    protected function hasCache(string $cacheKey): bool
    {
        return Cache::has($cacheKey);
    }
}