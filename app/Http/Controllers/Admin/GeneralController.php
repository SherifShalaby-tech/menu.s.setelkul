<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Utils\Util;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;

    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @return void
     */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }

    public function uploadImageTemp(Request $request)
    {
        $image = $request->image;

       return $this->commonUtil->uploadTempImage($image);
    }
}
