<?php

namespace App\Http\Controllers\Evaluation;

use App\Evaluation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class EvaluationController extends Controller
{
  public function __construct() {
      $this->middleware('auth:api', ['except' => ['login']]);
  }

  /**
  * Get a validator for an incoming college create request.
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
    ], $messages);
  }

  /**
  * Get all evaluations
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function index() {

    $evaluations = Evaluation::all();

    foreach ($evaluations as $key => $value) {
      $evaluations[$key]->user = DB::table('users')
                                    ->where('id', '=', $value->user_id)
                                    ->select('name')
                                    ->first();
    }
    return response()->json($evaluations, 200);

  }

  public function getEvaluationsByUser($id){
    $evaluations = DB::table('evaluations')
                    ->where('user_id', '=', $id)
                    ->get();
    foreach ($evaluations as $key => $value) {
      $evaluations[$key]->user = DB::table('users')
                                  ->where('id', '=', $value->user_id)
                                  ->select('name')
                                  ->first();
    }
    return response()->json($evaluations, 200);

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

    return Evaluation::create([
        'name' => $data['name'],
        'user_id' => $data['user_id']
    ]);
  }

  public function show($id) {

    // $data = DB::table('evaluations')
    //           ->get();

    //$evaluations = Evaluation::find($id);
    $evaluation = DB::table('evaluations')
                    ->where('id', '=', $id)
                    ->first();
    $criterias = DB::table('criterias')
                    ->where('eval_id', '=', $id)
                    ->get();

    foreach ($criterias as $key => $value) {
      $criterias[$key]->subcriterias = DB::table('subcriterias')
                                            ->where('criteria_id', '=', $value->id)
                                            ->get();
    }
    return response()->json(['evaluation' => $evaluation, 'criterias' => $criterias], 200);

  }

  /**
  * Update specific evaluation data
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function edit(Request $request, $id) {

    $evaluation = Evaluation::find($id);

    $data = $request->all();
    $validation = $this->validator($data);

    //validate data
    if($validation->fails()){
        return response()->json($validation->errors()->all(), 401);
    }

    $evaluation->name =  $data['name'];
    $evaluation->save();

    return response()->json(['success' => 'Successfully updated.'], 200);

  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id) {

    Evaluation::destroy($id);

    return response()->json(['success' => 'Successfully deleted.'], 200);

  }
}
