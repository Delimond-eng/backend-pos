<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PurchaseItem;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{

    public function __construct(){
        $this->middleware("auth");
    }
    public function downloadSalesReport(Request $request)
    {
        $date = $request->query('date');
        $sales = Sale::with(['items.product', 'user'])
            ->when($date, fn($query) => $query->whereDate('created_at', $date))
            ->orderByDesc('created_at')
            ->get();

        $pdf = Pdf::loadView('pdfs.sales', [
            'sales' => $sales,
            'date' => $date,
        ])->setPaper('A4', 'portrait');

        return $pdf->download('rapport_ventes.pdf');
    }

    public function downloadApproStories(Request $request)
    {
        $queryDate = $request->query("date");

        $purchases = PurchaseItem::with(["purchase.user", "product"])
            ->when($queryDate, fn($query) => $query->whereDate('created_at', $queryDate))
            ->orderByDesc("id")
            ->get();

        $pdf = Pdf::loadView('pdfs.purchases', [
            'purchases' => $purchases,
            'date' => $queryDate
        ])->setPaper('A4', 'portrait');

        return $pdf->download('liste_approvisionnements.pdf');
    }
}
