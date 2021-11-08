<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInstitutionRequest;
use App\Models\institution;
use App\Models\type_institution;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return institution::with('Type_institution')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInstitutionRequest $request)
    {
        $type = type_institution::find($request->type_institution_id);
        if(!$type){
            abort(404,'Object not found');
        }
        return institution::create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inst = institution::find($id);
        if(!$inst){
            abort(404,'Object not found');
        }
        return $inst;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreInstitutionRequest $request, institution $institution)
    {
        $type = type_institution::find($request->type_institution_id);
        if(!$type){
            abort(404,'Object not found');
        }
        $institution->update($request->validated());
        return $institution;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(institution $institution)
    {
        $institution->delete();
        return response()->noContent();
    }

    Public function InstPositions(){
        return institution::with('Position')->get();
    }
}
