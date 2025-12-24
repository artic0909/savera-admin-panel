<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;
    protected $status;

    public function __construct($startDate = null, $endDate = null, $status = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Order::with('customer');

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Order Number',
            'Customer Name',
            'Status',
            'Total Amount',
            'Payment Method',
            'Payment Status',
            'Created At'
        ];
    }

    public function map($order): array
    {
        return [
            $order->order_number,
            $order->customer->name ?? 'N/A',
            ucfirst($order->status),
            $order->total,
            strtoupper($order->payment_method),
            ucfirst($order->payment_status),
            $order->created_at->format('Y-m-d H:i:s')
        ];
    }
}
