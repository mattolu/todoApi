<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Todo;
use Validator;

class TodoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(Request $request)
    {
    $validator = Validator::make($request->all(), [
        'title' => 'required',
        'description' => 'required',
        'date_due' => 'required',
        'created_by' => 'required',
        'completed' =>'required'
    ]);
        if ($validator->fails()) {
            return response()->json([
                'error'=>[
                    'success' => false,
                    'status' =>400,
                    'message' => $validator->errors()->all()
                        ]]);
            }
            try{
                
                $todo = new Todo();
                $todo->title = $request->title;
                $todo->description = $request->description;
                $todo->date_due = $request->date_due;
                $todo->created_by = $request->created_by;
                $todo->completed = $request->completed;

                if (($request->completed)==true){
                    $todo->save();
                    return json_encode([
                                'result'=> [
                                        'success'=> true,
                                        'status'=>200,
                                        'message'=> 'successfully',
                                          ]]);    
                }
                else{
                    return json_encode([
                        'error'=> [
                                'success'=> false,
                                'status'=>200,
                                'message'=> 'Complete the To-Do and click the completed',
                                  ]]);    
                }
              
               
        
                
                }catch(\Illuminate\Database\QueryException $ex){
                return json_encode([
                    'status'=>500,
                    'message'=>$ex->getMessage()
                    ]);  
            }
        }

    public function update (Request $request)
    {
        $todo = Todo::find($request->id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'date_due' => 'required',
            'created_by' => 'required',
            'completed' =>'required'
        ]);
            if ($validator->fails()) {
                return response()->json([
                    'error'=>[
                        'success' => false,
                        'status' =>400,
                        'message' => $validator->errors()->all()
                            ]]);
                }
                try{

                    // $todo = new Todo();
                    $todo->title = $request->title;
                    $todo->description = $request->description;
                    $todo->date_due = $request->date_due;
                    $todo->created_by = $request->created_by;
                    $todo->completed = $request->completed;
                
                    if (($request->completed)==true){
                        $todo->update();
                        return json_encode([
                                    'result'=> [
                                            'success'=> true,
                                            'status'=>200,
                                            'message'=> 'Message sent successfully',
                                            ]]);    
                    }
                    else{
                        return json_encode([
                            'error'=> [
                                    'success'=> false,
                                    'status'=>200,
                                    'message'=> 'Complete the To-Do and click the completed',
                                    ]]);    
                    }
                    
                    }catch(\Illuminate\Database\QueryException $ex){
                    return json_encode([
                        'status'=>500,
                        'registered'=>false,
                        'message'=>$ex->getMessage()
                        ]);  
                }
        }

    public function delete(Request $request){
        $todo = Todo::find($request->id);
        $todo->delete();
       return json_encode([
        'success'=>[
            'status'=> 200,
            'message'=> 'Deleted'
        ]]);

    }
    public function sort(){
        $sort = Todo::orderBy('id', 'desc')->get();
        if ( count($sort) !=0  ){
            return json_encode([
            'todo'=>[
                    'status'=>200,
                    'sorted'=>$sort
                ]]);
            } else{
                return json_encode([
                    'error'=>[
                        'status'=> 401,
                        'message'=> 'No todo list'
                    ]]);
            }
        
    }

    public function filter(Request $request, Todo $todo){
    // Search for a todo based on their title.
        if ($request->has('title')) {
            return $todo->where('title', $request->input('title'))->get();
        }

        // Search for a todo based on their date of creation.
        if ($request->has('creation_date')) {
            return $todo->where('created_at', $request->input('creation_date'))->get();
        }

        // Search for a todo based on their date of updating.
        if ($request->has('updated_date')) {
            return $todo->where('updated_at', $request->input('updated_date'))->get();
        }
        // Search for a todo based on their creator
        if ($request->has('created_by')) {
            return $todo->where('created_by', $request->input('created_by'))->get();
        }
 
    }
}
