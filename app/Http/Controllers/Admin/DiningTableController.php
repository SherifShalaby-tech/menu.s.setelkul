<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiningRoom;
use App\Models\DiningTable;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class DiningTableController extends Controller
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
        if (!auth()->user()->can('settings.dining_table.view')) {
            abort(403, __('lang.not_authorized'));
        }

        if (request()->ajax()) {

            $dining_tables = DiningTable::leftjoin('dining_rooms', 'dining_tables.dining_room_id', 'dining_rooms.id')
                ->orderBy('dining_tables.name', 'asc');


            $dining_tables = $dining_tables->select(
                'dining_tables.*',
                'dining_rooms.name as dining_room_name',

            );

            return DataTables::of($dining_tables)
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
                        if (auth()->user()->can('settings.dining_table.edit')) {
                            $html .=
                                '<li><a data-href="' . action('Admin\DiningTableController@edit', $row->id) . '" data-container=".view_modal" class="btn btn-modal"
                            target="_blank"><i class="fas fa-edit"></i> ' . __('lang.edit') . '</a></li>';
                        }

                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('settings.dining_table.delete')) {
                            $html .=
                                '<li>
                            <a data-href="' . action('Admin\DiningTableController@destroy', $row->id) . '"
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


        return view('admin.dining_table.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('settings.dining_table.create')) {
            abort(403, __('lang.not_authorized'));
        }


        $quick_add = request()->quick_add ?? null;

        $dining_rooms = DiningRoom::orderBy('name', 'asc')->pluck('name', 'id');

        return view('admin.dining_table.create')->with(compact(
            'quick_add',
            'dining_rooms'
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
        if (!auth()->user()->can('settings.dining_table.create')) {
            abort(403, __('lang.not_authorized'));
        }

        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
        );

        try {
            $data = $request->except('_token', 'quick_add');

            DB::beginTransaction();
            $dining_table = DiningTable::create($data);

            $dining_table_id = $dining_table->id;

            $dining_room = DiningRoom::find($data['dining_room_id']);
            $data['dining_room_id'] = $dining_room->pos_model_id;

            $this->commonUtil->addSyncDataWithPos('DiningTable', $dining_table, $data, 'POST', 'dining-table');

            DB::commit();
            $output = [
                'success' => true,
                'dining_table_id' => $dining_table_id,
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
        if (!auth()->user()->can('settings.dining_table.edit')) {
            abort(403, __('lang.not_authorized'));
        }


        $dining_table = DiningTable::find($id);
        $dining_rooms = DiningRoom::orderBy('name', 'asc')->pluck('name', 'id');

        return view('admin.dining_table.edit')->with(compact(
            'dining_table',
            'dining_rooms'
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
        if (!auth()->user()->can('settings.dining_table.edit')) {
            abort(403, __('lang.not_authorized'));
        }


        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
        );

        try {
            $data = $request->except('_token', '_method');

            DB::beginTransaction();
            $dining_table = DiningTable::where('id', $id)->first();
            $dining_table->update($data);

            $dining_room = DiningRoom::find($data['dining_room_id']);
            $data['dining_room_id'] = $dining_room->pos_model_id;

            $this->commonUtil->addSyncDataWithPos('DiningTable', $dining_table, $data, 'PUT', 'dining-table');
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
        if (!auth()->user()->can('settings.dining_table.delete')) {
            abort(403, __('lang.not_authorized'));
        }


        try {
            $dining_table = DiningTable::find($id);

            DB::beginTransaction();
            $this->commonUtil->addSyncDataWithPos('DiningTable', $dining_table, null, 'DELETE', 'dining-table');
            $dining_table->delete();
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
        $dining_table = DiningTable::orderBy('name', 'asc')->pluck('name', 'id');
        $dining_table_dp = $this->commonUtil->createDropdownHtml($dining_table, 'Please Select');

        return $dining_table_dp;
    }
}
