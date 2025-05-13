<?php
declare(strict_types=1);

namespace Mlab\BudetControl\Http\Controller;

use Mlab\BudetControl\Entities\Posts;
use Mlab\BudetControl\View\Blog\Article;
use Mlab\BudetControl\Entities\Categories;
use Mlab\BudetControl\Facade\WordpressCLient;
use Mlab\BudetControl\Services\WordpressPost;
use Mlab\BudetControl\View\Blog\ArticlesList;
use League\Container\Exception\NotFoundException;
use Mlab\BudetControl\Services\Wordpress;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class WordpressController extends BlogController
{
    private Posts $postsId;
    private Categories $categories;

    public function __construct()
    {
        $this->postsId = WordpressPost::getArticles();
        $this->categories = WordpressPost::getCategories();
    }
    
    public function show(Request $request, Response $response, array $args)
    {
        $slug = $args['slug'];
        $category = $args['category'];

        $postPath = "/$category/$slug/";
        $page = new Article();

        $postId = $this->postsId->getPostId($postPath);

        if(!$postId) {
            throw new NotFoundException('',404);
        }

        $wordpress = WordpressCLient::post()->get($postId);
        $contentData = $wordpress->getContent();

        $data = $contentData->toArray();
        $data['seo'] = $wordpress->getBody()['yoast_head_json'];
        $data['category'] = [
            'name' => $this->categories->getbyname($category)->getName(),
            'slug' => $this->categories->getbyname($category)->getSlug()
        ];

        $data['excerpt'] = $this->replaceUrl($data['excerpt']);
        $data['content'] = $this->replaceUrl($data['content']);
        $data['seo'] = $this->replaceUrlObject($data['seo']);

        return $page->render($data, $wordpress);
    }

    public function showByCategory(Request $request, Response $response, array $args)
    {
        $category = $args['category'];

        $page = new Article();
        $category = $this->categories->getbyname($category);

        if(!$category) {
            throw new NotFoundException('',404);
        }

        $view = new ArticlesList();
        $articles = [];

        $posts = WordpressPost::getArticles(['categories' => $category->getId()]);

        /** @var Posts $post */
        foreach ($posts->getPosts() as $post) {

            /** @var Post $article */
            $article = $post['post'];
            
            /** @var Media $media */
            $media = $post['media'];
            
            $articles[] = [
                'title' => $article->getContent()->getTitle(),
                'link' => path_to($article->getLink(), 'blog'),
                'excerpt' => $this->replaceUrl($article->getContent()->getExcerpt()),
                'date' => $article->getDate(),
                'author' => $article->getAuthor(),
                'media' => $this->replaceUrl($media?->getLink())
            ];
        }

        return $view->render(['posts' => $articles, 'category' => [
            'name' => $category->getName(),
            'slug' => $category->getSlug()
        ]]);
    }

    /**
     * Handles the caching of a post.
     *
     * @param Request $request The HTTP request object.
     * @param Response $response The HTTP response object.
     * @param array $args Additional arguments.
     *
     * @return Response The HTTP response object.
     */
    public function postCache(Request $request, Response $response, array $args)
    {
        if(!isset($request->getQueryParams()['key'])) {
            return $response->withStatus(400);
        }

        try {
            $cacheKey = $request->getQueryParams()['key'];
            WordpressPost::clearPostCache($cacheKey);
            return $response->withStatus(200);
        } catch ( \Throwable $e) {
            return $response->withStatus(500);
        }
        
    }

    /**
     * Recursively replaces URLs in an object or array.
     *
     * @param array|object $data The data structure to process
     * @return array|object The processed data with replaced URLs
     */
    private function replaceUrlObject($data)
    {
        // If data is an array, iterate through it
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->replaceUrlObject($value);
            }
            return $data;
        }
        
        // If data is an object, iterate through its properties
        if (is_object($data)) {
            foreach (get_object_vars($data) as $key => $value) {
                $data->{$key} = $this->replaceUrlObject($value);
            }
            return $data;
        }
        
        // If it's a string, replace the URL
        if (is_string($data)) {
            return $this->replaceUrl($data);
        }
        
        // Otherwise, return the value unchanged
        return $data;
    }
}