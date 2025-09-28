<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    public function index()
    {
        $company = Auth::user()->admin->company;
        return view('admin.status', ['status' => $company->status]);
    }
}
