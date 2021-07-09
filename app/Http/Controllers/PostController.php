<?php

namespace App\Http\Controllers;

use App\Events\DetectTagsEvent;
use App\Events\PostCommentEvent;
use App\Events\PostReleaseEvent;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Facades\App\Repositories\Contracts\PostRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function create(): View
    {
        return view('post.create');
    }

    public function store(CreatePostRequest $request): View
    {
        $post = auth()->user()->posts()->create($request->validated());

        return view('post.preview',compact('post'))->with([
            'releaseLink' => route('posts.release',['slug' => $post->slug]),
            'draftLink'   => route('posts.draft',['slug' => $post->slug]),
            'postType'    => $post->type,
        ]);
    }

    public function release(): RedirectResponse
    {
        $post = PostRepository::pending(request('slug'));

        $this->authorize(Str::customKebab("create-{$post->type}"));

        $post->update(['status' => 'RELEASED']);

        event(new PostReleaseEvent(auth()->user(),$post));

        return redirect()->route('posts.show',$post->slug);
    }

    public function draft(): RedirectResponse
    {
        $post = PostRepository::dontReleased(request('slug'));

        $post->update([
            'status' => 'DRAFT',
        ]);

        return redirect()->route('posts.show',$post->slug);
    }

    public function comment(CreateCommentRequest $request): RedirectResponse
    {
        $post = PostRepository::findOrFail($request->post_id);

        $comment = $post->comments()->create([
            'context'   => $request->context,
            'parent_id' => $request->parent_id,
            'user_id'   => auth()->id(),
        ]);

        event(new PostCommentEvent(auth()->user(),$comment));

        return redirect(sprintf("%s#comment-%s",route('posts.show',$post->slug),$comment->id));
    }

    public function like($slug): JsonResponse
    {
        $post = PostRepository::released($slug,['likes','dislikes']);

        $this->authorize('like',$post);

        if (!$post->likes->contains(auth()->id())) {
            auth()->user()->like($post);
        }

        if ($post->dislikes->contains(auth()->id())) {
            auth()->user()->undislike($post);
        }

        return response()->json([
            'status'  => 'Done',
            'like'    => $post->like,
            'dislike' => $post->dislike,
        ]);
    }

    public function dislike($slug): JsonResponse
    {
        $post = PostRepository::released($slug,['likes','dislikes']);

        $this->authorize('dislike',$post);

        if (!$post->dislikes->contains(auth()->id())) {
            auth()->user()->dislike($post);
        }

        if ($post->likes->contains(auth()->id())) {
            auth()->user()->unlike($post);
        }

        return response()->json([
            'status'  => 'Done',
            'like'    => $post->like,
            'dislike' => $post->dislike,
        ]);
    }

    public function show($slug): View
    {
        $post = PostRepository::released($slug,['user:id,name']);

        $post['is_liked'] = $post->likes->contains(auth()->user());

        $post['is_disliked'] = $post->dislikes->contains(auth()->user());

        $permissions = [
            'update_post'         => auth()->user()?->can('edit',$post),
            'delete_post'         => auth()->user()?->can('delete',$post),
            'manage_chief_choice' => auth()->user()?->can('manage-chief-choice'),
            'manage_slide_post'   => auth()->user()?->can('manage-slide-post'),
            'create_comment'      => auth()->user()?->can('create-comment'),
        ];

        $post->increment('view');

        return view('post.show',compact('post','permissions'));
    }

    public function edit($slug): View
    {
        $post = PostRepository::released($slug);

        $this->authorize('edit',$post);

        return view('post.edit',compact('post'));
    }

    public function update(UpdatePostRequest $request): RedirectResponse
    {
        $request->post->update($request->validated());

        event(new DetectTagsEvent($request->post));

        return redirect()->route('posts.show',$request->post->slug);
    }

    public function destroy($slug): RedirectResponse
    {
        $post = PostRepository::firstOrFail($slug);

        $this->authorize('delete',$post);

        $post->delete();

        return redirect()->route('users.home');
    }

    public function lastNews(): JsonResponse
    {
        $response = PostRepository::lastNews();

        return response()->json($response);
    }
}
