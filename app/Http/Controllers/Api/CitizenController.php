<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCitizenRequest;
use App\Models\Cityzen;
use http\Client\Curl\User;
use Illuminate\Http\Request;

class CitizenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cityzen::with('User')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCitizenRequest $request)
    {
        return Cityzen::create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $citizen = Cityzen::with('User')->find($id);
        if(!$citizen){
            abort(404,'Object not found');
        }
        return $citizen;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCitizenRequest $request,Cityzen $cityzen)
    {
        //TODO: I think we dont really need this method
        $user = User::find($request->user_id);
        if(!$user){
           abort(404,'Object not found');
        }
        $cityzen->update($request->validated());
        return $cityzen;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cityzen $cityzen)
    {
        $cityzen->delete();
        return response()->noContent();
    }
}
