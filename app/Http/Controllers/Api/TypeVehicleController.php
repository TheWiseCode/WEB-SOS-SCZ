<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTypeVehicleRequest;
use App\Models\Type_vehicle;
use Illuminate\Http\Request;

class TypeVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Type_vehicle::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTypeVehicleRequest $request)
    {
        return Type_vehicle::create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $type = Type_vehicle::find($id);
        if(!$type){
            abort(404,'Type of vehicle not found');
        }
        return $type;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTypeVehicleRequest $request,Type_vehicle $type_vehicle)
    {
         $type_vehicle->update($request->validated());
        return $type_vehicle;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type_vehicle $type_vehicle)
    {
        $type_vehicle->delete();
        return response()->noContent();
    }
}
