<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class MicropostsController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illminate\Http\Response
     */
     public function index()
     {
         $data = [];
         if (\Auth::check()){
             $user = \Auth::user();
             $microposts = $user->feed_microposts()->orderBy('created_at', 'desc')->paginate(10);
             
             $data = [
                'user' => $user,
                'microposts' => $microposts,
            ];
            //タイムライン追加に伴い削除（Welcomeに変更)
            /*
            $data += $this->counts($user);
            return view('users.show', $data);
         }else{
          */
         }
             return view('welcome', $data);
     }
     
     public function store(Request $request)
     {
         $this->validate($request, [
             'content' => 'required|Max:191',
         ]);
         
         $request->user()->microposts()->create([
             'content' => $request->content,
         ]);
         
         return redirect()->back();
     }
     
     Public function destroy($id)
     {
        $micropost = \App\Micropost::find($id);
         
        if (\Auth::id() === $micropost->user_id) {
            $micropost->delete();
        }
        return redirect()->back();
     }
}
