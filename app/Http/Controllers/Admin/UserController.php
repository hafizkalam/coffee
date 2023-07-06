<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterTenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show(){
        $data['tenant'] = MasterTenant::whereNotNull('name_menu')->get();
        // $data['tenant1'] = MasterTenant::select('name_tenant)->groupBy('name_tenant')->get();
        $data['data'] = User::get();

        // echo "<pre>";
        // print_r($data['tenant1']);
        // dd($data['tenant1']);
        // exit;

        return view('admin.user', $data);
    }

    public function createedit(Request $request)
    {
        // if ($request->hasFile('profile')) {

        //     $file = Request()->profile;
        //     $fileName = Request()->name . time().'.' . $file->extension();
        //     $file->move(public_path('profile_users'), $fileName);
        // }
        // dd(public_path().'/profile_users');

        // dd($fileName);
        // exit;
        $test = Auth::user();
        // $nama = $test->name;

        $vaUpdate = array(
            "id" => $request->id,
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make("123"),
            "level" => "2",
            // "profile" => $fileName,
            "desc" => $request->desc,
        );
        // dd($vaUpdate);

        $vaTenant = array(
            "name_tenant" => $request->name
        );
        // dd($vaUpdate);

        if ($request->hasFile('profile')) {
            // $path = $request->file('url')->store('user');
            $file = Request()->profile;
            $fileName = Request()->name . time().'.' . $file->extension();
            $file->move(public_path('profile_users'), $fileName);
            $vaUpdate['profile'] = $fileName;
        }
        if ($request->has('edit')) {
            User::where('id', $request->id)->update($vaUpdate);
        } else {
            User::create($vaUpdate);
            MasterTenant::create($vaTenant);
            $request->session()->put('notif', "Data berhasil ditambahkan");
        }

        return redirect('user');
    }

    public function destory($id,$name)
    {
        User::where('id', $id)->delete();
        MasterTenant::where('name_tenant', $name)->delete();
        return redirect('user');
    }
}
