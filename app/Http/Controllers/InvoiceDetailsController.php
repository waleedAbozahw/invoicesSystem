<?php

namespace App\Http\Controllers;

use App\Models\invoice_attachments;
use App\Models\Invoice_details;
use App\Models\Invoices;
use Dotenv\Store\StoreBuilder;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice_details $invoice_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $invoices = Invoices::where('id',$id)->first();
        $details = Invoice_details::where('id_invoice',$id)->get();
        $attachments =invoice_attachments::where('invoice_id',$id)->get();
        return view('invoices.details_invoices',compact('invoices','details','attachments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice_details $invoice_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
       $invoices = invoice_attachments::findOrFail($request->id_file);
       $invoices->delete();
       Storage::disk('uploads')->delete($request->invoice_number.'/'.$request->file_name);
       session()->flash('delete','تم حذف المرفق بنجاح');
       return back();
    }

    public function get_file($invoice_number,$file_name){
        // $content = Storage::disk('uploads')->get($invoice_number.'/'.$file_name);
        // return response()->download($content);
        // return Storage::fileSize($invoice_number.'/'.$file_name);
        // return Storage::download($invoice_number.'/'.$file_name);


    }

    public function open_file($invoice_number,$file_name){
        // $files = Storage::disk('uploads')->getDriver()->getAdapter()->applyPathPrefix($invoice_number.'/'.$file_name);
        // return response()->file($files);
       // return   asset('uploads/'.$invoice_number.'/'.$file_name);
    //    $files = Storage::disk('uploads')->get($invoice_number.'/'.$file_name);
    //     return deflate_add($files);
    // $file ='<img src ="asset($invoice_number/$file_name)" width=300px height=300px>' ;
    // return $file;




    }
//     public function rootPath()
//   {
//     $adapter = $this->disk('uploads')->getDriver()->getAdapter();

//     if ($adapter instanceof InvoiceDetailsController) {
//       $adapter = $adapter->getAdapter();
//     }

//     return $adapter->getPathPrefix();
//     return $this->path();
//   }
}
