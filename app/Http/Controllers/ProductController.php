<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sections;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Sections::all();
        $products = Product::all();
        return view('products.products',compact('sections','products'));
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
        // validation
       $validate = $request->validate([
        'product_name' => 'required',
        'section_id' => 'required',
       ],[
            'product_name.required' => 'يرجي ادخال اسم المنتج',
            'section_id.required' => 'يرجي تحديد القسم',

       ]);
       Product::create([
        'product_name'=> $request->product_name,
        'section_id'=> $request->section_id,
        'description'=> $request->description,
       ]);
       session()->flash('Add', 'تم اضافة المنتج بنجاح');
       return redirect('/products');

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = Sections::where('section_name', $request->section_name)->first()->id;
        $products = Product::findOrFail($request->pro_id);

        $products->update([
            'product_name'=> $request->product_name,
            'section_id'=> $id,
            'description'=> $request->description,
        ]);
        session()->flash('Edit','تم تعديل المنتج بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $products = Product::findOrFail($request->pro_id);
        $products->delete();
        session()->flash('delete','تم حذف المنتج بنجاح');
        return back();
    }
}
