<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\ProductClass;
use App\Models\System;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $homepage_category_carousel = System::getProperty('homepage_category_carousel');
        $categories = ProductClass::orderBy('sort', 'asc')->where('status', 1)->where('name', '!=', 'Extras')->get();

        $offers_array = [];

        $offers = Offer::where(function ($q) {
            $q->whereDate('start_date', '<=', date('Y-m-d'))->whereDate('end_date', '>=', date('Y-m-d'))->orWhereNull('start_date');
        })->where('status', 1)->get();
        $offers_count = 0;
        $i = 0;
        foreach ($offers as $offer) {
            foreach ($offer->products as $product) {
                $offers_array[$i]['product_id'] = $product->id;
                $offers_array[$i]['image'] = $product->getFirstMediaUrl('product');
                $offers_array[$i]['product_name'] = $product->name;
                $offers_array[$i]['product_details'] = $product->product_details;
                $offers_array[$i]['sell_price'] =  $product->sell_price;
                $offers_array[$i]['discount_price'] =  $product->sell_price - $offer->discount_value;
                $i++;
                $offers_count++;
            }
        }

        return view('home.index')->with(compact(
            'categories',
            'offers_array',
            'offers_count',
            'homepage_category_carousel',
        ));
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
