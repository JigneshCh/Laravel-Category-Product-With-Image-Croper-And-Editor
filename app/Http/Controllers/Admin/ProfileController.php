<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Hash;
use Illuminate\Support\Facades\Lang;
use Session;

class ProfileController extends Controller
{


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        return view('admin.profile.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * @param Request $request
     */
    public function update(Request $request)
    {
        $normal = [
            'first_name' => 'required',
            'last_name' => 'required',
            'language' => 'required',
        ];

        $user = Auth::user();
        $this->validate($request, $normal, [], trans('user.label'));
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->language = $request->language;

        if ($user->save()) {
        }
        Session::flash('flash_success', trans('common.responce_msg.record_updated_succes'));

        return redirect()->back();
    }


    //
    /**
     * @param Request $request
     * @return null|string
     */
    public function uploadPhoto(Request $request)
    {
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changePassword()
    {
        return view('admin.profile.changePassword');
    }

    /**
     * @param Request $request
     */
    public function updatePassword(Request $request)
    {
        $this->validate(
            $request,
            [
                'current_password' => 'required',
                'password' => 'required|min:6|max:255',
                'password_confirmation' => 'required|same:password',
            ],
            [],
            trans('user.label')
        );


        $cur_password = $request->input('current_password');


        $user = Auth::user();

        if (Hash::check($cur_password, $user->password)) {

            $user->password = Hash::make($request->input('password'));
            $user->save();

            Session::flash('flash_success', trans('user.responce_msg.password_changed_success'));


            return redirect()->route('profile.index');
        } else {
            $error = array('current-password' => trans('user.responce_msg.password_not_match_with_current'));
            return redirect()->back()->withErrors($error);
        }

        Session::flash('flash_success', trans('common.responce_msg.something_went_wr'));

        return redirect()->back();
    }
}
