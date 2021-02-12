<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use DataTables;
use App\Mail\StudentRegistrationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function index(Request $request)
    {
        //
 
        if ($request->ajax()) {
            $data = Student::latest()->get();
            //return $data;
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editStudent">Edit</a>';
                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteStudent">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('student');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
        $validator = $this->StudentValidater($request);
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()->all()]);
        }
        //
        $data = ([
            'name' => $request->get('username'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'mobile' => $request->get('contact'),
            'message' => 'Welcome Student',
            ]);
            $mail = $request->email;

        if(Student::create($request->all())){

            Mail::to($mail)->send(new StudentRegistrationMail($request));
            return response()->json(['success'=>'Student Added successfully.']);
       }
        else{
            return response()->json(['error'=>'Unable to Add a Student']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $student = Student::find($id);
        return response()->json($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        //
        
        $validator = validator::make($request->all(), [
            'username' => 'required|unique:students,id',$request->student_id,
            'email' => 'required|email|unique:students,id',$request->student_id,
            'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:students,id',$request->student_id,
            'password' => 'required',
            'address' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()->all()]);
        }
        //
        Student::updateOrCreate(['id' => $request->student_id],
                ['username' => $request->username, 
                'email' => $request->email,
                'contact' => $request->contact,
                'address' => $request->address,
                'password' => $request->password,
                ]);
        return response()->json(['success'=>'saved successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Student::find($id)->delete();
        return response()->json(['success'=>'deleted successfully.']);
    }

    function StudentValidater($request){
        return validator::make($request->all(), [
                'username' => 'required|unique:students',
                'email' => 'required|email|unique:students',
                'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:students',
                'password' => 'required',
                'address' => 'required'
            ]);
    }
}
