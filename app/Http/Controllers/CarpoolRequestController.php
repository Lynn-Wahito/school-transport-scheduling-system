<?php

namespace App\Http\Controllers;

use App\Models\CarpoolDriver;
use Illuminate\Http\Request;
use App\Models\CarpoolRequest;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CarpoolRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carpoolRequests = CarpoolRequest::paginate(10);
        return view('carpool_requests.index', compact('carpoolRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $carpoolDrivers = CarpoolDriver::where('availability_status', 'Available')
        ->get(['id', 'first_name', 'last_name', 'availability_status']);

        return view('carpool_requests.create', compact('carpoolDrivers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request...
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:60',
            'description' => 'required|string|max:255',
            'carpool_driver_id' => 'required|integer',
            'departure_date' => 'required|date|before:2024-12-31|after_or_equal:'.Carbon::now()->format('Y-m-d'),
            'departure_time' => 'required',
            'departure_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'no_of_people' => 'required|integer|between:1,200',
        ]);

        if($validator->fails()){
            return redirect('carpool_requests/create')
                ->withErrors($validator->errors())
                ->withInput();
        }else{
            $input = [
                'title' => $request->title,
                'description' => $request->description,
                'carpool_driver_id' => $request->carpool_driver_id,
                'departure_date' => $request->departure_date,
                'departure_time' => $request->departure_time,
                'departure_location' => $request->departure_location,
                'destination' => $request->destination,
                'no_of_people' => $request->no_of_people,
                'status' => 'Pending',
            ];

            CarpoolRequest::create($input);

            return redirect('carpool_requests')->with('success', 'Carpool Request created successfully.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $carpoolRequest = CarpoolRequest::find($id);
        return view('carpool_requests.show', compact('carpoolRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $carpoolRequest = CarpoolRequest::find($id);
        $carpoolDrivers = CarpoolDriver::where('availability_status', 'Available')
        ->get(['id', 'first_name', 'last_name', 'availability_status']);

        return view('carpool_requests.edit', compact('carpoolRequest', 'carpoolDrivers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request...
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:60',
            'description' => 'required|string|max:255',
            'carpool_driver_id' => 'required|integer',
            'departure_date' => 'required|date|before:2024-12-31|after_or_equal:'.Carbon::now()->format('Y-m-d'),
            'departure_time' => 'required',
            'departure_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'no_of_people' => 'required|integer|between:1,200',
        ]);

        if($validator->fails()){
            return redirect('carpool_requests/'.$id.'/edit')
                ->withErrors($validator->errors())
                ->withInput();
        }else{
            $input = [
                'title' => $request->title,
                'description' => $request->description,
                'carpool_driver_id' => $request->carpool_driver_id,
                'departure_date' => $request->departure_date,
                'departure_time' => $request->departure_time,
                'departure_location' => $request->departure_location,
                'destination' => $request->destination,
                'no_of_people' => $request->no_of_people,
                'status' => 'Pending',
            ];

            CarpoolRequest::find($id)->update($input);

            return redirect('carpool_requests')->with('success', 'Carpool Request updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       CarpoolRequest::find($id)->delete();
         return redirect('carpool_requests')->with('success', 'Carpool Request deleted successfully.');
    }

    /**
     * Search for a carpool request.
     */
    public function search(Request $request){
        $search = $request->get('search');
        $carpoolRequests = CarpoolRequest::where('title', 'like', '%'.$search.'%')
            ->orWhere('description', 'like', '%'.$search.'%')
            ->orWhere('departure_location', 'like', '%'.$search.'%')
            ->orWhere('destination', 'like', '%'.$search.'%')
            ->paginate(10);
        return view('carpool_requests.index', compact('carpoolRequests'));
    }

    /**
     * Filter carpool requests by status.
     */
    public function filter(Request $request){
        $filter = $request->get('status');
        if($filter == 'All'){
           $carpoolRequests = CarpoolRequest::paginate(10);
        }else{
            $carpoolRequests = CarpoolRequest::where('status', $filter)->paginate(10);
        }

        return view('carpool_requests.index', compact('carpoolRequests'));
    }
}