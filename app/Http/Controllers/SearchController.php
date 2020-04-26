<?php

namespace App\Http\Controllers\Users;

use App\User;
use App\Event;
use App\Evaluation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchController extends Controller
{
  
  /**
   * @ search for q on events table
   * return Event collection
   */
  public function events() {

  	$search_result = Event::whereRaw('*', 'like', '%'.request()->q.'%')
  						  ->get();

  	return response()->json($search_result, 200);

  }

  /**
   * @ search for q on events table
   * return Event collection
   */
  public function users() {
  	
  	$search_result = User::whereRaw('*', 'like', '%'.request()->q.'%')
  						  ->get();

  	return response()->json($search_result, 200);

  }

  /**
   * @ search for q on events table
   * return Event collection
   */
  public function evaluations() {
  	
  	$search_result = Evaluation::whereRaw('*', 'like', '%'.request()->q.'%')
  						  ->get();

  	return response()->json($search_result, 200);

  }

}