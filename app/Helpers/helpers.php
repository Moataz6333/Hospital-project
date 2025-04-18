<?php
use Illuminate\Support\Facades\Http;

use App\Models\Hospital;

function TimesWhereNotNull($doctor)
{
    $times =  collect($doctor->timeTable)->filter(function ($day) {
        return !is_null($day);
    });
    return $times;
}

function GetDaysNames($times)
{
    $days = [];
    $AllDays = ['sat' => 'Saturday', 'sun' => 'Sunday', 'mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thurs' => 'Thursday', 'fri' => 'Friday'];

    foreach ($times as $key => $val) {
        $subString = substr($key, 0, 3); //sun
        if ($subString == 'thu') {
            $subString = "thurs";
        }
        if (!array_key_exists($subString, $days)) {
            $days[$subString] = $AllDays[$subString];
        }
    }
    return $days;
}
function GetCurrentDay($days)
{
    $current = date('l');

    if (!in_array($current, $days)) {
        $current = array_values($days)[0];
    }
    return $current;
}

function GetCurrentDayForDoctor($doctor)
{
    $days = GetDaysNames(TimesWhereNotNull($doctor));
    $current = GetCurrentDay($days);
    return $current;
}
function AllDays() {
    $AllDays = ['sat' => 'Saturday', 'sun' => 'Sunday', 'mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thurs' => 'Thursday', 'fri' => 'Friday'];
    return $AllDays;
}

// function increateBalance($amount) {
//     // Hospital::first()
// }