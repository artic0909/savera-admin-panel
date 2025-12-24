<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\InventoryExport;
use App\Exports\OrdersExport;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function export(Request $request)
    {
        $type = $request->query('type', 'orders');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $status = $request->query('status');

        $fileName = $type . '_report_' . now()->format('YmdHis') . '.xlsx';

        if ($type === 'inventory') {
            return Excel::download(new InventoryExport($startDate, $endDate), $fileName);
        }

        if ($type === 'sales') {
            return Excel::download(new SalesReportExport($startDate, $endDate), $fileName);
        }

        // For orders, returns, cancellations - using the same export class with status filter
        return Excel::download(new OrdersExport($startDate, $endDate, $status), $fileName);
    }
}
