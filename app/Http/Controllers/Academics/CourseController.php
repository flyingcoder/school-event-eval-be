<?php

namespace App\Http\Controllers\Academics;

use App\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class CourseController extends Controller
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
      'dept_id' => ['required'],
    ], $messages);
  }

  /**
  * Get all colleges
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function index() {

    $course = Course::all();
    return response()->json($course, 200);

  }

  /**
  * Get all departments by college id
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function filterByDept($id) {

    $courses = DB::table('courses')
                ->where('dept_id', '=', $id)
                ->get();
    return response()->json($courses, 200);

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

    return Course::create([
        'name' => $data['name'],
        'dept_id' => $data['dept_id']
    ]);
  }

  /**
  * Get specific college data
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function show($id) {

    $course = Course::find($id);
    return response()->json($college, 200);

  }

  /**
  * Update specific college data
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function edit(Request $request, $id) {

    $course = Course::find($id);

    $data = $request->all();
    $validation = $this->validator($data);

    //validate data
    if($validation->fails()){
        return response()->json($validation->errors()->all(), 401);
    }

    $course->name =  $data['name'];
    $course->save();

    return response()->json(['success' => 'Successfully updated.'], 200);

  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id) {

    Course::destroy($id);

    return response()->json(['success' => 'Successfully deleted.'], 200);

  }
}
