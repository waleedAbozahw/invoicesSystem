<?php

namespace App\Http\Controllers;

use App\Models\Sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sectionsData = Sections::all();
        return view('sections.sections',compact('sectionsData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    //    $input = $request->all();
    //    //التحقق من السجيل مسبقا
    //    $s_exists = Sections::where('section_name','=',$input['section_name'])->exists();
    //    if ($s_exists) {
    //      session()->flash('Error','القسم مسجل مسبقا');
    //      return redirect('/sections');

    //validation in laravel

        $validated = $request->validate([
            'section_name' => 'required|unique:sections|max:255',

        ],[
                'section_name.required'=> 'يرجي ادخال اسم القسم',
                'section_name.unique'=> ' اسم القسم موجود بالفعل',
               

        ]);

         Sections::create([
            'section_name' => $request->section_name,
            'description' => $request->description,
            'created_by' => (Auth::user()->name),

         ]);
         session()->flash('Add','تم اضافة القسم بنجاح');
         return redirect('/sections');


    }

    /**
     * Display the specified resource.
     */
    public function show(Sections $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sections $sections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sections $sections)
    {
        $id = $request->id;

        $this->validate($request, [

            'section_name' => 'required|max:255|unique:sections,section_name,'.$id,
            'description' => 'required',
        ],[

            'section_name.required' =>'يرجي ادخال اسم القسم',
            'section_name.unique' =>'اسم القسم مسجل مسبقا',
            'description.required' =>'يرجي ادخال البيان',

        ]);

        $sections = sections::find($id);
        $sections->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
        ]);

        session()->flash('Edit','تم تعديل القسم بنجاج');
        return redirect('/sections');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        sections::find($id)->delete();
        session()->flash('delete','تم حذف القسم بنجاح');
        return redirect('/sections');
    }
}
