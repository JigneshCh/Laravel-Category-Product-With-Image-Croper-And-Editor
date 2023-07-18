<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;

use Illuminate\Http\Request;
use Session;

use Illuminate\Support\Facades\File;

use DataTables;

class UsersController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function userDatatable(Request $request)
    {
        $record = User::with('roles')->orderBy('utype', 'asc');
        return Datatables::of($record)->make(true);
    }

    public function index(Request $request)
    {
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $roles = Role::pluck('label', 'name');
        $selected_role = [];
        return view('admin.users.create', compact('roles', 'selected_role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request)
    {
        $rules = array(
            'first_name' => 'required|min:2|max:150',
            'last_name' => 'required|min:2|max:150',
            'email' => 'required|email|unique:users|min:2|max:150',
            'password' => 'required|min:6|max:25',
            'roles' => 'required',
        );

        $this->validate($request, $rules, [], trans('user.label'));

        $alldata = $request->all();

        $data = $request->except('password', 'enable_password', 'qualification', 'experience');
        $data['password'] = bcrypt($request->password);
        $data['utype'] = "employee";
        $data['qualification'] = json_encode($request->qualification);
        $data['experience'] = json_encode($request->experience);

        foreach ($request->roles as $role) {
            if ($role == "AU") {
                $data['utype'] = "admin";
            }
            if ($role == "EU") {
                $data['utype'] = "employee";
            }
        }

        $user = User::create($data);

        if ($user && $request->has('signature_base64') && $request->signature_base64 != "") {
            if ($user->signature) {
                removeRefeImage($user->signature);
            }
            uploadBase64($request->signature_base64, 'uploads/users/' . $user->id, 'user_id', $user->id, 'signature_image', []);
        }

        foreach ($request->roles as $role) {
            $user->assignRole($role);
        }

        $subject = \config('app.name') . " " . \Lang::get('email.subject.surveyour_account_detail', [], 'he');
        Session::flash('flash_success', trans('common.responce_msg.record_created_succes'));


        return redirect('admin/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return void
     */
    public function show($id)
    {

        $user = User::where("id", $id)->first();

        if (!$user) {
            Session::flash('flash_error', trans('common.responce_msg.data_not_found'));
            return redirect()->back();
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return void
     */
    public function edit($id)
    {
        $user = User::where("id", $id)->first();
        $roles = Role::pluck('label', 'name');

        if (!$user) {
            Session::flash('flash_error', trans('common.responce_msg.data_not_found'));
            return redirect()->back();
        }

        $selected_role = $user->roles->pluck('name');

        return view('admin.users.edit', compact('user', 'roles', 'selected_role'));
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
        $this->validate(
            $request,
            [
                'first_name' => 'required|min:2|max:150',
                'last_name' => 'required|min:2|max:150',
                'password' => 'max:25',
                'roles' => 'required'
            ],
            [],
            trans('user.label')
        );

        $data = $request->except('password', 'email', 'enable_password', 'qualification', 'experience');

        if ($request->has('enable_password') && $request->get('enable_password') == "1" && $request->has('password') && $request->get('password') != "") {
            $data['password'] = bcrypt($request->password);
        }
        $data['qualification'] = json_encode($request->qualification);
        $data['experience'] = json_encode($request->experience);

        foreach ($request->roles as $role) {
            if ($role == "AU") {
                $data['utype'] = "admin";
            }
            if ($role == "EU") {
                $data['utype'] = "employee";
            }
        }

        $user = User::where("id", $id)->where("id", "!=", 1)->first();
        if (!$user) {
            Session::flash('flash_error', trans('common.responce_msg.data_not_found'));
            return redirect()->back();
        }

        if ($user) {
            $user->update($data);

            if ($request->has('signature_base64') && $request->signature_base64 != "") {
                if ($user->signature) {
                    removeRefeImage($user->signature);
                }
                uploadBase64($request->signature_base64, 'uploads/users/' . $user->id, 'user_id', $user->id, 'signature_image', []);
            }
            $user->save();
        }

        $user->roles()->detach();
        foreach ($request->roles as $role) {
            $user->assignRole($role);
        }

        Session::flash('flash_success', trans('common.responce_msg.record_updated_succes'));

        return redirect('admin/users');
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
        $result = array();
        $ob = User::where("id", $id)->where("id", "!=", \Auth::user()->id)->where("id",'>', 2)->first();

        if ($ob) {
            if ($ob->signature) {
                removeRefeImage($ob->signature);
            }

            $ob->roles()->detach();
            $ob->delete();

            $result['message'] = \Lang::get('common.responce_msg.record_deleted_succes');;
            $result['code'] = 200;
        } else {
            $result['message'] = "You have no permission to delete this users!";
            $result['code'] = 400;
        }


        if ($request->ajax()) {
            return response()->json($result, $result['code']);
        } else {
            Session::flash('flash_message', $result['message']);
            return redirect('admin/users');
        }
    }


    public function search(Request $request)
    {
        $result = array();

        $data = User::select(["users.*"]);

        if ($request->has('filter_user') &&  $request->get('filter_user') != '') {
            $data->where('first_name', 'LIKE', "%$request->filter_user%")->orWhere('last_name', 'LIKE', "%$request->filter_user%");
        }

        $res = $data->get()->toArray();
        array_unshift($res, ["id" => 0, "full_name" => \Lang::get('user.label.all_surveyor')]);

        $result['data'] = $res;
        $result['code'] = 200;

        return response()->json($result, $result['code']);
    }



    public function uploadPhoto(Request $request)
    {
        if ($request->hasFile('photo')) {

            $file = $request->file('photo');
            $timestamp = str_replace([' ', ':'], '-', \Carbon\Carbon::now()->toDateTimeString() . uniqid());

            $name = $timestamp . '-' . $file->getClientOriginalName();
            $file->move(public_path() . '/uploads/', $name);

            return $name;
        } else {

            return null;
        }
    }
    public function removeImage($imageName)
    {
        $image_path1 = public_path() . "/uploads/" . $imageName;

        if ($imageName && $imageName != "" && File::exists($image_path1)) {
            unlink($image_path1);
        }
    }
}
