<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Compte;
use App\Models\Currencie;
use App\Models\Facture;
use App\Models\FactureDetail;
use App\Models\Item;
use App\Models\ItemNature;
use App\Models\Operation;
use App\Models\Produit;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PublicController extends Controller
{
    /**
     * View all configs data for application
     * @return JsonResponse
    */
    public function viewAllConfigs():JsonResponse
    {
        try{
            $users = User::all();
            $currencie = Currencie::all()->last();
            $allAccounts = Compte::where('compte_state', 'allowed')->get();
            $activatedAccounts = Compte::where('compte_state', 'allowed')->where('compte_status', 'actif')->get();
            $products = Produit::where('produit_state', 'allowed')->orderByDesc('id')->get();
            $items = Item::with(['natures'=>function($query){
                $query->where('item_nature_state', 'allowed');
            }])->where('item_state', 'allowed')->orderByDesc('id')->get();
            return response()->json([
                "status"=>"success",
                "users"=>$users,
                "currencie"=>$currencie ?? ["currencie_value"=>0, "id"=>0],
                "all_comptes"=>$allAccounts,
                "activated_comptes"=>$activatedAccounts,
                "produits"=>$products,
                "items"=>$items,
            ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json([
                "errors"=>$e->getMessage()
            ]);
        }
    }


    /**
     * Create new costumers
     * @param Request $request
     * @return JsonResponse
    */
    public function createClient(Request $request):JsonResponse
    {
        try {
            $data = $request->validate([
                "client_nom"=>"required|string",
                'client_tel'=>"nullable|string",
                'client_adresse'=>"nullable|string",
            ]);
            $data['user_id'] = Auth::id();
            $client = Client::create($data);
            return response()->json([
                "status"=>"success",
                "result"=>$client
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage() ]);
        }
    }


    /**
     * view all clients
     * @return JsonResponse
    */
    public function viewAllClients():JsonResponse
    {
        $clients = Client::with(["factures.details", "factures.paiements"=>function ($query) {
            $query->where('operation_state', 'allowed');
        }, "factures.paiements.user"])
            ->where("client_state", "allowed")->orderByDesc('id')->get();
        return response()->json([
            "status"=>"success",
            "clients"=>$clients
        ]);
    }

    /**
     * create new Facture
     * @param Request $request
     * @return JsonResponse
     */
    public function createFacture(Request $request):JsonResponse
    {
        try {
            $data = $request->validate([
                'facture_montant'=>'required|string',
                'facture_devise'=>'required|string',
                'client_id'=>'required|int',
                'facture_details'=>'required|array',
                'facture_details.*.facture_detail_libelle'=>'required|string',
                'facture_details.*.facture_detail_qte'=>'required|int',
                'facture_details.*.facture_detail_pu'=>'required|numeric',
                'facture_details.*.facture_detail_nature'=>'required|string',
                'facture_details.*.facture_detail_devise'=>'required|string',
            ]);
            $data["user_id"] = Auth::id();
            $details = $data['facture_details'];
            $facture = Facture::create($data);
            if(isset($facture)){
                foreach ($details as $detail){
                    $detail['facture_id'] = $facture->id;
                    FactureDetail::create($detail);
                }
                $latestFacture = Facture::join('clients', 'factures.client_id', '=', 'clients.id')
                    ->with('details')
                    ->with('client')
                    ->select('factures.*')
                    ->where('factures.facture_state', 'allowed')
                    ->where('factures.id', $facture->id)
                    ->first();
                return response()->json([
                    "status"=>"success",
                    "result"=>$facture,
                    "data"=>$latestFacture
                ]);
            }
            else{
                return response()->json([
                    "status"=>"failed",
                    "message"=>"Echec de la creation de la facture !"
                ]);
            }
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage() ]);
        }
    }



    /**
    * view all factures
     * @return JsonResponse
    */
    public function viewAllFactures($key):JsonResponse
    {
        $results = match ($key) {
            'all' => Facture::join('clients', 'factures.client_id', '=', 'clients.id')
                ->with('details')
                ->with('client')
                ->with('user')
                ->with(['paiements' => function ($query) {
                    $query->where('operation_state', 'allowed');
                }])
                ->select('factures.*')
                ->where('factures.facture_state', 'allowed')
                ->orderByDesc('factures.id')
                ->get(),
            'pending' => Facture::join('clients', 'factures.client_id', '=', 'clients.id')
                ->with('details')
                ->with('client')
                ->with('user')
                ->with(['paiements' => function ($query) {
                    $query->where('operation_state', 'allowed');
                }])
                ->select('factures.*')
                ->where(function ($query) {
                    $query->where('factures.facture_status', 'en cours')
                        ->orWhere('factures.facture_status', 'en attente');
                })
                ->where('factures.facture_state', 'allowed')
                ->orderByDesc('factures.id')
                ->get(),
            'completed' => Facture::join('clients', 'factures.client_id', '=', 'clients.id')
                ->with('details')
                ->with('client')
                ->with('user')
                ->with(['paiements' => function ($query) {
                    $query->where('operation_state', 'allowed');
                }])
                ->select('factures.*')
                ->where('factures.facture_status', 'paie')
                ->where('factures.facture_state', 'allowed')
                ->orderByDesc('factures.id')
                ->get(),
            default => null,
        };
        return response()->json([
            "status"=>"success",
            "results"=>$results
        ]);
    }


    /**
     * make paiement for facture
     * @param Request $request
     * @return JsonResponse
     *
    */
    public function makePayment(Request $request) :JsonResponse
    {
        try {
            $data = $request->validate([
                'operation_montant'=>'required|numeric',
                'operation_devise'=>'required|string',
                'operation_mode'=>'required|string',
                'facture_id'=>'required|int|exists:factures,id',
                'compte_id'=>'required|int|exists:comptes,id'
            ]);
            $facture = Facture::findOrFail($data['facture_id']);
            $lastPay = $this->lastPayment($data['facture_id']);
            $factureAmount = (double)$facture->facture_montant;
            $operationAmount = (double)$data['operation_montant'];
            $verifyAmount = 0;
            if($lastPay > 0){
               $verifyAmount = $factureAmount - $operationAmount;
               if($verifyAmount < 0){
                   return response()->json(['errors' => "Le montant de paiement saisi dépasse le frais de la facture sélectionnée !" ]);
               }
               else{
                   $restToPay = $factureAmount - $lastPay;
                   $verifyAmount = $restToPay - $operationAmount;
                   if ($restToPay == 0){
                       return response()->json(['errors' => "Cette facture a été déjà payé à la totalité !" ]);
                   }
                   if($verifyAmount < 0){
                       return response()->json(['errors' => "Le montant de paiement saisi dépasse le frais restant de la facture sélectionnée !" ]);
                   }
               }
            }
            else{
                $verifyAmount = $factureAmount - $operationAmount;
                if($verifyAmount < 0){
                    return response()->json(['errors' => "Le montant de paiement saisi dépasse le frais de la facture sélectionnée !" ]);
                }
            }

            $payment = Operation::create([
                'operation_libelle'=>"paiement facture",
                'operation_montant'=>$operationAmount,
                'operation_devise'=>$data['operation_devise'],
                'operation_type'=>'entrée',
                'operation_mode'=>$data['operation_mode'],
                'user_id'=>Auth::id(),
                'facture_id'=>$data['facture_id'],
                'compte_id'=>$data['compte_id']
            ]);
            if (isset($payment)){
                if($verifyAmount ==0){
                    $facture = Facture::findOrFail($data['facture_id']);
                    $facture->facture_status = 'paie';
                    $facture->save();
                }
                return response()->json([
                    "status"=>"success",
                    "result"=>$payment
                ]);
            }
            else{
                return response()->json([
                    "status"=>"failed",
                    "message"=>"Echec de traitement de paiement"
                ]);
            }
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage() ]);
        }
    }

    /**
     * Voir les paiements
     * @return JsonResponse
    */
    public function allPaiements($key, $date=null):JsonResponse
    {
        try {
            $results = match ($key){
                'all'=> DB::table('factures')
                    ->selectRaw('SUM(operations.operation_montant) AS totalPay, factures.id AS facture_id,MAX(factures.facture_montant) AS facture_montant,MAX(factures.facture_devise) AS facture_devise,MAX(factures.facture_status) AS facture_status, MAX(clients.id) AS client_id, MAX(clients.client_nom) AS client_nom,MAX(DATE(operations.operation_create_At)) AS operation_create_At,MAX(operations.operation_type) AS operation_type,MAX(operations.operation_libelle) AS operation_libelle,MAX(operations.operation_devise) AS operation_devise, MAX(comptes.id) AS compte_id,MAX(comptes.compte_libelle) AS compte_libelle, MAX(users.id) AS user_id,MAX(users.name) AS user_name')
                    ->join('operations', 'factures.id', '=', 'operations.facture_id')
                    ->join('clients', 'factures.client_id', '=', 'clients.id')
                    ->join('comptes', 'operations.compte_id', '=', 'comptes.id')
                    ->join('users', 'operations.user_id', '=', 'users.id')
                    ->where('operations.operation_state', 'allowed')
                    ->groupBy('factures.id')
                    ->orderByDesc('operations.facture_id')
                    ->get(),
                'date'=>DB::table('factures')
                    ->selectRaw('SUM(operations.operation_montant) AS totalPay, factures.id AS facture_id,MAX(factures.facture_montant) AS facture_montant, MAX(factures.facture_devise) AS facture_devise,MAX(factures.facture_status) AS facture_status,MAX(clients.id) AS client_id, MAX(clients.client_nom) AS client_nom,MAX(DATE(operations.operation_create_At)) AS operation_create_At,MAX(operations.operation_type) AS operation_type,MAX(operations.operation_libelle) AS operation_libelle,MAX(operations.operation_devise) AS operation_devise, MAX(comptes.id) AS compte_id,MAX(comptes.compte_libelle) AS compte_libelle, MAX(users.id) AS user_id,MAX(users.name) AS user_name')
                    ->join('operations', 'factures.id', '=', 'operations.facture_id')
                    ->join('clients', 'factures.client_id', '=', 'clients.id')
                    ->join('comptes', 'operations.compte_id', '=', 'comptes.id')
                    ->join('users', 'operations.user_id', '=', 'users.id')
                    ->where('operations.operation_state', 'allowed')
                    ->whereDate('operations.operation_create_At', $date )
                    ->groupBy('factures.id', 'factures.client_id', 'clients.id')
                    ->orderByDesc('operations.facture_id')
                    ->get(),
                default=>null

            };
            return response()->json([
                "status"=>"success",
                "results"=>$results
            ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json([
                "errors"=>$e->getMessage()
            ]);
        }

    }


    /**
     * Voir les details de paiements d'une facture
     * @param int $factureId
     * @return JsonResponse
    */
    public function getPaiementDetails(int $factureId):JsonResponse
    {
        $results = Operation::with('facture.client')
            ->with('user')
            ->with('compte')
            ->where('operation_state', 'allowed')
            ->where('facture_id', $factureId)
            ->get();
        return response()->json([
            "status"=>"success",
            "details"=>$results
        ]);
    }

    /**
     * Delete from specify database
     * @return JsonResponse
    */
    public function triggerDelete(Request $request):JsonResponse
    {
        try {
            $data = $request->validate([
                'table'=>'required|string',
                'id'=>'required|int',
                'id_field'=>'nullable|string',
                'state'=>'required|string'
            ]);
            $field = $data['id_field'] ?? 'id';
            $result = DB::table($data['table'])
                ->where($field, $data['id'])
                ->update([$data['state'] => 'deleted']);
            return response()->json([
                "status"=>"success",
                "result"=>$result
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage() ]);
        }
    }


    /**
     * disable accounts
     * @return JsonResponse
    */
    public function disableData(Request $request):JsonResponse
    {
        try {
            $data = $request->validate([
                'table'=>'required|string',
                'id'=>'required|int',
                'id_field'=>'nullable|string',
                'state'=>'required|string',
                'state_val'=>'required|string',
            ]);
            $field = $data['id_field'] ?? 'id';
            $result = DB::table($data['table'])
                ->where($field, $data['id'])
                ->update([$data['state'] =>$data['state_val']]);
            return response()->json([
                "status"=>"success",
                "result"=>$result
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage() ]);
        }
    }

    /**
     * Voir un detail d'un paiement
     * @return JsonResponse
    */
    public function getPayDetails($filter):JsonResponse
    {
        $date = Carbon::createFromFormat('Y-m-d', $filter)->startOfDay()->timestamp;
        $results = Facture::join('operations', 'factures.id', '=', 'operations.facture_id')
            ->join('clients', 'factures.client_id', '=', 'clients.id')
            ->select(DB::raw('SUM(operations.operation_montant) AS totalPay'), 'factures.*')
            ->whereDate('operations.operation_state', 'allowed')
            ->where('operations.operation_create_At',now()->toDateString())
            ->groupBy('operations.facture_id')
            ->orderByDesc('operations.id')
            ->get();
        return response()->json([
            "status"=>"success",
            "results"=>$results
        ]);
    }


    private function lastPayment($factureId):mixed
    {
        $lastAmount = Operation::join('factures', 'operations.facture_id', '=', 'factures.id')
            ->where('operations.facture_id', $factureId)
            ->whereNotIn('operations.operation_state', ['deleted'])
            ->sum('operations.operation_montant');

        return $lastAmount ?? 0;
    }
}