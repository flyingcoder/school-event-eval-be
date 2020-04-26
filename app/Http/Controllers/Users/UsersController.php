<?php

namespace App\Http\Controllers\Users;

use DB;
use App\User;
use Illuminate\Http\Request;
use App\Imports\UsersImport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
  public function __construct() {
      $this->middleware('auth:api', ['except' => ['login']]);
  }
  /**
  * Get all users
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function index() {

    $users = User::all();
    return response()->json($users, 200);

  }

  /**
  * Import csv to users table
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function import()
  {
      try {
        Excel::import(new UsersImport, request()->file('csvfile'));
        return response()->json(200);
      } catch (Exception $e) {
        return response(500);
      }  
  }

  /**
  * Get all users by id
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function getByType($id) {

    $users = DB::table('users')
              ->where('type', '=', $id)
              ->get();
    return response()->json($users, 200);

  }

  public function show($id) {
    $user = DB::table('users')
                    ->where('id', '=', $id)
                    ->first();
    return response()->json($user, 200);
  }

  public function edit(Request $request, $id) {

    $user = User::find($id);

    $data = $request->all();

    $user->name =  $data['name'];
    $user->email = $data['email'];
    $user->phone = $data['phone'];
    $user->type = $data['type'];
    $user->college = $data['college'];
    $user->department = $data['department'];
    $user->school_id = $data['school_id'];
    $user->course = $data['course'];
    if(!empty($data['password'])){
      $user->password = $data['password'];
      if (Hash::needsRehash($data['password'])) {
          $user->password = Hash::make($data['password']);
      }
    }
    $user->save();

    return response()->json(['success' => 'Successfully updated.'], 200);

  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id) {

    User::destroy($id);

    return response()->json(['success' => 'Successfully deleted.'], 200);

  }
}
