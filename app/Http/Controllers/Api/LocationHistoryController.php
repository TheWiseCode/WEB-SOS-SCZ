<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Helper;
use App\Models\LocationHistory;
use Illuminate\Http\Request;

class LocationHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(LocationHistory::all(),200);
    }

    public function getHistoryByHelper(Request $request){
        $request->validate([
            'helper_id' => 'required'
        ]);

        $data = LocationHistory::where('helper_id','=', $request->helper_id)->get();
        return response($data,200);
    }

    public function getHistoryByDate(Request $request){
        $request->validate([
            'date' => 'required'
        ]);

        $data = LocationHistory::where('date','=', $request->date)->get();
        return response($data,200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'longitude'=> 'required',
            'latitude'=> 'required',
            'helper_id'=> 'required',
            'date'=> 'required',
            'time'=> 'required',
        ]);

        $helper = Helper::where('id','=',$request->helper_id)->get();

        if(!$helper){
            abort('404','Some object not found');
        }

        LocationHistory::create($request->all());
        return response(201);
    }

}
