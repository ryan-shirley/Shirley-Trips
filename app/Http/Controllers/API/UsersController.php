<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
Use App\User;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();

        return $users->load('holidays');
    }
}
