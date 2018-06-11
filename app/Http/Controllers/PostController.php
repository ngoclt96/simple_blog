<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PostController extends BaseController
{
    use ValidatesRequests;

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
        }
        if (old()) {
            $post->fill(old());
        }
        (request()->session()->has('postsConfirm') && request()->query('back') == 'true') ? $booking = request()->session()->get('postsConfirm') : request()->session()->forget('postsConfirm');
        $post->id = $id;
        $user = $post->pluckUser();
        $this->view(['post' => $post, 'user' => $user]);
    }

    public function confirm(Request $request)
    {
        $post = new Post();
        $this->validate($request, [
            'user_id' => 'required|integer',
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
        $postsConfirm->save();
        request()->session()->forget('postsConfirm');
        $this->view();
    }

    public function delete()
    {
        $this->deleteRecord('Post');
        return redirect(route('posts.index'));

    }

    public function approve()
    {
//        $post = new Post();
//        dd($post->id);
//        $post = Post::findOrFail(request()->id);
//        if ($post->approve == 1) {
//            $post->approve = 0;
//            $post->approver_id = null;
//        } else {
//            $post->approve = 1;
//            $post->approver_id = Auth::user()->id;
//        }
//        $post->update();
//        dd($post);
//        return redirect(route('posts.index'));

    }

    public function show($id = null)
    {
        $post = Post::findOrFail($id);
        $post->id = $id;
        if ($post->approve == 1) {
            $detail_post = $post->select('users.name', 'posts.title', 'posts.content', 'posts.created_at', 'posts.updated_at')
                ->where('posts.id', $id)
                ->join('users', 'users.id', 'posts.user_id')->first();
            return view($this->getViewDir() . '.' . 'post.show', ['post' => $detail_post]);
        }
        return redirect(route('posts.index'))->with('success', 'This post has not been approved by the admin!');

    }
}