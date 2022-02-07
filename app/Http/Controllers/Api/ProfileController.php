<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Follower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function showUserProfile(Request $request)
    {
        // if (Auth::guard('api')->check()) {
        $username = $request->username;
        $userInfo =  User::where('username', '=', $username)->first();
        if ($userInfo == null) {
            return response()->json(['message' => 'User account not found']);
        } else {
            return response()->json(['userInfo' => $userInfo]);
        }
        //} else {
        //  return response()->json(['error' => 'failed']);
        //}

        //list user's posts using another API call
    }


    public function showMyProfile()
    {
        if (Auth::guard('api')->check()) {
            return response()->json(['userInfo' => Auth::guard('api')->user()]);
        } else {
            return response()->json(['message' => 'You need to be logged in to view this']);
        }
    }


    public function editProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
        ]);

        $name = $request->name;
        $surname = $request->surname;
        $username = $request->username;
        $user_bio = $request->user_bio;
        $user_website = $request->user_website;

        DB::table('users')
            ->where('id', Auth::guard('api')->user()->id)
            ->update(['name' => $name, 'surname' => $surname, 'username' => $username, 'user_bio' => $user_bio, 'user_website' => $user_website]);

        return response()->json(['message' => 'Profile updated.']);
    }


    public function follow(Request $request)
    {
        $to_be_followed = $request->following_id;

        $check_followership = DB::table('followers')
            ->where(
                ['user_id', Auth::guard('api')->user()->id],
                ['following', $to_be_followed]
            );
        if ($check_followership == null) {
            if (Follower::create(['user_id' => Auth::user()->id, 'following' => $request->following_id])) {
                return response()->json([
                    'message' => 'Success'
                ]);
            } else {
                return response()->json(['message' => 'Failed']);
            }
        }
    }

    public function unfollow(Request $request)
    {
        if (Follower::where([
            'user_id' => Auth::guard('api')->user()->id,
            'following' => $request->unfollow_id
        ])->delete()) {
            return response()->json([
                'message' => 'Success'
            ]);
        } else {
            return response()->json([
                'message' => 'Failed.'
            ]);
        }
    }
}
