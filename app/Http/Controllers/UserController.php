<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [ 
            'name' => 'required',
             'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=256,max_height=256',
        ]); 
        $imageName = time().'.'.$request->avatar->extension();  
        $request->avatar->move(public_path('images'), $imageName);
        $data = ['name'=>$request->name,'avatar'=>$imageName];
        User::where('id',$user->id)->update($data);
        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }
}
