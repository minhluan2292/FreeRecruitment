<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

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
        $accounts = User::all();
        return response()->json($accounts);
    }
}
