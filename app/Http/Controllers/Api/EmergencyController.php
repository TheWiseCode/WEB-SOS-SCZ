<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmergencyRequest;
use App\Models\Cityzen;
use App\Models\Emergency;
use App\Models\type_institution;
use Illuminate\Http\Request;
use Illuminate\Session\Store;

class EmergencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Emergency::with('Citizen','Type_institution')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmergencyRequest $request)
    {
        $citizen = Cityzen::find($request->citizen_id);
        $type = type_institution::find($request->type_institution_id);
        if( !$citizen || !$type){
            abort(404,'Some object not found');
        }
        return Emergency::create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id )
    {
        $emergency = Emergency::find($id);
        if(!$emergency){
            abort(404,'Object not found');
        }
        return $emergency;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreEmergencyRequest $request, Emergency $emergency)
    {
        //TODO: MAKE CITIZEN_ID UNCHANGEABLE. HOW TO DO IT?
        $type = type_institution::find($request->type_institution_id);
        if( !$type){
            abort(404,'Some object not found');
        }
        $emergency->update($request->validated());
        return $emergency;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Emergency $emergency)
    {
        $emergency->delete();
        return response()->noContent();
    }
}
