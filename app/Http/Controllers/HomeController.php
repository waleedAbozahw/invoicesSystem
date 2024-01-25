<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       $all_invoices=Invoices::count();
       $invoices2=Invoices::where('value_status',2)->count();
       if (!empty($all_invoices) && !empty($invoices2) ) {
            $percent_invoices2=round($invoices2/$all_invoices*100);

            $invoices1=Invoices::where('value_status',1)->count();
            $percent_invoices1=round($invoices1/$all_invoices*100);

            $invoices3=Invoices::where('value_status',3)->count();
            $percent_invoices3=round($invoices3/$all_invoices*100);

            $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 340, 'height' => 200])
            ->labels(['الفواتير الغير مدفوعة','الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    "label" => 'الفواتير الغير مدفوعة',
                    'backgroundColor' => ['red','green','orange'],
                    'data' => [$percent_invoices2]
                ],
                [
                    "label" => 'الفواتير المدفوعة',
                    'backgroundColor' => ['green',],
                    'data' => [$percent_invoices1]
                ],
                [
                    "label" => 'الفواتير المدفوعة جزئيا',
                    'backgroundColor' => ['orange',],
                    'data' => [$percent_invoices3]
                ]
            ])
            ->options([]);

            $chartjs_2 = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 340, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    'backgroundColor' => ['red', 'green','orange'],
                    'data' => [$percent_invoices2, $percent_invoices1,$percent_invoices3]
                ]
            ])
            ->options([]);

            return view('home', compact('chartjs','chartjs_2'));
        }else {
            $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 340, 'height' => 200])
            ->labels(['الفواتير الغير مدفوعة','الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    "label" => 'الفواتير الغير مدفوعة',
                    'backgroundColor' => ['red','green','orange'],
                    'data' => [0]
                ],
                [
                    "label" => 'الفواتير المدفوعة',
                    'backgroundColor' => ['green',],
                    'data' => [0]
                ],
                [
                    "label" => 'الفواتير المدفوعة جزئيا',
                    'backgroundColor' => ['orange',],
                    'data' => [0]
                ]
            ])
            ->options([]);

            $chartjs_2 = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 340, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    'backgroundColor' => ['red', 'green','orange'],
                    'data' => [0, 0,0]
                ]
            ])
            ->options([]);

            return view('home', compact('chartjs','chartjs_2'));
        }



    }
}
