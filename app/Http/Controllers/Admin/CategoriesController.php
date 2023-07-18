<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Categories;
use App\Issue;

use Illuminate\Http\Request;
use Session;

class CategoriesController extends Controller
{
    public function export(Request $request)
    {
        $items = Categories::where("parent_id", 0)->where("is_hidden", "!=", 1)->orderby('display_order', 'asc');
        if ($request->has('category_id') && $request->category_id != "") {
            $items->where("id", $request->category_id);
        }
        $items = $items->get();
        return view('admin.categories.export', compact('items'));
    }
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 15;

        if (!empty($keyword)) {
            $items = Categories::where("is_hidden", "!=", 1)->where('name', 'LIKE', "%$keyword%")->orWhere('label', 'LIKE', "%$keyword%")
                ->where("parent_id", 0)->get();
        } else {
            $items = Categories::where("parent_id", 0)->where("is_hidden", "!=", 1)->orderby('display_order', 'asc')->get();
        }
        return view('admin.categories.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create(Request $request)
    {
        $items = Categories::select()->parentonly()->pluck('name', 'id')->prepend(trans('categories.label.no_parent'), 0);

        return view('admin.categories.create', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request)
    {

        $result = array();

        $niceNames = trans('categories.label');
        $niceNames['name'] = trans('common.label.name');

        $rules = ['name' => 'required|min:2|max:150|unique:categories', 'display_order' => 'required'];

        $counts = 0;
        if ($request->has('parent_id') && $request->parent_id != 0) {
            $requestData = $request->except(['image', 'images', 'file', 'files', 'quote_desc']);
            $counts = Categories::where("parent_id", $request->parent_id)->where("display_order", $request->display_order)->count();
        } else {
            $requestData = $request->except(['issue_detail', 'recommendation', 'quote', 'image', 'images', 'file', 'files', 'quote_desc']);
            $counts = Categories::where("parent_id", 0)->where("display_order", $request->display_order)->count();
        }
        if ($counts > 0) {
            $rules['display_order'] = 'required|unique:categories';
        }

        $this->validate($request, $rules, [], $niceNames);
        $requestData['created_by'] = \Auth::user()->id;
        $requestData['updated_by'] = \Auth::user()->id;
        $requestData['slug'] = str_slug($requestData['name']);

        $module = Categories::create($requestData);

        if ($module) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');
                uploadModalReferenceFile($files, 'uploads/category/' . $module->id, 'cat_id', $module->id, 'cat_image', []);
            }
            if ($request->hasFile('image')) {
                $files = [$request->file('image')];
                uploadModalReferenceFile($files, 'uploads/category/' . $module->id, 'cat_id', $module->id, 'cat_image', []);
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
            return redirect('admin/categories');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return void
     */

    public function show($id, Request $request)
    {
        $item = Categories::findOrFail($id);

        return view('admin.categories.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return void
     */
    public function edit($id, Request $request)
    {
        $item = Categories::findOrFail($id);

        if (!$item) {
            Session::flash('flash_error', trans('common.responce_msg.data_not_found'));
            return redirect('admin/categories');
        }

        if ($item->parent_id == 0) {
            $items = Categories::select()->parentonly()->where('id', 0)->pluck('name', 'id')->prepend(trans('categories.label.no_parent'), 0);
        } else {
            $items = Categories::select()->parentonly()->pluck('name', 'id')->prepend(trans('categories.label.no_parent'), 0);
        }

        return view('admin.categories.edit', compact('item', 'items'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function update($id, Request $request)
    {
        $niceNames = trans('categories.label');
        $niceNames['name'] = trans('common.label.name');

        $rules = ['name' => 'required|min:2|max:150|unique:categories,name,' . $id, 'display_order' => 'required'];

        $cnt = 0;

        if ($request->has('parent_id') && $request->parent_id != 0) {
            $requestData = $request->except(['image', 'images', 'file', 'files', 'quote_desc']);
            $cnt = Categories::where("id", "!=", $id)->where("parent_id", $request->parent_id)->where("display_order", $request->display_order)->count();
        } else {

            $requestData = $request->except(['issue_detail', 'recommendation', 'quote', 'image', 'images', 'file', 'files', 'quote_desc']);
            $requestData['issue_detail'] = "";
            $requestData['recommendation'] = "";
            $requestData['quote'] = "";
            $cnt = Categories::where("id", "!=", $id)->where("parent_id", 0)->where("display_order", $request->display_order)->count();
        }
        if ($cnt > 0) {
            $rules['display_order'] = 'required|unique:categories';
        }

        $this->validate($request, $rules, [], $niceNames);
        $item = Categories::findOrFail($id);
        if ($request->has('quote_desc') && $request->quote_desc) {
            $requestData['quote_desc'] =  json_encode($request->quote_desc);
        }

        if ($item) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');
                uploadModalReferenceFile($files, 'uploads/category/' . $item->id, 'cat_id', $item->id, 'cat_image', []);
            }
            if ($request->hasFile('image')) {
                $files = [$request->file('image')];
                uploadModalReferenceFile($files, 'uploads/category/' . $item->id, 'cat_id', $item->id, 'cat_image', []);
            }
            $item->update($requestData);

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
            if ($item->next() && $result['code'] == 200) {
                return redirect('admin/categories/' . $item->next()->id . "/edit");
            } else {
                $url = url('admin/categories?#category_' . $item->id);
                return redirect($url);
            }
            return redirect('admin/categories');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return void
     */
    public function destroy($id, Request $request)
    {
        $item = Categories::where("id", $id)->first();

        $result = array();

        if ($item->issue->count() || $item->child->count()) {
            $item->is_hidden = 1;
            $item->save();
            Session::flash('flash_warning', "This item now hidden,as it contain child data");
            return redirect('admin/categories');
        }

        if ($item) {
            $item->is_hidden = 1;
            $item->save();
            //$item->delete();
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
            return redirect('admin/categories');
        }
    }

    public function catImgView(Request $request)
    {
        $result = array();

        $cat = null;
        $issue = null;
        $selected_img = [];
        $img_hints = [];

        if ($request->has('category_id') && is_numeric($request->category_id) && $request->get('category_id') != '') {
            $cat = Categories::where("id", $request->category_id)->first();
        }
        if ($request->has('issue_id') &&  $request->get('issue_id') != '') {
            $issue = Issue::where("id", $request->issue_id)->first();
            if ($issue && $issue->img_hint && $issue->img_hint != "") {
                $img_hint = json_decode($issue->img_hint, true);
                foreach ($img_hint as $z => $hint) {
                    if (isset($hint['img_id'])) {
                        $selected_img[] = $hint['img_id'];
                        $img_hints[$hint['img_id']] = $hint['hint'];
                    }
                }
            }
        }

        $html = view('admin.categories.cat-img', compact('cat', 'issue', 'selected_img', 'img_hints'))->render();

        $result['data'] = $cat;
        $result['html'] = $html;
        $result['code'] = 200;

        return response()->json($result, $result['code']);
    }

    public function search(Request $request)
    {
        $result = array();

        $data = Categories::select("*")->where("is_hidden", "!=", 1);

        if ($request->has('search') &&  $request->get('search') != '') {
            $data->where('name', 'LIKE', "%$request->search%");
        }
        if ($request->has('parent_id') &&  $request->get('parent_id') != '') {
            $data->where('parent_id', $request->parent_id);
        }
        $res = $data->get();

        $result['data'] = $res;
        $result['code'] = 200;

        return response()->json($result, $result['code']);
    }
}
