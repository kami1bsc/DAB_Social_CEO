<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;

class AllUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('email', '!=', 'admin@admin.com')
        ->where('is_profile_verified', 'false')
        ->orderBy('created_at', 'desc')->paginate(12);
        return view('admin.all_users.user_requests', compact('users'));
    }

    public function verified_users()
    {
        $users = User::where('email', '!=', 'admin@admin.com')
        ->where('is_profile_verified', 'true')
        ->orderBy('created_at', 'desc')->paginate(12);
        return view('admin.all_users.verified_users', compact('users'));
    }

    public function verify_user($user_id)
    {
        $user = User::find($user_id);
        $user->is_profile_verified = 'true';
        if($user->save())
        {
            return back()->with('message', 'User Profile Verified!');
        }else{
            return back()->with('error', 'There is some trouble to proceed your action!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('admin.all_users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            User::find($id)->delete();
            return back()->with('message', 'User Deleted Successfully!');
        }catch(\Exception $e)
        {
            return back()->with('error', 'There is some trouble to proceed your action!');
        }
    }
}
