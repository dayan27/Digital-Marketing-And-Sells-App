<?php

namespace App\Http\Controllers;

use App\Http\Resources\SystemUserResource;
use App\Models\Account;
use App\Models\Manager;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SystemUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SystemUserResource::collection(Manager::where('type','system_user')->with('roles')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $account=Account::create(['user_name'=>$request->email,'password'=>Hash::make($request->last_name.'1234') ]);
        $data=$request->all();
        $data['account_id']=$account->id;
        $data['type']='system_user';
        $manager= Manager::create($data);
        $manager->sendEmailVerificationNotification();

        if($manager){
        $phone_numbers=[];
        $phoneNumbers=$request->phone_numbers;
        //return $phoneNumbers;
        foreach($phoneNumbers as $phoneNumber ){
           $phoneNum=new PhoneNumber();
           $phoneNum->phone_number=$phoneNumber;
           $phoneNum->manager_id=$manager->id;

           $phoneNum->save();
        //    $phone_number['id']=$phoneNum->id;
        //    $phone_number['phone_number']=$phoneNum->phone_number;
           $phone_numbers[]=$phoneNum->phone_number;

        }
       $manager->phone_numbers=$phone_numbers;

       }


       return $manager;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        $manager=Manager::find($id);
        $manager->update($request->all());
        $phoneNum=new PhoneNumber();

        if($manager){
          $manager->phone_numbers()->delete();
          $phoneNumber=$request->phone_number;
          $phoneNum=new PhoneNumber();
             $phoneNum->phone_number=$request->phone_number;
             $phoneNum->manager_id=$manager->id;
             $phoneNum->save();
             
             $manager['phone_numbers']=[$phoneNum->phone_number];

          }
  
           return $manager;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    //    $phone_numbers= $manager->phone_numbers;
    //    foreach(){
        $manager=Manager::find($id);
        $manager->phone_numbers()->delete();
        $manager->delete();
        return response()->json('sucessfully deleted',200);
    }

    public function assignRoleToEmployee(Request $request,$id){
        $user=Manager::find($id);
          $user->syncRoles($request->role_id);
          return Role::find($request->role_id)->name;

    }

    public function changeUserStatus($user_id){
        $user=Manager::find($user_id);
        $user->is_active=request()->status;
        $user->save();
        return response()->json('sucessfuly changed',200);

    }
}
