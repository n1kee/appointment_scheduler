<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvailableIntervalsRequest;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Http\Traits\SearchPersonTrait;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    use SearchPersonTrait;

    public function getAvailableIntervals(AvailableIntervalsRequest $request)
    {
        $doctorId = $request->get('doctor');

        $dateString = $request->get('date');

        $date = Carbon::parse($dateString);

        $availableIntervals = [];

        $doctor = Doctor::where('id', $doctorId)->first();

        $dayStart = Carbon::parse($dateString)->startOfDay();
        $dayEnd = Carbon::parse($dateString)->endOfDay();

        $appointmentList = Appointment::where('datetime', '>=', $dayStart->format('Y-m-d H:i'))
            ->where('datetime', '<=', $dayEnd->format('Y-m-d H:i'))
            ->where('doctor_id', $doctorId)
            ->orderBy('datetime', 'asc')
            ->get();

        $intervalStart = $doctor->getWorkdayStart($date);

        $addAvailableInterval = function (Carbon $intervalStart, Carbon $intervalEnd) use (&$availableIntervals) {
            $timeDiffMinutes = $intervalEnd->diffInMinutes($intervalStart);

            if ($timeDiffMinutes >= 30) {

                $availableIntervals[] = [
                    'from' => $intervalStart->format('H:i'),
                    'to' => $intervalEnd->format('H:i'),
                ];
            }
        };

        foreach ($appointmentList as $appointment) {

            $addAvailableInterval($intervalStart, $appointment->datetime);

            $intervalEnd = clone $appointment->datetime;
            $intervalEnd->modify('+30 minutes');

            $intervalStart = clone $intervalEnd;
        }

        $addAvailableInterval($intervalStart, $doctor->getWorkdayEnd($date));

        return response()->json($availableIntervals);
    }

    public function searchByPerson(string $fieldName, ?string $searchQuery, Builder $dbQuery)
    {

        if (! $searchQuery) {
            return;
        }

        return $dbQuery->whereHas($fieldName, function ($q) use ($searchQuery) {
            $this->searchByFullname($searchQuery, $q);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $doctorQuery = $request->get('doctor');

        $patientQuery = $request->get('patient');

        $datetimeSort = $request->get('datetimeSort') ?? 'desc';

        $from = $request->get('from');

        $to = $request->get('to');

        $dbQuery = Appointment::query('a')->orderBy('datetime', $datetimeSort);

        $this->searchByPerson('doctor', $doctorQuery, $dbQuery);

        $this->searchByPerson('patient', $patientQuery, $dbQuery);

        if ($from) {
            $dbQuery->where('datetime', '>=', $from);
        }
        if ($to) {
            $dbQuery->where('datetime', '<=', $to);
        }

        $pagination = $dbQuery->paginate(10);

        $pagination->getCollection()->transform(function ($appointment) {
            return new AppointmentResource($appointment);
        });

        return $pagination;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAppointmentRequest $request)
    {
        $appointment = Appointment::create($request->all());

        return response()->json([
            'id' => $appointment->id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
