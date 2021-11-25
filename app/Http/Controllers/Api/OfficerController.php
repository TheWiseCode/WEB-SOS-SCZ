<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfficerRequest;
use App\Models\institution;
use App\Models\Officer;
use App\Models\Position;
use http\Client\Curl\User;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Officer::with('User','Position','Institution')->get();
    }

    /**Display all the officer's work shifts he has been on.
     * @param $id
     */
    public function getWorkShifts($id){
        $officer = Officer::with('Work_shift')->find($id);
        /*if(!$officer){
            abort(404,' Object not found ');
        }*/
        return $officer;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOfficerRequest $request)
    {
        $user = \App\Models\User::find($request->user_id);
        $position = Position::find($request->position_id);
        $institution =  institution::find($request->institution_id);
        if( !$user || !$position || !$institution){
            abort(404,'object not found');
        }
        return Officer::create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $officer = Officer::with('User','Position','Institution')->find($id);
        if(!$officer){
            abort(404,'Object not found');
        }
        return $officer;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreOfficerRequest $request,Officer $officer)
    {
        $user = \App\Models\User::find($request->user_id);
        $position = Position::find($request->position_id);
        $institution =  institution::find($request->institution_id);
        if( !$user || !$position || !$institution){
            abort(404,'object not found');
        }
        $officer->update($request->validated());
        return $officer;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Officer $officer)
    {
        $officer->delete();
        return response()->noContent();
    }
}
