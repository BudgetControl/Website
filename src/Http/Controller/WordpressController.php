<?php
declare(strict_types=1);

namespace Mlab\BudetControl\Http\Controller;

use League\Container\Exception\NotFoundException;
use Mlab\BudetControl\Entities\Posts;
use Mlab\BudetControl\View\Blog\Article;
use Mlab\BudetControl\Facade\WordpressCLient;
use Mlab\BudetControl\Services\WordpressPost;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class WordpressController extends BlogController
{
    private Posts $postsId;

    public function __construct()
    {
        $this->postsId = WordpressPost::getArticles();
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

        return $page->render($data, $wordpress);
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
}