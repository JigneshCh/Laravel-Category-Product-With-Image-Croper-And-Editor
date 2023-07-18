<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Issue;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
	
	public function index()
    {
		return view('admin.dashboard');
    }
	
	public function export(Request $request)
    {
		$tbl = $request->export;
		
        $filename = $tbl.Carbon::now('Asia/Kolkata')->format("d-m-Y").".csv";
		$list =  \DB::table($tbl)->get()->toArray();
             
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.$filename,
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];
        if(isset($list[0])){
			$list = json_encode($list);
			$list = json_decode($list,true);
			array_unshift($list, array_keys($list[0]));

			$callback = function() use ($list) 
			{
				 $FH = fopen('php://output', 'w');
				 foreach ($list as $row) { 
					 fputcsv($FH, $row);
				 }
				 fclose($FH);
			};

         return Response::stream($callback, 200, $headers);
        }
    }
	
	
	public function recoverItem(Request $request)
    {
		
		$result = array();

        $rules = array(
            'item' => 'required',
            'id' => 'required',
        );
		$validator = \Validator::make($request->all(), $rules,[]);

        if ($validator->fails())
        {
            $validation = $validator;
            $msgArr = $validator->messages()->toArray();
            $messages = reset($msgArr)[0];

            return response()->json(['message' =>$messages,'success' => false,'status' => 400],400);
        }
		
		$ob = null;
		$modal = $request->item;
		$id = $request->id;
		if($modal == 'issue'){
			$ob = Issue::withTrashed()->where('id',$id)->first();
			$ob->restore();
		}
		
		if($ob){
            $result['message'] = \Lang::get('common.responce_msg.record_restored_succes');;
            $result['code'] = 200;
        }else{
            $result['message'] = \Lang::get('common.responce_msg.data_not_found');;
            $result['code'] = 400;
        }

        return response()->json($result, $result['code']);
	}
}
