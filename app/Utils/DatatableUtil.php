<?php

namespace App\Utils;

use App\Utils\Util;

class DatatableUtil extends Util
{

    public function getActionButtons($id, $button_include = [], $controller_name)
    {
        $btnView = '';
        $btnEdit = '';
        $btnDelete = '';
        if (in_array('view', $button_include)) {
            $btnView = '<a href=' . action($controller_name . '@show', $id) . ' class="btn btn-xs btn-default text-primary mx-1 shadow" title="View"><i class="fa fa-lg fa-fw fa-eye"></i></a>';
        }
        if (in_array('edit', $button_include)) {
            $btnEdit = '<a href=' . action($controller_name . '@edit', $id) . ' class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>';
        }
        if (in_array('delete', $button_include)) {
            $btnDelete = '<a data-href=' . action($controller_name . '@destroy', $id) . ' class="btn btn-xs btn-default text-danger mx-1 shadow delete_item" title="Delete"><i class="fa fa-lg fa-fw fa-trash"></i></a>';
        }
        $buttons =   '<nobr>' . $btnView . $btnEdit . $btnDelete . '</nobr>';

        return $buttons;
    }
}
