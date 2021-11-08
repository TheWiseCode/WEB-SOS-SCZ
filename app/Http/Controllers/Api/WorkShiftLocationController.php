<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkShiftLocationRequest;
use App\Models\Work_shift;
use App\Models\WorkShift_location;
use Illuminate\Http\Request;

class WorkShiftLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return WorkShiftLocationController::with('Work_shift')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWorkShiftLocationRequest $request)
    {
        $shift =  Work_shift::find($request->work_shift_id);
        if(!$shift){
            abort(404,'Object not found');
        }
        return WorkShift_location::create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locations = WorkShift_location::find($id);
        if(!$locations){
            abort(404,'Object not found');
        }
        return $locations;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreWorkShiftLocationRequest $request,WorkShift_location $workShift_location)
    {
        $shift =  Work_shift::find($request->work_shift_id);
        if(!$shift){
            abort(404,'Object not found');
        }
        $workShift_location->update($request->validated());
        return $workShift_location;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkShift_location $workShift_location)
    {
        $workShift_location->delete();
        return response()->noContent();
    }
}
