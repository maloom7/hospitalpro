<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Doctor;

use Illuminate\Support\Facades\Auth;

use App\Models\Appointment;

use Notification;

use App\Notifications\SendEmailNotification;




class AdminController extends Controller
{
    // add doctor
    public function addview()
    {
        if(Auth::id())
        {
            if(Auth::user()->usertype==1)

            {
                return view('admin.add_doctor');


            }

            else
            {
                return redirect()->back();
            }
        }

        else
        {
            return redirect('login');
        }


    }
    
    // upload doctor
    public function upload(Request $request)
    {
        $doctor=new doctor;

        $image=$request->file;

        $imagename=time().'.'.$image-> getClientoriginalExtension();

        $request->file->move('doctorimage',$imagename);

        $doctor->image=$imagename;


        $doctor->name=$request->name;

        $doctor->phone=$request->number;

        $doctor->room=$request->room;

        $doctor->speciality=$request->speciality;



        $doctor->save();

        return redirect()->back()->with('message', 'Doctor Added Successfully');

        
    }

    // Show appointment
    public function showappointment()
    {
        if(Auth::id())
        {
            if(Auth::user()->usertype==1)

            {


        $data=appointment::all();

        return view('admin.showappointment',compact('data'));
            }
            else
            {
                return redirect()->back();
            }

        }
        else
        {
            return redirect('login');
        }
    }

    // Approve appoint
    public function approved($id)
    {
        $data=appointment::find($id);

        $data->status='Approved';

        $data->save();

        return redirect()->back();
    }

    // Cancel appoint
    public function canceled($id)
    {
        $data=appointment::find($id);

        $data->status='Canceled';

        $data->save();

        return redirect()->back();
    }


    // Show doctors
    public function showdoctor()
    {
        $data = doctor::all();

        return view('admin.showdoctor',compact('data'));
    }


    // Delete doctors
    public function deletedoctor($id)
    {
        $data=doctor::find($id);

        $data->delete();

        return redirect()->back();
    }

    // Update doctor
    public function updatedoctor($id)
    {
        $data = doctor::find($id);

        return view('admin.update_doctor',compact('data'));
    }

    // Edit doctors data and submit
    public function editdoctor(Request $request , $id)
    {
        $doctor = doctor::find($id);

// the first (name) is from database and the second (name) is from update_doctor class and the same for the others
        $doctor->name=$request->name;
        
        $doctor->phone=$request->phone;
        
        $doctor->speciality=$request->speciality;
       
        $doctor->room=$request->room;

        $image=$request->file;

        if($image)
        {

        $imagename=time().'.'.$image->getClientOriginalExtension();

        $request->file->move('doctorimage',$imagename);

        $doctor->image=$imagename;

        }

        $doctor->save();

        return redirect()->back()->with('message','Doctor Details Updated Successfully');
    }


    // Send email view page
    public function emailview($id)
    {

        $data=appointment::find($id);

        return view('admin.email_view',compact('data'));
    }

    // send email notification
    public function sendmail(Request $request,$id)
    {
        $data = appointment::find($id);

        $details=[

            'greeting' => $request->greeting,

            'body' => $request->body,

            'actiontext' => $request->actiontext,

            'actionurl' => $request->actionurl,

            'endpart' => $request->endpart

        ];

        Notification::send($data,new SendEmailNotification($details));

        return redirect()->back()->with('message','Email send is successful');
    }


     // add employee
     public function addemployee()
     {
         if(Auth::id())
         {
             if(Auth::user()->usertype==1)
 
             {
                 return view('admin.add_employee');
 
 
             }
 
             else
             {
                 return redirect()->back();
             }
         }
 
         else
         {
             return redirect('login');
         }
 
 
     }


}
