<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DiningTable;
use App\Models\Offer;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Size;
use App\Models\Store;
use App\Models\Variation;
use App\Utils\CartUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $cartUtil;
    protected $commonUtil;

    /**
     * Constructor
     *
     * @param Util $cartUtil
     * @return void
     */
    public function __construct(CartUtil $cartUtil, Util $commonUtil)
    {
        $this->cartUtil = $cartUtil;
        $this->commonUtil = $commonUtil;
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view()
    {
        $user_id = Session::get('user_id');

        $cart_content = \Cart::session($user_id)->getContent()->sortBy('name');

        $extras = Product::leftjoin('product_classes', 'products.product_class_id', 'product_classes.id')
            ->where('product_classes.name', 'Extras')
            ->where('active', 1)
            ->select('products.*')
            ->get();

        $total = \Cart::session($user_id)->getTotal();
        $month_array = $this->commonUtil->getMonthsArray();
        $stores = Store::pluck('name', 'id');
        $dining_tables = DiningTable::pluck('name', 'id');
        return view('cart.view')->with(compact(
            'stores',
            'extras',
            'total',
            'cart_content',
            'dining_tables',
            'month_array'
        ));
    }

    /**
     * add the resource to cart
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addToCartExtra($id)
    {
        try {
            $quantity = !empty(request()->quantity) ? request()->quantity : 1;
            $product = Product::find($id);
            $variation = Variation::where('product_id', $id)->first();

            $price = $variation->default_sell_price;

            $price = $price - $product->discount_value;

            $user_id = Session::get('user_id');
            \Cart::session($user_id)->add(array(
                'id' => $product->id,
                'name' => $product->name,
                'price' => $price,
                'quantity' => $quantity,
                'attributes' => [
                    'variation_id' => $variation->id,
                    'extra' => true,
                    'discount' => $product->discount_value
                ],
                'associatedModel' => $product
            ));

            $this->cartUtil->createOrUpdateCart($user_id);

            $output = [
                'success' => 1,
                'msg' => __('lang.added_to_the_cart_successfully')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => 0,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return redirect()->back()->with('status', $output);
    }

    /**
     * add the resource to cart
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function addToCart($id)
     {
         // return request()->quantity;
         try {
             $quantity = !empty(request()->quantity) ? request()->quantity : 1;
             $variation = Variation::find( $id);
             $product = Product::find($variation->product_id);
             $IsproductHasDiscount = Product::where('id',$variation->product_id)
             ->whereDate('discount_start_date', '<=', date('Y-m-d'))->whereDate('discount_end_date', '>=', date('Y-m-d'))->first();
             $product_discount= !empty($IsproductHasDiscount)?$product->discount_value:0;
            
             $user_id = Session::get('user_id');
             $price = $variation->default_sell_price;
             $price = $price - $product_discount;
             $item_exist = \Cart::session($user_id)->get($variation->id);
             // return $item_exist->quantity+$quantity;
             if (!empty($item_exist)) {
                 \Cart::session($user_id)->update($variation->id, array(
                     // 'quantity' =>  $item_exist->quantity+$quantity
                     'quantity' =>  array(
                         'relative' => false,
                         'value' => $item_exist->quantity+$quantity
                     ),
                 ));
             } else {
                 \Cart::session($user_id)->add(array(
                     'id' => $variation->id,
                     'name' => $product->name,
                     'price' => $price,
                     'quantity' =>  $quantity,
                     'attributes' => [
                         'variation_id' => $variation->id,
                         'extra' => false,
                         'discount' => $product_discount,
                         'size'=>$variation->size->name,
                     ],
                     'associatedModel' => $product
                 ));
             }
 
             $this->cartUtil->createOrUpdateCart($user_id);
 
             $output = [
                 'success' => 1,
                 'msg' => __('lang.added_to_the_cart_successfully')
             ];
         } catch (\Exception $e) {
             Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
             $output = [
                 'success' => false,
                 'msg' => __('lang.something_went_wrong')
             ];
         }
 
         return response()->json(['status'=> $output]);
     }
    // public function addToCart($id,$sizeId)
    // {
    //     try {
            
    //         // return $sizeId;
    //         $quantity = !empty(request()->quantity) ? request()->quantity : 1;
    //         $product = Product::find($id);
    //         $variation = Variation::where('product_id', $id)->first();

    //         $user_id = Session::get('user_id');
    //         if(!empty($variation)){
    //             $price = $variation->default_sell_price;
    //         }else{
    //             $price = 0;
    //         }

    //         $product_size=$product->sizes->where('id',$sizeId)->first();
    //         $price = $price - $product_size->discount_value;
    //         $item_exist = \Cart::session($user_id)->get($product->id);
    //         // return $item_exist;
            
    //         if (!empty($item_exist)) {
    //             $newArray=$item_exist->attributes->sizes;
    //             $count = count($newArray);
    //             $is_added=false;
    //             for($i = 0; $i < $count; $i++){
    //                 $size=$newArray[$i]->size ;
    //                 if($size===$product_size->id){
    //                     // $iobcount = count($newArray);
    //                     // for($iob = 0; $iob < $iobcount; $iob++){
    //                     $newArray[$i]->qty = $newArray[$i]->qty+1;
    //                 // }
    //                     \Cart::session($user_id)->update($product->id, array(
    //                         'quantity' =>  array(
    //                             'relative' => false,
    //                             'value' => $item_exist->quantity + 1
    //                         ),
    //                         'attributes' => [
    //                             'variation_id' => !empty($variation)?$variation->id:null,
    //                             'extra' => false,
    //                             'discount' => $product->discount_value,
    //                             'sizes' => $newArray
    //                         ]
    //                     ));
    //                     $is_added=true;
    //                      break 1;
    //                 }
    //             }
    //             if(!$is_added){
    //                     array_push($newArray,(object) array('size' => $product_size->id, 'qty' => 1));
    //                     \Cart::session($user_id)->update($product->id, array(
    //                             'quantity' =>  array(
    //                                 'relative' => false,
    //                                 'value' => $item_exist->quantity + 1
    //                             ),
    //                             'attributes' => [
    //                                 'variation_id' => !empty($variation)?$variation->id:null,
    //                                 'extra' => false,
    //                                 'discount' => $product->discount_value,
    //                                 'sizes' =>$newArray
    //                             ]
    //                         ));
    //             }
    //          }
    //         else {
    //             $sizes_array[] = (object) array('size' => $product_size->id, 'qty' => 1);
    //             // return 'qwe';
    //             \Cart::session($user_id)->add(array(
    //                 'id' => $product->id,
    //                 'name' => $product->name,
    //                 'price' => $price,
    //                 'quantity' =>  $quantity,
    //                 'attributes' => [
    //                     'variation_id' => !empty($variation)?$variation->id:null,
    //                     'extra' => false,
    //                     'discount' => $product->discount_value,
    //                     'sizes'=>$sizes_array
    //                 ],
    //                 'associatedModel' => $product
    //             ));
    //         }

    //         $this->cartUtil->createOrUpdateCart($user_id);

    //         $output = [
    //             'success' => 1,
    //             'msg' => __('lang.added_to_the_cart_successfully')
    //         ];
    //     } catch (\Exception $e) {
    //         Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
    //         $output = [
    //             'success' => false,
    //             'msg' => __('lang.something_went_wrong')
    //         ];
    //     }

    //     return response()->json(['status', $output]);
    //     // return redirect()->back()->with('status', $output);
    // }
    /**
     * remove product from cart
     *
     * @param int $product_id
     * @return void
     */
    public function removeProduct($cart_id)
    {
        try {
            $user_id = Session::get('user_id');
            \Cart::session($user_id)->remove($cart_id);

            $this->cartUtil->createOrUpdateCart($user_id);

            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return redirect()->back()->with('status', $output);
    }

    /**
     * update product variation
     *
     * @return void
     */
    public function updateProductQuantity($product_id, $quantity)
    {
        try {
            $user_id = Session::get('user_id');


            \Cart::session($user_id)->update($product_id, array(
                'quantity' => array(
                    'relative' => false,
                    'value' => $quantity
                ),
            ));

            $this->cartUtil->createOrUpdateCart($user_id);

            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return redirect()->back()->with('status', $output);
    }
    /**
     * update product variation
     *
     * @return void
     */
    public function updateProductVariation($product_id, $variation_id)
    {
        try {
            $user_id = Session::get('user_id');

            $product = Product::find($product_id);
            $variation = Variation::where('id', $variation_id)->first();



            \Cart::session($user_id)->update($product->id, array(
                'price' => $variation->default_sell_price,
                'attributes' => [
                    'variation_id' => $variation->id,
                ],
            ));

            $this->cartUtil->createOrUpdateCart($user_id);

            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return redirect()->back()->with('status', $output);
    }
    public function clearCart()
    {
        try {
            $user_id = Session::get('user_id');

            \Cart::session($user_id)->clear();

            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return redirect()->back()->with('status', $output);
    }
}
