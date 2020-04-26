<?php

namespace App\Http\Controllers\Academics;

use App\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class DepartmentController extends Controller
{
  public function __construct() {
      //$this->middleware('auth:api', ['except' => ['login']]);
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
      'college_id' => ['required'],
    ], $messages);
  }

  /**
  * Get all colleges
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function index() {

    $colleges = Department::all();
    return response()->json($colleges, 200);

  }

  /**
  * Get all departments by college id
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function filterByCollege($id) {

    $colleges = DB::table('departments')
                ->where('college_id', '=', $id)
                ->get();
    return response()->json($colleges, 200);

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

    return Department::create([
        'name' => $data['name'],
        'college_id' => $data['college_id']
    ]);
  }

  /**
  * Get specific college data
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function show($id) {

    $departments = Department::find($id);
    return response()->json($departments, 200);

  }

  /**
  * Update specific college data
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function edit(Request $request, $id) {

    $departments = Department::find($id);

    $data = $request->all();
    $validation = $this->validator($data);

    //validate data
    if($validation->fails()){
        return response()->json($validation->errors()->all(), 401);
    }

    $departments->name =  $data['name'];
    $departments->college_id =  $data['college_id'];
    $departments->save();

    return response()->json(['success' => 'Successfully updated.'], 200);

  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id) {

    Department::destroy($id);

    return response()->json(['success' => 'Successfully deleted.'], 200);

  }
}
