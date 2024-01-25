<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Models\invoice_attachments;
use App\Models\Invoice_details;
use App\Models\Invoices;
use App\Models\Sections;
use App\Models\User;
use App\Notifications\Add_invoice;
use App\Notifications\InvoicePay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $invoices = Invoices::all();
       return view('invoices.invoices',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Sections::all();
        return view('invoices.add_invoice',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Invoices::create([
        'invoice_number'=>$request->invoice_number,
        'invoice_date'=>$request->invoice_date,
        'due_date'=>$request->due_date,
        'product'=>$request->product,
        'section_id'=>$request->section,
        'amount_collection'=>$request->amount_collection,
        'amount_commission'=>$request->amount_commission,
        'discount'=>$request->discount,
        'value_vat'=>$request->value_vat,
        'rate_vat'=>$request->rate_vat,
        'total'=>$request->total,
        'status'=>'غير مدفوعة',
        'value_status'=>2,
        'note'=>$request->note,
       ]);

       $invoice_id =Invoices::latest()->first()->id;
       Invoice_details::create([
        'id_invoice'=>$invoice_id,
        'invoice_number'=>$request->invoice_number,
        'product'=>$request->product,
        'section'=>$request->section,
        'status'=>'غير مدفوعة',
        'value_status'=>2,
        'note'=>$request->note,
        'user'=>(Auth::user()->name),
       ]);

       if ($request->hasFile('pic')) {
            $invoice_id =Invoices::latest()->first()->id;
            $image = $request->file('pic');
            $file_name=$image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new invoice_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->created_by = Auth::user()->name;
            $attachments->invoice_id =  $invoice_id ;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/'.$invoice_number),$imageName);
       }
       $user = User::get();
       $invoices =Invoices::latest()->first();
      // $user->notify(new \App\Notifications\Add_invoice($invoice_id));

    //    $user = User::first();
    //    //$user->notify(new InvoicePay($invoice_id));
        Notification::send($user, new Add_invoice($invoices));

       session()->flash('Add','تم اضافة الفاتورة بنجاح');
       return redirect('/invoices');

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
       $invoices = Invoices::where('id',$id)->first();
       return view('invoices.status_update',compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
       $invoices = Invoices::where('id',$id)->first();
       $sections = Sections::all();
       return view('invoices.edit_invoice',compact('sections','invoices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoices $invoices)
    {
        $invoices = Invoices::findOrFail($request->invoice_id);
        $invoices->update([
        'invoice_number'=>$request->invoice_number,
        'invoice_date'=>$request->invoice_date,
        'due_date'=>$request->due_date,
        'product'=>$request->product,
        'section_id'=>$request->section,
        'amount_collection'=>$request->amount_collection,
        'amount_commission'=>$request->amount_commission,
        'discount'=>$request->discount,
        'value_vat'=>$request->value_vat,
        'rate_vat'=>$request->rate_vat,
        'total'=>$request->total,
        'note'=>$request->note,
        ]);
        session()->flash('edit','تم تعديل الفاتورة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
       $id = $request->invoice_id;

       $invoices = Invoices::where('id',$id)->first();
       $details = invoice_attachments::where('invoice_id',$id)->first();
       $id_page = $request->id_page;
       if(!$id_page == 2 )
       {
       if (!empty($details->invoice_number)) {
          Storage::disk('uploads')->deleteDirectory($details->invoice_number);
       }

       $invoices->forceDelete();

       session()->flash('delete_invoice');
       return redirect('/invoices');

        }else{

           $invoices->delete();
            session()->flash('archive_invoice');
            return redirect('/invoices');

        }
    }
    public function getProducts($id){
       $products = DB::table('products')->where('section_id',$id)->pluck('product_name','id');
       return json_encode($products);
    }

    public function status_update($id,Request $request){
         $invoices = Invoices::findOrFail($id);
         if ($request->status === 'مدفوعة') {
              $invoices->update([
                  'value_status' => 1,
                  'status' => $request->status,
                  'payment_date' => $request->payment_date,
              ]);
              Invoice_details::create([
               'id_invoice'=>$request->invoice_id,
               'invoice_number'=>$request->invoice_number,
               'product'=>$request->product,
               'section'=>$request->section,
               'status'=>$request->status,
               'value_status'=> 1,
               'note' => $request->note,
               'payment_date' => $request->payment_date,
               'user'=>(Auth::user()->name),

              ]);
         }else{
            $invoices->update([
               'value_status' => 3,
               'status' => $request->status,
               'payment_date' => $request->payment_date,
           ]);
           Invoice_details::create([
            'id_invoice'=>$request->invoice_id,
            'invoice_number'=>$request->invoice_number,
            'product'=>$request->product,
            'section'=>$request->section,
            'status'=>$request->status,
            'value_status'=> 3,
            'note' => $request->note,
            'payment_date' => $request->payment_date,
            'user'=>(Auth::user()->name),

           ]);

         }
         session()->flash('status_update');
         return redirect('/invoices');
    }
    public function paid_invoices(){
        $invoices = Invoices::where('value_status',1)->get();
        return view('invoices.paid_invoices',compact('invoices'));
    }
    public function non_paid_invoices(){
        $invoices = Invoices::where('value_status',2)->get();
        return view('invoices.non_paid_invoices',compact('invoices'));
    }

    public function partial_paid_invoices(){
        $invoices = Invoices::where('value_status',3)->get();
        return view('invoices.partial_paid_invoices',compact('invoices'));
    }

    public function print_invoice($id){
      $invoices = Invoices::where('id',$id)->first();
      return view('invoices.print_invoice',compact('invoices'));
    }

    public function export()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');

    }

    public function MarkAsRead_all(Request $request)
    {
        $userUnreadNotification = auth()->user()->unreadNotifications;

        if($userUnreadNotification){
            $userUnreadNotification->markAsRead();
            return back();
        }
    }


}
