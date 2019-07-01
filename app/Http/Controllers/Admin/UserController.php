<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');

        View::share('action', 'no_add');
        View::share('nav', 'users');
    }

    public function index()
    {
        $users = User::all();

        return view('admin.pages.users.index', [
            'users' => $users,
        ]);
    }
}
