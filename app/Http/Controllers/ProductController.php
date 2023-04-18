<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Product;
use App\Models\ProductClass;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductListByCategory($category_id)
    {
        $category = ProductClass::find($category_id);
        $products = Product::where('product_class_id', $category_id)->where('active', 1)->get();

        return view('product.index')->with(compact(
            'category',
            'products'
        ));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $product = Product::findOrFail($id);

        return view('product.show')->with(compact(
            'product'
        ));
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

    public function getPromotionProducts()
    {

        $offers = Offer::whereDate('start_date', '<=', date('Y-m-d'))->whereDate('end_date', '>=', date('Y-m-d'))->where('status', 1)->get();
        $i = 0;
        $offers_array = [];
        foreach ($offers as $offer) {
            foreach ($offer->products as $product) {
                $offers_array[$i]['product_id'] = $product->id;
                $offers_array[$i]['image'] = $product->getFirstMediaUrl('product');
                $offers_array[$i]['product_name'] = $product->name;
                $offers_array[$i]['product_details'] = $product->product_details;
                $offers_array[$i]['sell_price'] =  $product->sell_price;
                $offers_array[$i]['discount_price'] =  $product->sell_price - $offer->discount_value;
                $i++;
            }
        }

        return view('product.promotions')->with(compact(
            'offers_array'
        ));
    }
}
