<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
{
    public function likePost(Request $request)
    {
        $check_like = Like::where(['post_id' => $request->post_id, 'user_id' => Auth::guard('api')->user()->id])->first();
        if(!$check_like){
            Like::create(['post_id' => $request->post_id, 'user_id' => Auth::guard('api')->user()->id, 'is_liked' => 1]);
            return response()->json(['message' => 'Success']);
        }

    }

    public function unlike(Request $request){
        $check_like = Like::where(['post_id' => $request->post_id, 'user_id' => Auth::guard('api')->user()->id])->first();
        if($check_like){
            Like::where(['post_id', $request->post_id, 'user_id' => Auth::guard('api')->user()->id])->update();
            return response()->json(['message' => 'Success']);
        }
    }
}
