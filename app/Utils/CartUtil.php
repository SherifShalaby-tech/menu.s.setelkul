<?php

namespace App\Utils;

use App\Models\Cart;
use App\Utils\Util;

class CartUtil extends Util
{

    public function createOrUpdateCart($user_id)
    {
        Cart::updateOrCreate([
            'user_id' => $user_id,
        ], [
            'user_id' => $user_id,
            'total_amount' => \Cart::session($user_id)->getTotal(),
        ]);

        return true;
    }
}
