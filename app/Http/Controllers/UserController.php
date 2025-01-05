<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Users;
use App\Models\User;
use App\Models\Feedback;
use App\Models\News;
use Auth;

class UserController extends Controller
{
    public function edit(){
        if(Auth::check()){
            return view('user.edit')->with('user',auth()->user());
        }else{
            return back();
        }
    }
    public function update(){
        $r=request();
        $user = auth()->user();
        $user->name=$r->username;
        $user->save();
        return view('/welcome');
    }
    public function showUser(){
        $users = DB::table('users')
        // ->where('role_as','=','0')
        // ->latest()
         ->get();
        return view('userList')->with('users',$users);
    }
    public function showAdmin(){
        $admins = DB::table('users');
        // ->where('role_as','=','1')
        // ->latest()
        // ->get();
        return view('adminList')->with('admins',$admins);
    }
    public function deleteUser($id){
        $user=User::find($id);
        $user->delete();
        Session::flash('success',"user delete successfully!");
        return redirect()->route('userList');
    }
    public function deleteAdmin($id){
        $admin=User::find($id);
        $admin->delete();
        Session::flash('success',"admin create successfully!");
        return redirect()->route('userList');
    }

    // search user
    public function userSearch(){
        $r=request();
        $keyword=$r->keyword;
        $users = DB::table('users')
        // ->where('role_as','=','0')
        ->where('users.name','like','%'.$keyword.'%')      
        ->latest()
        ->get();
        return view('userList')->with('users',$users);
    }
    // search admin
    // public function adminSearch(){
    //     $r=request();
    //     $keyword=$r->keyword;
    //     $admins = DB::table('users')
    //     ->where('role_as','=','1')
    //     ->where('users.name','like','%'.$keyword.'%')    
    //     ->latest()
    //     ->get();
    //     return view('admin.adminList')->with('admins',$admins);
    // }

    public function editProfile($id) {
        $users=User::all()->where('id',$id);
        return view('editProfileInfo')->with('users',$users);

    }

    public function updateProfile(){
        $r=request();//retrive submited form data
        $user =User::find($r->userID);  //get the record based on income ID      
        if($r->file('image')!=''){
            $image=$r->file('image');        
            $image->move('images',$image->getClientOriginalName());                   
            $imageName=$image->getClientOriginalName(); 
            $user->image=$imageName;
        }         
        $user->name=$r->Uname;
        $user->email=$r->Uemail;
        $user->save();
        return redirect()->route('todolistprofile');
    }

    public function index(): JsonResponse
    {
        $users = User::where('id', '!=', auth()->user()->id)->get();
        return $this->success($users);
    }
}
