<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
class AdminController extends Controller
{
    public function sendRegisterMail(Request $request)
    {
         
        $this->validate($request, [
            'email' => 'required|email',

        ]);
        sendMail([
            'file'=>'invite',
            'subject'=>'Invitation',
            'data'=>[],
            'email'=>$request->email
        ]);
        return response()->json([
            'success' => true,
            'data' => $request,
        ]);
    }
}
