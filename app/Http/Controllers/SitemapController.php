<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
class SitemapController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
 
        return response()->view('home.sitemap', [
            'products' => $products
        ])->header('Content-Type', 'text/xml');
    }
}
