<?php

namespace App\Http\Controllers\Evaluation;

use App\Subcriteria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubcriteriaController extends Controller
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
      'value' => ['required', 'string', 'max:500'],
      'criteria_id' => ['required'],
    ], $messages);
  }

  /**
  * Get all colleges
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function index() {

    $subcriterias = Subcriteria::all();
    return response()->json($subcriterias, 200);

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

    return Subcriteria::create([
        'value' => $data['value'],
        'criteria_id' => $data['criteria_id']
    ]);
  }

  /**
  * Update specific subcriteria data
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function edit(Request $request, $id) {

    $subcriteria = Subcriteria::find($id);

    $data = $request->all();
    $validation = $this->validator($data);

    //validate data
    if($validation->fails()){
        return response()->json($validation->errors()->all(), 401);
    }

    $subcriteria->name =  $data['name'];
    $subcriteria->save();

    return response()->json(['success' => 'Successfully updated.'], 200);

  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id) {

    Subcriteria::destroy($id);

    return response()->json(['success' => 'Successfully deleted.'], 200);

  }
}
