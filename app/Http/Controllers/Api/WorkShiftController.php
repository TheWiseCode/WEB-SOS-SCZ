<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkShiftRequest;
use App\Models\Officer;
use App\Models\Schedule;
use App\Models\Vehicle;
use App\Models\Work_shift;
use Illuminate\Http\Request;

class WorkShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Work_shift::with('Vehicle','Officer','Schedule')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWorkShiftRequest $request)
    {
        $vehicle = Vehicle::find($request->vehicle_id);
        $officer = Officer::find($request->officer_id);
        $schedule = Schedule::find($request->schedule_id);
        if(!$vehicle || !$officer || !$schedule){
            abort(404,'Some object has not been found');
        }
        return Work_shift::create($request->validated());
    }

    /** Display all the coordinates where the officer was in a
     * specific work shift
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getLocations($id){
        $location = Work_shift::with('WorkShift_location')
            ->orderBy('date_time')
            ->find($id);
        return $location;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $workshift = Work_shift::with('Officer','Vehicle','Schedule')->find($id);
        if(!$workshift){
            abort(404,'Object not found');
        }
        return $workshift;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreWorkShiftRequest $request,Work_shift $work_shift)
    {
        $vehicle = Vehicle::find($request->vehicle_id);
        $officer = Officer::find($request->officer_id);
        $schedule = Schedule::find($request->schedule_id);
        if(!$vehicle || !$officer || !$schedule){
            abort(404,'Some object has not been found');
        }
        $work_shift->update($request->validated());
        return $work_shift;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Work_shift $work_shift)
    {
        $work_shift->delete();
        return response()->noContent();
    }
}
