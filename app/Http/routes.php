<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

use App\Task;
use Illuminate\Http\Request;

Route::group(['middleware' => ['web']], function() {
    Route::get('/', function() {
        return view('welcome');
    });
    
    Route::auth();
    
    Route::get('/invoices/{contract}', 'InvoiceController@index')->name('invoiceindex');
    Route::post('/invoices/{contract}', 'InvoiceController@store')->name('invoicestore');
    Route::delete('/invoices/{contract}/{invoice}', 'InvoiceController@destroy')->name('invoicedestroy');
    Route::get('/invoice/{contract}/{invoice}', 'InvoiceController@individual');
    Route::post('/invoice/{contract}/{invoice}', 'InvoiceController@storeItem');
    
    
    Route::delete('/invoice/{contract}/{invoice}/{item}', 'InvoiceController@destroyItem');
    
    Route::get('/home', 'HomeController@index');
    Route::get('/tasks', 'TaskController@index')->name('lalatask');
    Route::post('/tasks', 'TaskController@store');
    Route::delete('/task/{task}', 'TaskController@destroy');
       
    Route::get('/contracts', 'ContractController@index')->name('contractindex');
    Route::post('/contracts', 'ContractController@store')->name('contractstore');
    Route::delete('/contract/{contract}', 'ContractController@destroy')->name('contractdestroy');
    
    Route::get('/contract/{contract}', 'ContractController@editForm');
    Route::post('/contract/{contract}', 'ContractController@edit');
    
    /* Routes protected by authenication middleware */
    Route::group(['middleware' => ['auth']], function() {
        /* Route::get('/tasks', function() {
            return view('tasks', [
                'tasks' => Task::orderBy('created_at', 'asc')->get()
            ]);
        })->name('lalatask');
        */
       
        /*Route::post('/task', function(Request $request) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                //'tenantname' => 'required'
            ]);
    
            if ($validator->fails()) {
                //return redirect('/')
                return redirect()->route('lalatask')
                    ->withInput()
                    ->withErrors($validator);
            }
            $task = new Task;
            $task->name = $request->name;
            $task->tenantname = $request->gabe;
            $task->save();
            //return redirect('/');
            return redirect()->route('lalatask');
        });
        
        
        */
        /**
         * Delete Task
         */
        /*Route::delete('/task/{id}', function($id) {
            Task::findOrFail($id)->delete();
            return redirect()->route('lalatask');
        });//delete task
        */
    }); //Route::group middleware auth


});//Route::group web



