<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        $users = Auth::user();
        return view('dashboard.setting.index', [
            'users' => $users,
        ]);
    }
}
