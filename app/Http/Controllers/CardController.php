<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\CardSocialLinks;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
class CardController extends Controller
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
    public function save_card(Request $request){
        info($request->all());
        
        if($request){
            // $isNew = Card::where('user_email',$request->user_email)->first();
            // if(!$isNew){
                if($request->company_logo){
                    $company_logo_contents = file_get_contents($request->company_logo);
                    if($company_logo_contents) $company_logo_name = 'C'.time().'.png';
                }
                if($request->user_photo){
                    $user_photo_contents = file_get_contents($request->user_photo);
                    if($user_photo_contents) $user_photo_name = 'P'.time().'.png';
                }
                $series = self::getCardNumber();
                
                $data                   =       new Card;
                $data->user_email       =       $request->user_email;
                $data->first_name       =       $request->first_name;
                $data->last_name        =       $request->last_name;
                $data->prefix           =       $request->prefix;
                $data->suffix           =       $request->suffix;
                $data->pronouns         =       $request->pronouns;
                $data->company          =       $request->company;
                $data->designation      =       $request->designation;
                $data->summary          =       $request->summary;
                $data->phone            =       $request->phone;
                $data->email            =       $request->email;
                $data->website          =       $request->website;
                $data->company_logo     =       $company_logo_name;
                $data->work_address     =       $request->work_address;
                $data->font_style       =       $request->font_style;
                $data->title_size       =       $request->title_size;
                $data->primary_color    =       $request->primary_color;
                $data->secondary_color  =       $request->secondary_color;
                $data->auto_generated   =       $request->auto_generated ?? "0";
                $data->self_domain      =       $request->self_domain ?? self::get_dn_generated();
                $data->enable_tcs       =       $request->enable_tcs;
                $data->user_photo       =       $user_photo_name;
                $data->card_series      =       $series;
                if($data->save()){
                    //saving social links
                    foreach ($request->social_links as $key => $value) {
                        if($value){
                            $links                   =       new CardSocialLinks;
                            $links->card_series      =       $series;
                            $links->type             =       $key;
                            $links->url              =       $value;
                            $links->save();
                        }
                    }
                    //saving photo and logo
                    $path = storage_path('_userfiles/'.$data->card_series);
                    if(!File::exists($path)) {
                        Storage::disk('user_upload')->makeDirectory($data->card_series);
                    }
                    if($company_logo_name) Storage::disk('user_upload')->put( '/'.$data->card_series.'/'.$company_logo_name,$company_logo_contents);
                    if($user_photo_name)Storage::disk('user_upload')->put( '/'.$data->card_series.'/'.$user_photo_name,$user_photo_contents);
                    return response()->json([
                        'status'=>'success'
                    ],201);
                } else  return response()->json([
                    'status'=>'error'
                ],201); 
            // }else{
            //     $data                   =       Card::where('user_email',$request->user_email)->first();
            //     $data->first_name       =       $request->first_name;
            //     $data->last_name        =       $request->last_name;
            //     $data->prefix           =       $request->prefix;
            //     $data->suffix           =       $request->suffix;
            //     $data->pronouns         =       $request->pronouns;
            //     $data->company          =       $request->company;
            //     $data->designation      =       $request->designation;
            //     $data->summary          =       $request->summary;
            //     $data->phone            =       $request->phone;
            //     $data->email            =       $request->email;
            //     $data->website          =       $request->website;
            //     $data->company_logo     =       $request->company_logo;
            //     $data->work_address     =       $request->work_address;
            //     $data->font_style       =       $request->font_style;
            //     $data->title_size       =       $request->title_size;
            //     $data->primary_color    =       $request->primary_color;
            //     $data->secondary_color  =       $request->secondary_color;
            //     $data->auto_generated   =       $request->auto_generated;
            //     $data->self_domain      =       $request->self_domain;
            //     $data->enable_tcs       =       $request->enable_tcs;
            //     if($data->update()){
            //         return response()->json([
            //             'status'=>'success'
            //         ],201);
            //     } else  return response()->json([
            //         'status'=>'error'
            //     ],201); 
            // }
        }
    }
    public function shared_card($id){
        $data = Card::where('self_domain',$id)
        ->orderby('id','desc')
        ->first();
        if($data){
            info($data);
            return response()->json([
                'status'=>'success',
                'data' => $data
            ],201); 
        }
    }
    public function get_card_list(Request $request){
        info($request);
        $data = Card::from('card as a')
        ->where('user_email',$request->email)
        ->wherenotnull('first_name')
        ->wherenotnull('last_name')
        ->selectRaw('concat(first_name," ",last_name) as name,concat("'.env('BACKEND_URL').'",self_domain) as domain_name,a.*')
        ->orderBy('created_at','desc')
        ->get();
        if($data){
            return response()->json([
                'status'=>'success',
                'data' => $data
            ],201); 
        }
    }
    private function get_dn_generated() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
     
        for ($i = 0; $i < 6; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
     
        return $randomString;
    }
    private function getCardNumber(){
        $card = Card::orderBy('card_series','desc')
        ->first();
        $last_series = $card->card_series ?? '';
        
        $recent_series	= substr($last_series, 5,9 );

         $prefix = 'CN';
        if(str_contains($recent_series,'-')){
            $tick_series = $prefix.'000000';
        }
        else{
            $recent_series = $recent_series + 1;
            $tick_series = $prefix.str_pad($recent_series, 6, '0', STR_PAD_LEFT);
        }
        return $tick_series;
    }
    public function get_card_social_links(Request $request){
        $data = CardSocialLinks::where('card_series',$request->card_series)
        ->selectRaw('type,url')
        ->get();
        if($data){
            return response()->json([
                'status'=>'success',
                'data' => $data
            ],201); 
        }
    }
}
