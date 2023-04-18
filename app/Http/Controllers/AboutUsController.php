<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\System;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $content_data = System::getProperty('about_us_content');

        $store = Store::orderBy('id', 'desc')->first();

        $content = str_replace('{store_name}', $store->name, $content_data);
        $content = str_replace('{store_location}', $store->location, $content);
        $content = str_replace('{store_phone_number}', $store->phone_number, $content);

        $all_store_names = Store::pluck('name', 'id')->toArray();
        $all_store_names = implode(',', $all_store_names);
        $content = str_replace('{all_store_names}', $all_store_names, $content);


        return view('about_us.index')->with(compact('content'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
