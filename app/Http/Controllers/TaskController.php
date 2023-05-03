<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all task
        $tasks = Task::all();
        return response($tasks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // Validate task
        $request->validate([
            'name'=>['required'],
            'description'=>['required','max:255'],
            'status'=>['required']
        ]);

        // save new task
        $task = Task::create($request->all('name','description','status'));
        return response($task);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Fin or Fail data 
        $task = Task::findOrFail($id);
        return response($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // first fin the data and after that update
        $task = Task::findOrFail($id)->update($request->all());
        return response("Tarea con ID $id actualizada correctamente");
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delte the data
        $task = Task::destroy($id);
        return response("Tarea con ID $id eliminada correctamente");
    }
}
