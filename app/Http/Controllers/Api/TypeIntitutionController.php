<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTypeInstitutionRequest;
use App\Models\type_institution;
use Illuminate\Http\Request;

class TypeIntitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return type_institution[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function index()
    {
        return type_institution::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTypeInstitutionRequest $request)
    {
        return type_institution::create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        $type_institution = type_institution::find($id);
        if(!$type_institution){
            abort(404,'Type Institution not found');
        }
        return $type_institution;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTypeInstitutionRequest $request,type_institution $type_institution)
    {
        $type_institution->update($request->validated());
        return $type_institution;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(type_institution $type_institution)
    {
        $type_institution->delete();

        return response()->noContent();
    }
}
