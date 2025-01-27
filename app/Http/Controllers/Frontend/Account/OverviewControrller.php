<?php

namespace App\Http\Controllers\Frontend\Account;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OverviewControrller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $latest_orders = Order::with('items')->where('user_id', '=', $user->id)->take(5)->orderBy('created_at', 'DESC')->get();
        return view('frontend.account.overview', compact('latest_orders'));
    }

}
