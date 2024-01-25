<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use Illuminate\Http\Request;

class InvoicesReportController extends Controller
{
    public function index(){
        return view('reports.invoices_report');

    }
    public function search_invoices(Request $request){
       $rdio = $request->rdio;
       // البحث بنوع الفاتورة

         if ($rdio == 1) {
            // في حالة عدم تحديد التاريخ
            if ($request->type && $request->start_at =='' && $request->end_at =='') {
                 $invoices = Invoices::select('*')->where('status','=',$request->type)->get();
                 $type =$request->type;
                 return view('reports.invoices_report',compact('type'))->withDetails($invoices);
            }
            // في حالة تحديد تاريخ استحقاق
            else {
               $start_at = $request->start_at;
               $end_at = $request->end_at;
               $type =$request->type;
               $invoices =Invoices::whereBetween('invoice_date',[$start_at,$end_at])->where('status','=',$request->type)->get();
               return view('reports.invoices_report',compact('type','start_at','end_at'))->withDetails($invoices);
            }
        }
//=======================================================
 // البحث برقم الفاتورة
         else {
           $invoices = Invoices::select('*')->where('invoice_number','=',$request->invoice_number)->get();
           return view('reports.invoices_report')->withDetails($invoices);
         }
    }


}
