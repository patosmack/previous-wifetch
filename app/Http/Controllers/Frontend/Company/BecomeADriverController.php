<?php

namespace App\Http\Controllers\Frontend\Company;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BecomeADriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.company.become_a_driver');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

//        vehicle_plate
//        vehicle_brand
//        vehicle_model
//        vehicle_year
//        license
//
//        name
//        address
//        email
//        phone

        return redirect()->back();
    }

}
