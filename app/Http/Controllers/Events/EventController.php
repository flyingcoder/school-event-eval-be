<?php

namespace App\Http\Controllers\Events;

use App\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;
use DB;

class EventController extends Controller
{
  public function __construct() {
      $this->middleware('auth:api', ['except' => ['login']]);
  }

  /**
  * Get a validator for an incoming event create request.
  *
  * @param  array  $data
  * @return \Illuminate\Contracts\Validation\Validator
  */

  protected function validator(array $data) {
    $messages = [
      'required'    => 'You need to provide your :attribute to proceed.',
      'size'    => 'The :attribute must be exactly :size.',
      'unique' => 'This :attribute is being used by another recruiter.',
      'in'      => 'The :attribute must be one of the following types: :values',
    ];

    return Validator::make($data, [
      'name' => ['required', 'string', 'max:255'],
      'evaluation' => ['required']
    ], $messages);
  }

  /**
  * Get all events
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function index() {

    $events = Event::all();
    return response()->json($events, 200);

  }

  public function getEventsByUser($id){
    $events = DB::table('events')
                    ->where('user_id', '=', $id)
                    ->get();
    return response()->json($events, 200);
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function store(Request $request) {

    $data = $request->all();
    $validation = $this->validator($data);

    //validate data
    if($validation->fails()){
        return response()->json($validation->errors()->all(), 401);
    }

    return Event::create([
        'name' => $data['name'],
        'venue' => $data['venue'],
        'date' => $data['event_date'],
        'user_id' => $data['user_id'],
        'college' => $data['college'],
        'department' => $data['department'],
        'course' => $data['course'],
        'evaluation' => $data['evaluation'],
        'school_year' => $data['school_year'],
        'semester' => $data['semester']
    ]);

  }

  public function show($id) {

    $event = DB::table('events')
                    ->where('id', '=', $id)
                    ->first();
    if(!is_null($event->course)){
      $event->course = DB::table('courses')
                        ->where('id', '=', $event->course)
                        ->first();
      $event->students = DB::table('users')
                                  ->where('type', '=', '3')
                                  ->where('course', '=', $event->course->id)
                                  ->select('name', 'email', 'phone')
                                  ->get();

    } else if(!is_null($event->department)){
      $event->department = DB::table('departments')
                        ->where('id', '=', $event->department)
                        ->first();
      $event->students = DB::table('users')
                                  ->where('type', '=', '3')
                                  ->where('department', '=', $event->department->id)
                                  ->select('name', 'email', 'phone')
                                  ->get();


    } else if(!is_null($event->college)){
      $event->college = DB::table('colleges')
                        ->where('id', '=', $event->college)
                        ->first();
      $event->students = DB::table('users')
                                    ->where('type', '=', '3')
                                    ->where('department', '=', $event->college->id)
                                    ->select('name', 'email', 'phone')
                                    ->get();
    }


    return response()->json($event, 200);

  }

  public function get($id) {

    $event = DB::table('events')
                    ->where('id', '=', $id)
                    ->first();
    if(!is_null($event->course)){
      $event->course = DB::table('courses')
                        ->where('id', '=', $event->course)
                        ->first();
      $event->students = DB::table('users')
                                  ->where('type', '=', '3')
                                  ->where('course', '=', $event->course->id)
                                  ->select('name', 'email', 'phone')
                                  ->get();

    } else if(!is_null($event->department)){
      $event->department = DB::table('departments')
                        ->where('id', '=', $event->department)
                        ->first();
      $event->students = DB::table('users')
                                  ->where('type', '=', '3')
                                  ->where('department', '=', $event->department->id)
                                  ->select('name', 'email', 'phone')
                                  ->get();


    } else if(!is_null($event->college)){
      $event->college = DB::table('colleges')
                        ->where('id', '=', $event->college)
                        ->first();
      $event->students = DB::table('users')
                                    ->where('type', '=', '3')
                                    ->where('department', '=', $event->college->id)
                                    ->select('name', 'email', 'phone')
                                    ->get();
    }


    return $event;

  }

  /**
  * Update specific event data
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function edit(Request $request, $id) {

    $event = Event::find($id);

    $data = $request->all();
    $validation = $this->validator($data);

    //validate data
    if($validation->fails()){
        return response()->json($validation->errors()->all(), 401);
    }

    $event->name =  $data['name'];
    $event->college = $data['college'];
    $event->department = $data['department'];
    $event->course = $data['course'];
    $event->evaluation = $data['evaluation'];
    $event->venue = $data['venue'];
    $event->date = $data['event_date'];
    $event->school_year = $data['school_year'];
    $event->semester = $data['semester'];
    $event->save();

    return response()->json(['success' => 'Successfully updated.'], 200);

  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id) {

    Event::destroy($id);

    return response()->json(['success' => 'Successfully deleted.'], 200);

  }

  public function sendEvaluation($id) {
    $event = $this->get($id);

    foreach ($event->students as $key => $value) {
      $message = 'Hi ' . $value->name . '! You are required to submit an evaluation on the recently concluded ' . $event->name . ' last '. $event->date .'. Here\'s the link to the evaluation form: https://ldcu-ees.online/event/'.$event->id.'/evaluation/' . $event->evaluation;
      $this->sendMessage($message, $value->phone);
    }

    return response()->json($event, 200);
  }

  private function sendMessage($message, $recipients)
  {
      $account_sid = getenv("TWILIO_SID");
      $auth_token = getenv("TWILIO_AUTH_TOKEN");
      $twilio_number = getenv("TWILIO_NUMBER");
      $client = new Client($account_sid, $auth_token);
      $client->messages->create($recipients,
              ['from' => $twilio_number, 'body' => $message] );
  }
}
