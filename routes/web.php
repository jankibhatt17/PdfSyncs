<?php

use App\Meeting;
use App\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

// use App\Http\Controllers\MeetingController;
// use App\Http\Controllers\Api\V1\Admin\MeetingsApiController;
// use App\Http\Controllers\Admin\MeetingsController;


Route::get('/pdfview/{id}',function($id){
    // $users=array();
    $filename=DB::table('media')->where('model_id',$id)->first();
    $_GET['pdfname']=$filename->file_name;
    $attachments=DB::table('media')->where('model_id',$id)->get();
   $meeting=Meeting::find($id);
  
       
    $users=DB::table('users_meetings')->where('meeting_id',$id)->count();
    // return $users;
      

// return $users;
    return view('pdfview',['attachments'=>$attachments,'id'=>$id,'users'=>$users]);
});
Route::get('/pdfview/{filename}/{id}', function($filename,$id){
    $users=array();
    $_GET['pdfname']=$filename;
    $attachments=DB::table('media')->where('model_id',$id)->get();
    $meeting=Meeting::find($id);
 
    
    $users=DB::table('users_meetings')->where('meeting_id',$id)->count();

    return view('pdfview',['attachments'=>$attachments,'id'=>$id,'users'=>$users]);
});
Route::get('/userupdate/{id}', function($id){
   
 
    
    $users=DB::table('users_meetings')->where('meeting_id',$id)->count();

    return response()->json($users);
});


Route::get('/startsharings/{id}', function($id){
    $users=DB::table('meetings')->where('id',$id)->where('shared','1')->exists();
    //false
    $stop=DB::table('users_meetings')->where('meeting_id',$id)->exists();
    //false
    $data = array(
      "start" => $users,
      "stop" => $stop
    );

    return $data;
});






Route::get('/Follow/{id}',function($id){
    // if(!DB::table('users_meetings')->where('user_id',Auth::user()->id)->where('meeting_id',$id)->exists()){
    // DB::table('users_meetings')->insert(['user_id'=>Auth::user()->id,'meeting_id'=>$id]);
    // }
    $meeting=Meeting::find($id);
    // $pagenumber=$meeting->pagenumber;
    // $pdfname=$meeting->pdfname;
    // $pdf->pdfname=$pdfname;
    // dd($pdf);
    // $pdf->pagenumber=$pagenumber;
    return response()->json($meeting);
});




Route::get('/ClickFollow/{id}',function($id){
    if(!DB::table('users_meetings')->where('user_id',Auth::user()->id)->where('meeting_id',$id)->exists()){
    $s1=DB::table('users_meetings')->insert(['user_id'=>Auth::user()->id,'meeting_id'=>$id]);
    }
    
    return $s1;
});







Route::get('/UnFollow/{id}',function($id){
$sa=DB::table('users_meetings')->where(['user_id'=>Auth::user()->id,'meeting_id'=>$id])->delete();
    return $sa;
});











Route::get('/StartShare/{id}/{pagenumber}/{pdfname}',function($id,$pagenumber,$pdfname){
    $idds='a10'.$id;
  $s=Meeting::where('id',$id)->update(['shared'=>"1",'pagenumber'=>$pagenumber,'pdfname'=>$pdfname]);
    return response()->json($id);
});


Route::get('/StopShare/{id}',function($id){
    //   sleep(4);
   DB::table('users_meetings')->truncate();

  $s=Meeting::where('id',$id)->update(['shared'=>'2']);
  

 return redirect()->route('admin.meetings.index')->withMessage(Meeting::where('id',$id)->update(['shared'=>'2']));

//return Redirect::back()->withMessage(Meeting::where('id',$id)->update(['shared'=>'2']));
});





Route::get('/StopSharese/{id}',function($id){
    //   sleep(4);
   DB::table('users_meetings')->truncate();

  $s=Meeting::where('id',$id)->update(['shared'=>'2']);
  

// return redirect()->route('admin.meetings.index');

return Redirect::back()->withMessage(Meeting::where('id',$id)->update(['shared'=>'2']));
});






Route::get('/',function(){
    
    return view('welcome');
});



// Route::resource('/', 'PermissionsController');


Route::get('/UsersEmpty/{id}',function($id){
   
  
    DB::table('users_meetings')->truncate();
    
return response()->json($id);

});


Route::get('/home', function () {
    $route = Gate::denies('dashboard_access') ? 'admin.meetings.index' : 'admin.home';
    if (session('status')) {
        return redirect()->route($route)->with('status', session('status'));
    }

    return redirect()->route($route);
});

Auth::routes(['register' => false]);
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::post('tickets/media', 'MeetingController@storeMedia')->name('tickets.storeMedia');
Route::post('tickets/comment/{ticket}', 'MeetingController@storeComment')->name('tickets.storeComment');
Route::resource('meetings', 'MeetingController')->only(['show', 'create', 'store','']);


// Route::get('/', 'HomeController@index')->name('home');
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');


    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Statuses
    Route::delete('statuses/destroy', 'StatusesController@massDestroy')->name('statuses.massDestroy');
    Route::resource('statuses', 'StatusesController');

    // Priorities
    Route::delete('priorities/destroy', 'PrioritiesController@massDestroy')->name('priorities.massDestroy');
    Route::resource('priorities', 'PrioritiesController');

    // Categories
    Route::delete('categories/destroy', 'CategoriesController@massDestroy')->name('categories.massDestroy');
    Route::resource('categories', 'CategoriesController');

    // Tickets
    Route::delete('meetings/destroy', 'MeetingsController@massDestroy')->name('meetings.massDestroy');
    Route::post('tickets/media', 'MeetingsController@storeMedia')->name('tickets.storeMedia');
    Route::post('tickets/comment/{ticket}', 'MeetingsController@storeComment')->name('tickets.storeComment');
    Route::resource('meetings', 'MeetingsController');

    // Comments
    Route::delete('comments/destroy', 'CommentsController@massDestroy')->name('comments.massDestroy');
    Route::resource('comments', 'CommentsController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);
});
