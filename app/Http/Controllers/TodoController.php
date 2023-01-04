<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todos = Todo::latest()->get();
        return view('todo', compact('todos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validasi inputan user
        $validator = Validator::make($request->all(), [
            'title' => 'required'
        ]);

        // jika validasi gagal kembalikan ke index dengan pesan error
        if($validator->fails()){
            return redirect()->route('todos.index')->withErrors($validator->errors());
        }

        // buat row di table Todo
        Todo::create([
            'title' => $request->get('title')
        ]);

        // redirect dengan pesan berhasil
        return redirect()->route('todos.index')->with('success', 'Inserted');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // ambil todo dengan id yg sesuai
        $todo = Todo::where('id', $id)->first();

        // lempar ke view edit-todo
        return view('edit-todo', compact('todo'));
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
        // validasi inputan
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        // jika gagal tampilkan pesan
        if ($validator->fails())
        {
            return redirect()->route('todos.edit',['todo'=>$id])->withErrors($validator);
        }

        // ambil todo
        $todo = Todo::where('id', $id)->first();

        // update isi dari todo
        $todo->title = $request->get('title');
        $todo->is_completed = $request->get('is_completed');
        $todo->save();

        // kembalikan ke halaman todo.blade.php
        return redirect()->route('todos.index')->with('success', 'Updated Todo');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // hapus todo
        Todo::where('id', $id)->delete();

        // redirect ke todo.blade.php
        return redirect()->route('todos.index')->with('success', 'Deleted');
    }
}
