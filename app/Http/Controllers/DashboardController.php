<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Currencie;
use App\Models\Facture;
use App\Models\Operation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psy\Util\Json;

class DashboardController extends Controller
{
    /**
     * Compte les donnees journaliers des ventes
     * @return JsonResponse
    */
    public function dashCounter():JsonResponse
    {
        try {
            $counts = [];
            $countData = [
                'daily' => [
                    'title'=>'Factures journalières',
                    'icon'=>'assets/icons/calendar-check.svg'
                ],
                'pending' => [
                    'title'=>'Factures en attente',
                    'icon'=>'assets/icons/doc1.svg'
                ],
                'costumers' => [
                    'title'=>'Clients',
                    'icon'=>'assets/icons/users.svg'
                ],
                'completed' => [
                    'title'=>'Factures payées',
                    'icon'=>'assets/icons/doc2.svg'
                ],
            ];
            foreach ($countData as $key => $data) {
                $count = $this->count($key);
                $counts[] = [
                    'title' => $data['title'],
                    'icon'=>$data['icon'],
                    'count' => $count,
                ];
            }
            $dayPayments = Array();
            $paymentTypes = ['Cash', 'Paiement mobile', 'Virement', 'Chèque'];
            foreach ($paymentTypes as $type) {
                $sum = $this->sum($type);
                $dayPayments[] = [
                    'title' => "Paiement $type",
                    'sum' => $sum,
                ];
            }
            $singleCount = $this->singleCounter();
            $currencieObj = Currencie::all()->first();
            return response()->json([
                "day_count"=>round($singleCount, 2),
                "dash_counts" => $counts,
                "day_payments"=>$dayPayments,
                "currencie"=> isset($currencieObj) ? $currencieObj['currencie_value'] : 0
            ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(["errors"=>$e->getMessage()]);
        }
    }

    /**
     * Dashboard single count
     * @return float
    */
    private function singleCounter():float
    {
        $dateNow = Carbon::now()->toDateString();
        $count = Operation::whereDate('operation_create_At', $dateNow)
            ->where('operation_state', 'allowed')
            ->sum('operation_montant');
        return $count;
    }


    /**
     * Count dashboard
     * @return int
    */
    private function count($key):int
    {
        $dateNow = Carbon::now()->toDateString();
        return match ($key){
            "daily"=> Facture::join('clients', 'factures.client_id', '=', 'clients.id')
                ->whereDate('factures.facture_create_At',  $dateNow)
                ->where('factures.facture_state', 'allowed')
                ->where('clients.client_state', 'allowed')
                ->count(),
            "pending"=> Facture::join('clients', 'factures.client_id', '=', 'clients.id')
                ->where('factures.facture_status', 'en attente')
                ->where('factures.facture_state', 'allowed')
                ->where('clients.client_state', 'allowed')
                ->count(),
            "costumers"=> Client::where('client_state', 'allowed')->count(),
            "completed"=> Facture::join('clients', 'factures.client_id', '=', 'clients.id')
                ->where('factures.facture_status', 'paie')
                ->where('factures.facture_state', 'allowed')
                ->where('clients.client_state', 'allowed')
                ->count(),
            default=>null
        };
    }

    private function sum($mode):mixed{
        $dateNow = Carbon::now()->toDateString();
        return Operation::where('operation_mode', $mode)
            ->whereDate('operation_create_At', $dateNow)
            ->where('operation_state', 'allowed')
            ->sum('operation_montant');
    }


}