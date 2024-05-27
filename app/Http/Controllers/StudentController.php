<?php

namespace App\Http\Controllers;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class StudentController extends Controller
{
    protected $student;

    public function __construct(){
        $this->student = new Student();
    }

    public function index(){
        return $this->student->all();
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone'=> 'required',
            'image' => 'required|image|max:2048', 
        ]);
        $student = new Student();
        $student->name = $request->input('name');
        $student->email = $request->input('email');
        $student->phone = $request->input('phone');
        $student->gender = $request->input('gender');
        $student->status = $request->input('status');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $student->image = 'images/'.$imageName;
        }
        $student->save();
        return response()->json($student, 201);
    }

    public function update(Request $request, $id) {
        $student = Student::findOrFail($id);
        $student->name = $request->input('name');
        $student->email = $request->input('email');
        $student->phone = $request->input('phone');
        $student->gender = $request->input('gender');
        $student->status = $request->input('status');
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $student->image = 'images/'.$imageName;
        }
        $student->save();      
        return $student;
    }

    public function destroy($id){
        $student = $this->student->findOrFail($id);
        $student->delete();

        return response()->json(['message' => 'Student deleted successfully']);
    }
}
