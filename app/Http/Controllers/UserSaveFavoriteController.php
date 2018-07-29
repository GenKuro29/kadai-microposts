<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User; //追加
use App\Micropost; //追加

class UserSaveFavoriteController extends Controller
{
    public function store(Request $request, $id)
    {
        \Auth::user()->save_favorite($id);
        return redirect()->back();
    }
    
    public function destroy($id)
    {
        \Auth::user()->unsave_favorite($id);
        return redirect()->back();
        
        
    }
}
