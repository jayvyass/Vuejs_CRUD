<?php

namespace App\Http\Controllers;
use App\Models\Teacher;
use Illuminate\Http\Request;


class TeacherController extends Controller 
{     protected $teacher;

    public function __construct(){
        $this->teacher = new teacher();
    }

    public function index(){
        return $this->teacher->all();
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone'=> 'required',
            'image' => 'required|image|max:2048', 
        ]);

        $teacher = new Teacher();
        $teacher->name = $request->input('name');
        $teacher->email = $request->input('email');
        $teacher->phone = $request->input('phone');
        $teacher->status = $request->input('status');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $teacher->image = 'images/'.$imageName;
        }

        $teacher->save();

        return response()->json($teacher, 201);
    }

    public function update(Request $request, $id) {

        $teacher = Teacher::findOrFail($id);
        $teacher->name = $request->input('name');
        $teacher->email = $request->input('email');
        $teacher->phone = $request->input('phone');
        $teacher->status = $request->input('status');

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $teacher->image = 'images/'.$imageName;
        }
        $teacher->save();
        
        return $teacher;
        
    }

    public function destroy($id){
        $teacher = $this->teacher->findOrFail($id);
        $teacher->delete();

        return response()->json(['message' => 'Teacher deleted successfully']);
    }
}
