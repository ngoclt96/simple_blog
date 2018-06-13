<?php
namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends BaseController
{
    use ValidatesRequests, AuthorizesRequests;

    protected $limit = Post::PAGE_RECORD;

    public function index()
    {
        $model = new Post();
        $searchField = $model->getSearchAbleField();
        $params = request()->input();
        $post = $model->availablePosts($params);
        $post = $post->search($params);
        $post = $post->orderBy('posts.id', 'desc');
        $post = $post->paginate($this->limit);
        $this->view(['searchField' => $searchField, 'pages' => $post, 'listField' => $post]);
    }

    public function form($id = null)
    {
        $post = new Post();

        if ($id) {
            $post = Post::findOrFail($id);

            if ($post->user_id != Auth::user()->id) {
                return redirect(route('posts.index'))->with('success', 'You do not edit this post!');
            }

        }

        if (old()) {
            $post->fill(old());
        }

        (request()->session()->has('postsConfirm') && request()->query('back') == 'true') ? $post = request()->session()->get('postsConfirm') : request()->session()->forget('postsConfirm');
        $post->id = $id;
        $this->view(['post' => $post]);

    }

    public function confirm(Request $request)
    {
        $post = new Post();
        $this->validate($request,
            [
                'title' => 'required',
                'content' => 'required'
            ]);
        $post->fill($request->input());

        if ($request->input('id')) {
            $post->id = $request->input('id');
        }

        $request->session()->put('postsConfirm', $post);
        $this->view(['post' => $post]);

    }

    public function complete()
    {
        if (!request()->session()->has('postsConfirm')) {
            return redirect(route('posts.index'));
        }

        $postsConfirm = request()->session()->get('postsConfirm');

        if ($postsConfirm->id) {
            $postsConfirm->exists = true;
        }

        $postsConfirm->user_id = Auth::user()->id;
        $postsConfirm->save();
        request()->session()->forget('postsConfirm');
        $this->view();

    }

    public function delete()
    {
        $id = request()->id;
        $this->deleteRecord('Post', $id);
        return redirect(route('posts.index'));

    }

    public function approve($id = null)
    {
        $user = User::findOrFail(Auth::user()->id);

        if ($user->permission == User::ADMIN) {
            $post = Post::findOrFail($id);
            $post->id = $id;

            if ($post->approve == Post::APPROVER) {
                $post->approve = Post::NON_APPROVER;
                $post->approver_id = null;
            } else {
                $post->approve = Post::APPROVER;
                $post->approver_id = Auth::user()->id;
            }

            $post->save();
            
            return redirect(route('posts.index'));

        }

        return redirect(route('posts.index'))->with('success', 'You have no right to approve this post!');

    }


    public function show($id = null)
    {
        $post = Post::findOrFail($id);
        $post->id = $id;

        if ($post->approve == Post::APPROVER) {
            $postTableName = (new Post())->getTable();
            $userTableName = (new User())->getTable();
            $detail_post = $post->select
            (
                "$userTableName.name",
                "$postTableName.title",
                "$postTableName.content",
                "$postTableName.created_at",
                "$postTableName.updated_at"
            )
                ->where("$postTableName.id", $id)
                ->join("$userTableName", "$userTableName.id", "$postTableName.user_id")
                ->first();

            return view($this->getViewDir() . '.' . 'post.show', ['post' => $detail_post]);

        }

        return redirect(route('posts.index'))->with('success', 'This post has not been approved by the admin!');

    }

    public function postApproved()
    {
        $model = new Post();
        $postTableName = (new Post())->getTable();
        $userTableName = (new User())->getTable();

        $post_approved = $model->select("$postTableName.*", "$userTableName.name")
            ->where("$postTableName.approve", Post::APPROVER)
            ->where("$postTableName.deleted", Post::NOT_DELETE)
            ->join("$userTableName", "$userTableName.id", "$postTableName.user_id")
            ->get();

        return view('home', ['post' => $post_approved]);

    }
}