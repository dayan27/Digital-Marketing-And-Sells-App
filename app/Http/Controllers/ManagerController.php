<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManagerResource;
use App\Models\Account;
use App\Models\Manager;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page=request()->per_page;
        $query= Manager::query()->where('type','agent');
      
        $query=$query->when(request('search'),function($query){

            $query->where('first_name','LIKE','%'.request('search').'%')
            ->orWhere('last_name','LIKE','%'.request('search').'%')
            ->orWhere('first_name','LIKE','%'.request('search'))
            ->orWhere('last_name','LIKE'.request('search').'%');
            //->orWhere('phone_number','LIKE','%'.request('search').'%');
                //  ->orWhere('products.model','LIKE','%'.request('search').'%');
            });
        return ManagerResource::collection($query->paginate($per_page));



      //return ManagerResource::collection($manager);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $account=Account::create(['user_name'=>$request->email,'password'=>Hash::make($request->last_name.'1234') ]);
        $data=$request->all();
        $data['account_id']=$account->id;
        $manager= Manager::create($data);
       if($manager){
        $phone_numbers=[];
        $phoneNumbers=$request->phone_numbers;
        //return $phoneNumbers;
        foreach($phoneNumbers as $phoneNumber ){
           $phoneNum=new PhoneNumber();
           $phoneNum->phone_number=$phoneNumber;
           $phoneNum->manager_id=$manager->id;

           $phoneNum->save();
           $phone_number['id']=$phoneNum->id;
           $phone_number['phone_number']=$phoneNum->phone_number;
           $phone_numbers[]=$phone_number;

        }
       $manager['phone_numbers']=$phone_numbers;

       }

      // $manager->sendEmailVerificationNotification();

       return $manager;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function show(Manager $manager)
    {
        $manager->load('phone_numbers');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Manager $manager)
    {
      $manager->update($request->all());
      if($manager){
        $phone_numbers=[];
        $manager->phone_numbers()->delete();
        $phoneNumbers=$request->phone_numbers;
        $phoneNum=new PhoneNumber();
        foreach($phoneNumbers as $phoneNumber ){
           $phoneNum->phone_number=$phoneNumber;
           $phoneNum->manager_id=$manager->id;
           $phoneNum->save();
           $phoneNum->refresh();
           $phone_number['phone_number']=$phoneNum->phone_number;
           $phone_numbers[]=$phone_number;

        }

         $manager['phone_numbers']=$phone_numbers;

      }
         //return $manager;
         return new ManagerResource($manager->load('phone_numbers'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manager $manager)
    {
    //    $phone_numbers= $manager->phone_numbers;
    //    foreach(){

        $manager->phone_numbers()->delete();
        $manager->account()->delete();
        $manager->delete();
        return response()->json('sucessfully deleted',200);
    }

/**
 * assign role to a certain employee
 */



}
