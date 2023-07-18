<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Issue;
use App\Categories;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Session;

class IssueController extends Controller
{

	public function index(Request $request)
	{
		return view('admin.issues.index');
	}

	public function datatable(Request $request)
	{
		$record = Issue::select(['issue.*'])->where("issue.id", ">", 0);

		if ($request->has('status') && $request->get('status') != 'all' && $request->get('status') != '') {
			$record->where('issue.status', $request->get('status'));
		}

		if ($request->has('enable_deleted') && $request->enable_deleted == 1) {
			$record->onlyTrashed();
		}

		return Datatables::of($record)->make(true);
	}


	public function create(Request $request)
	{
		$categories = Categories::select(['id', 'name', 'slug', 'parent_id'])->where("is_hidden", "!=", 1)->Parentonly()->orderby('display_order', 'asc')->get();
		return view('admin.issues.create', compact('categories'));
	}
	public function editImages($issue_id, Request $request)
	{
		$item = Issue::where("id", $issue_id)->first();
		if (!$item) {
			Session::flash('flash_error', trans('common.responce_msg.data_not_found'));
			return redirect()->back();
		}
		$ifile = null;
		if ($request->has('selected_file')) {
			$ifile =  $item->refefile->where('id', $request->selected_file)->first();
		}

		return view('admin.issues.image-update', compact('item', 'ifile'));
	}
	public function updateImages(Request $request)
	{
		$varr = [
			'issue_id' => 'required',
			'file_id' => 'required',
			'base_64_img' => 'required',
		];

		$this->validate($request, $varr, [], trans('issue.label'));

		$item = Issue::where("id", $request->issue_id)->first();
		if (!$item) {
			Session::flash('flash_error', trans('common.responce_msg.data_not_found'));
			return redirect()->back();
		}

		$ifile =  $item->refefile->where('id', $request->file_id)->first();

		if (!$ifile) {
			Session::flash('flash_error', trans('common.responce_msg.data_not_found'));
			return redirect()->back();
		}

		updateBase64($request->base_64_img, $ifile);
		Session::flash('flash_success', trans('common.responce_msg.record_updated_succes'));
		return redirect()->back();
	}

	public function store(Request $request)
	{
		$result = array();
		$varr = [
			'images.*' => 'mimes:jpeg,png,jpg,gif,svg',
			'image' => 'mimes:jpeg,png,jpg,gif,svg',
		];

		$this->validate($request, $varr, [], trans('issue.label'));

		$input = $request->except(['quote_desc', 'other_json_data', 'issue_cat_images', 'other', 'cost_detail_img', 'image', 'images', 'file', 'files', 'survey_id', 'cat_img', 'show_default_price_detail']);
		$image_selection = [];
		if (isset($request->cat_img) && $request->cat_img != "") {
			foreach ($request->cat_img as $p => $valp) {
				$image_selection[] = ["img_id" => $p, "hint" => $request->quote_desc[$p]];
			}
		}

		$input['img_hint'] =  json_encode($image_selection);
		$item = Issue::create($input);

		if ($item) {
			if ($request->hasFile('images')) {
				$files = $request->file('images');
				uploadModalReferenceFile($files, 'uploads/issues/' . $item->id, 'issue_id', $item->id, 'issue_image', []);
			}
			if ($request->hasFile('image')) {
				$files = [$request->file('image')];
				uploadModalReferenceFile($files, 'uploads/issues/' . $item->id, 'issue_id', $item->id, 'issue_image', []);
			}

			$result['message'] = trans('common.responce_msg.record_created_succes');
			$result['code'] = 200;
		} else {
			$result['message'] = trans('common.responce_msg.something_went_wr');
			$result['code'] = 400;
		}

		if ($request->ajax()) {
			return response()->json($result, $result['code']);
		} else {
			Session::flash('flash_message', $result['message']);

			return redirect('admin/items');
		}
	}

	public function show($id, Request $request)
	{
		$item = Issue::where("id", $id)->first();
		if (!$item) {
			Session::flash('flash_error', trans('common.responce_msg.data_not_found'));
			return redirect('admin/items');
		}
		return view('admin.issues.show', compact('item'));
	}

	public function edit($id, Request $request)
	{

		$result = array();
		$item = Issue::findOrFail($id);
		$categories = Categories::select(['id', 'name', 'slug', 'parent_id'])->Parentonly()->orderby('display_order', 'asc')->get();

		if ($item) {
			$result['data'] = $item;
			$result['code'] = 200;
		} else {
			$result['message'] = trans('common.responce_msg.something_went_wr');
			$result['code'] = 400;

			Session::flash('flash_error', trans('common.responce_msg.data_not_found'));
			return redirect('admin/items');
		}

		if ($request->ajax()) {
			return response()->json($result, $result['code']);
		} else {
			return view('admin.issues.edit', compact('item', 'categories'));
		}
	}

	public function update($id, Request $request)
	{
		$result = array();

		$varr = [
			'images.*' => 'mimes:jpeg,png,jpg,gif,svg',
			'image' => 'mimes:jpeg,png,jpg,gif,svg',
		];

		$this->validate($request, $varr, [], trans('issue.label'));

		$item = Issue::where("id", $id)->first();
		$requestData = $request->except(['quote_desc', 'issue_cat_images', 'other', 'cost_detail_img', 'image', 'images', 'file', 'files', 'cat_img', 'show_default_price_detail', 'saveandnext']);

		$image_selection = [];
		if (isset($request->cat_img) && $request->cat_img != "") {

			foreach ($request->cat_img as $p => $valp) {
				$image_selection[] = ["img_id" => $p, "hint" => $request->quote_desc[$p]];
			}
		}
		$requestData['img_hint'] =  json_encode($image_selection);
		if ($item) {
			$item->update($requestData);
			if ($request->hasFile('images')) {
				$files = $request->file('images');
				uploadModalReferenceFile($files, 'uploads/issues/' . $item->id, 'issue_id', $item->id, 'issue_image', []);
			}
			if ($request->hasFile('image')) {
				$files = [$request->file('image')];
				uploadModalReferenceFile($files, 'uploads/issues/' . $item->id, 'issue_id', $item->id, 'issue_image', []);
			}
			$result['message'] = trans('common.responce_msg.record_updated_succes');
			$result['code'] = 200;
		} else {
			$result['message'] = trans('common.responce_msg.something_went_wr');
			$result['code'] = 400;
		}

		if ($request->ajax()) {
			return response()->json($result, $result['code']);
		} else {
			Session::flash('flash_message', $result['message']);
			return redirect('admin/items/');
		}
	}

    public function destroy($id, Request $request)
	{
		$item = Issue::where("id", $id)->first();
		$is_hard_delete = 0;

		if (!$item) {
			$item = Issue::withTrashed()->where('id', $id)->first();
			$is_hard_delete = 1;
		}
		$result = array();

		if ($item) {
			if ($is_hard_delete) {
				foreach ($item->refefile as $rf) {
					removeRefeImage($rf);
				}
				$item->forceDelete();
			}
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
			return redirect('admin/items');
		}
	}
}
