<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2018/6/3
 * Time: 12:52
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Service\PaginateService;
use App\Http\Service\PostService;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        return view('admin.post.index');
    }

    public function postList()
    {
        $user = request()->input('user');
        $pageSize = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);
        $orderBy = request()->input('order_by', 'created_at');
        $sortBy = request()->input('sort_by', 'desc');
        $app = $user->app();

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];

        $query = Post::with(['poster', 'praises', 'comments'])
            ->whereHas(Post::REL_USER,function ($query)use($app){
                $query->where(User::FIELD_ID_APP,$app->id);
            })
            ->orderBy(Post::FIELD_CREATED_AT, 'desc');

        $posts = app(PaginateService::class)->paginate($query, $pageParams, '*', function ($post) use ($user) {

            return $this->postService->formatSinglePost($post, $user);

        });

        return webResponse('ok',200,$posts);
    }

    /**
     * 删除评论
     *
     * @author yezi
     *
     * @param $id
     * @return mixed
     * @throws ApiException
     */
    public function delete($id)
    {
        $user = request()->input('user');

        if(empty($id)){
            return webResponse('404',500);
        }

        $result = Comment::where(Comment::FIELD_ID,$id)->delete();
        return webResponse('ok',200,$result);
    }
}