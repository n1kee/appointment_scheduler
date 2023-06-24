<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use Carbon\Carbon;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctorListResponse = DoctorResource::collection(Doctor::all());

        return response()->json($doctorListResponse);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDoctorRequest $request)
    {
        $doctorData = $request->all();

        $doctorData['workdayStart'] = Carbon::parse($doctorData['workdayStart'])->secondsSinceMidnight() / 60;
        $doctorData['workdayEnd'] = Carbon::parse($doctorData['workdayEnd'])->secondsSinceMidnight() / 60;

        $doctor = Doctor::create($doctorData);

        return response()->json([
            'id' => $doctor->id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor)
    {
        $doctorResponce = new DoctorResource($doctor);

        return response()->json($doctorResponce);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        $doctor->update($request->all());

        return response();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return response();
    }
}
