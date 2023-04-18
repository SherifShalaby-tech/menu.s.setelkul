<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SizeController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('settings.size.view')) {
            abort(403, __('lang.not_authorized'));
        }

        if (request()->ajax()) {

            $sizes = Size::orderBy('name', 'asc');


            $sizes = $sizes->select(
                'sizes.*',

            );

            return DataTables::of($sizes)
                ->addColumn(
                    'action',
                    function ($row) {
                        $html =
                            '<div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">' . __('lang.action') .
                            '<span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';

                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('settings.size.edit')) {
                            $html .=
                                '<li><a data-href="' . action('Admin\SizeController@edit', $row->id) . '" data-container=".view_modal" class="btn btn-modal"
                            target="_blank"><i class="fas fa-edit"></i> ' . __('lang.edit') . '</a></li>';
                        }

                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('settings.size.delete')) {
                            $html .=
                                '<li>
                            <a data-href="' . action('Admin\SizeController@destroy', $row->id) . '"
                                data-check_password="' . action('Admin\UserController@checkPassword', Auth::user()->id) . '"
                                class="btn text-red delete_item"><i class="fa fa-trash"></i>
                                ' . __('lang.delete') . '</a>
                        </li>';
                        }

                        $html .= '</ul></div>';

                        return $html;
                    }
                )

                ->rawColumns([
                    'action',
                ])
                ->make(true);
        }


        return view('admin.size.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('settings.size.create')) {
            abort(403, __('lang.not_authorized'));
        }


        $quick_add = request()->quick_add ?? null;

        $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');

        return view('admin.size.create')->with(compact(
            'quick_add',
            'sizes'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('settings.size.create')) {
            abort(403, __('lang.not_authorized'));
        }

        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
        );

        try {
            $data = $request->except('_token', 'quick_add');

            DB::beginTransaction();
            $size = Size::create($data);

            $size_id = $size->id;

            $this->commonUtil->addSyncDataWithPos('Size', $size, $data, 'POST', 'size');

            DB::commit();
            $output = [
                'success' => true,
                'size_id' => $size_id,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }


        if ($request->quick_add) {
            return $output;
        }

        return redirect()->back()->with('status', $output);
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
        if (!auth()->user()->can('settings.size.edit')) {
            abort(403, __('lang.not_authorized'));
        }


        $size = Size::find($id);

        return view('admin.size.edit')->with(compact(
            'size'
        ));
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
        if (!auth()->user()->can('settings.size.edit')) {
            abort(403, __('lang.not_authorized'));
        }


        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
        );

        try {
            $data = $request->except('_token', '_method');

            DB::beginTransaction();
            $size = Size::where('id', $id)->first();
            $size->update($data);

            $this->commonUtil->addSyncDataWithPos('Size', $size, $data, 'PUT', 'size');
            DB::commit();
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('settings.size.delete')) {
            abort(403, __('lang.not_authorized'));
        }


        try {
            $size = Size::find($id);

            DB::beginTransaction();
            $this->commonUtil->addSyncDataWithPos('Size', $size, null, 'DELETE', 'size');
            $size->delete();
            DB::commit();

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

        return $output;
    }

    public function getDropdown()
    {
        $size = Size::orderBy('name', 'asc')->pluck('name', 'id');
        $size_dp = $this->commonUtil->createDropdownHtml($size, 'Please Select');

        return $size_dp;
    }
}
