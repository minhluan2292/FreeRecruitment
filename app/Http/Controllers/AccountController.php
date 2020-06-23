<?php

namespace App\Http\Controllers;

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
        return response()->json($accounts);
    }
    public function store(Request $request)
    {
        if(User::whereEmail($request->email)->count() > 0)
        {
            return 'Email exist';
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);
        return response()->json($user);
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

        return response()->json($user);
    }


}
