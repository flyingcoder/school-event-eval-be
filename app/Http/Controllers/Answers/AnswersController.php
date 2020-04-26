<?php

namespace App\Http\Controllers\Answers;

use App\Answer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class AnswersController extends Controller
{
  public function __construct() {
      //$this->middleware('auth:api', ['except' => ['login']]);
  }



  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function store(Request $request) {

    $data = $request->all();

    return Answer::create([
        'event_id' => $data['event_id'],
        'user_id' => $data['user_id'],
        'answers' => $data['answers']
    ]);
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function showEventAnswers($id) {

    $answers = DB::table('answers')
                    ->where('event_id', '=', $id)
                    ->get();

    foreach ($answers as $key => $value) {
      $answers[$key]->user = DB::table('users')
                                ->where('id', '=', $value->user_id)
                                ->first();

    }

    return response()->json($answers, 200);
  }

  public function checkIfSubmitted($id, $event) {

    $answers = DB::table('answers')
                    ->where('user_id', '=', $id)
                    ->where('event_id', '=', $event)
                    ->get();

    foreach ($answers as $key => $value) {
      $answers[$key]->user = DB::table('users')
                                ->where('id', '=', $value->user_id)
                                ->first();

    }

    return response()->json($answers, 200);
  }
}
