<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
        //
    }
    public function register_user(Request $request){
        if($request->all()){
           $first_name = $request->first_name;
           $last_name = $request->last_name;
           $email_address = $request->email_address;
           $password = $request->password;
           if(!self::is_email_exist($email_address)){
                $data               = new User;
                $data->name         = $first_name.' '.$last_name;
                $data->email        = $email_address;
                $data->password     = bcrypt($password);
                if($data->save()){
                    return response()->json([
                        'message'=> 'Account registration is successful. Redirecting now...',
                        'status' => 'success'
                       ],201);
                }
           }
           else return response()->json([
            'message'=> 'Email address already exist.',
            'status'=> 'error'
           ],201);
        }
    }
    public function login (Request $request){
        $email = $request->email_address;
        $password = $request->password;
        $data = User::where('email',$email)
        ->first();
        
      
        if($data && Hash::check($password,$data->password)){
            info(json_encode($data));
            return response()->json([
                'message'=> 'login success',
                'status' => 'success',
                'data' => $data,
               ],201);
        }
        else return response()->json([
            'message'=> 'Account does not exist.',
            'status'=> 'error'
           ],201);
    }
    public function is_email_exist($email){
        $check = User::where('email',$email)->first();
        if($check) return true;
        else return false;
    }
}
