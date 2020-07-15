<?php

namespace App\Http\Controllers;

use App\Providers\ResponseHelper;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\Environment\Console;

class AccountController extends Controller
{
    //
    public function index()
    {
        return view('account.index');
    }

    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function get()
    {
        $accounts = User::where('delete',0)->get();
        return ResponseHelper::successOKResponse($accounts);
    }

    public function store(Request $request)
    {
        if(User::whereEmail($request->email)->count() > 0)
        {
            return ResponseHelper::emailExistResponse();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);
        return ResponseHelper::successOKResponse($user);
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => ($request->password == "") ? $user->password : Hash::make($request->password),
            'role' => $request->role
        ]);

        return ResponseHelper::successOKResponse($user);
    }

}
