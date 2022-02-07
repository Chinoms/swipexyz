<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function comment(Request $request)
    {

        $request->validate([
            'commenter_id' => 'required',
            'post_id' => 'required',
            'post_comment' => 'required'
        ]);
        Comment::create([
            'commenter_id' => $request->commenter_id,
            'post_id' => $request->id,
            'post_comment' => $request->comment
        ]);

        return response()->json([
            'message' => 'Comment posted successfully'
        ]);
    }

    public function deleteComment(Request $request)
    {
        Comment::where('id', $request->comment_id)->delete();
        $child_comments = Comment::where('parent_comment', $request->id)->get();
        if ($child_comments) {
            Comment::where('parent_comment', $request->id)->delete();
        }
        return response()->json([
            'message' => 'Comment posted successfully'
        ]);
    }

    public function countComments(Request $request)
    {
        $data['comments_count'] = Comment::where('post_id', $request->post_id)->count();
        return response(['comments_count' => $data['comments_count']]);
    }
}
