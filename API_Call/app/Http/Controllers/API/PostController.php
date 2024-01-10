<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Facade;
use Validator;
use DB;

class PostController extends Controller
{
    function list(Request $request){
        $filter = $request->all();
     
        $string=implode("",$filter);
        
       
        if (!empty($string)) {
            
            return $posts = Post::where('posts.prod_name', 'like', '%'.$string.'%')->get();
            
        } 
        elseif (isset($filter) !='') {
            return $post = Post::all();
        }
        
        // return $posts;
    }

    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $user = User::where('users.email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $response = ['User' => 'You are Logged in'];
                return response($response, 200);
            } else {
                $response = ["message" => "Password not match"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User not exist'];
            return response($response, 422);
        }
    }
}
