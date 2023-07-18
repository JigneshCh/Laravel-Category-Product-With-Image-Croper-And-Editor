<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Survey;
use App\Refefile;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Session;
use Auth;
use Carbon;

class ReferenceController extends Controller
{
    public function rotate($id, Request $request)
    {
        $rotation = 90;
        if ($request->has('dir') && $request->get('dir') == "left") {
            $rotation = - ($rotation);
        }
        $refe = Refefile::where("id", $id)->first();

        $result = array();

        if ($refe) {

            $path = public_path('storage');

            if ($refe->refe_file_name && $refe->refe_file_name != "" && \File::exists($path . "/" . $refe->refe_file_path . "/" . $refe->refe_file_name)) {
                $real_path = $path . "/" . $refe->refe_file_path . "/" . $refe->refe_file_name;

                $img = \Image::open($real_path);
                $img->rotate($rotation);
                $img->save($real_path);
            }
            if ($refe->refe_file_name && $refe->refe_file_name != "" && \File::exists($path . "/" . $refe->refe_file_path . "/thumb/" . $refe->refe_file_name)) {
                $real_path =  $path . "/" . $refe->refe_file_path . "/thumb/" . $refe->refe_file_name;

                $img = \Image::open($real_path);
                $img->rotate($rotation);
                $img->save($real_path);
            }

            $result['message'] = trans('common.responce_msg.image_rotate_success');
            $result['code'] = 200;
        } else {
            $result['message'] = trans('common.responce_msg.something_went_wr');
            $result['code'] = 400;
        }

        if ($request->ajax()) {
            return response()->json($result, $result['code']);
        } else {
            Session::flash('flash_message', $result['message']);

            $oldurl = str_replace(url('/'), '', url()->previous());
            return redirect($oldurl . "#ref" . $id);
        }
    }

    public function destroy($id, Request $request)
    {
        $item = Refefile::where("id", $id)->first();

        $result = array();

        if ($item) {
            removeRefeImage($item);
            $item->delete();
            $result['message'] = trans('common.responce_msg.record_deleted_succes');
            $result['code'] = 200;
        } else {
            $result['message'] = trans('common.responce_msg.something_went_wr');
            $result['code'] = 400;
        }

        if ($request->ajax()) {
            return response()->json($result, $result['code']);
        } else {
            Session::flash('flash_message', $result['message']);
            return redirect()->back();
        }
    }
}
