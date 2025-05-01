<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessJsonDataJob;
use App\Models\Client;
use App\Models\Compte;
use App\Models\Currencie;
use App\Models\Entree;
use App\Models\Facture;
use App\Models\FactureDetail;
use App\Models\Item;
use App\Models\ItemNature;
use App\Models\Operation;
use App\Models\Produit;
use App\Models\Sortie;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Queue;
use Illuminate\Http\Request;

class JsonDataController extends Controller
{

    /**
     * Recuperer le fichier json contenant les données et les stocker dans le serveur
     * @param Request $request
     * @return JsonResponse
    */
    public function syncIn(Request $request): \Illuminate\Http\JsonResponse
    {
        $jsonData = json_decode(file_get_contents($request->file('jsonfile')), true);
        if ($jsonData) {
            Queue::push(new ProcessJsonDataJob($jsonData));
            return response()->json([
                'status' => 'success',
                'message' => 'Synchronisation en cours de traitement...'
            ], 200);
        }
        return response()->json(['error' => 'Aucune donnée JSON reçue'], 400);
    }

    /**
     * Renvoie toutes les données pour la synchronisation
     * @return JsonResponse
    */
    public function syncOut():JsonResponse{
        $users = User::all();
        $currencies = Currencie::all();
        $comptes = Compte::all();
        $clients = Client::all();
        $factures = Facture::all();
        $factureDetails = FactureDetail::all();
        $items = Item::all();
        $itemNatures = ItemNature::all();
        $operations = Operation::all();
        $produits = Produit::all();
        $sorties = Sortie::all();
        $entrees = Entree::all();

        return response()->json([
            "status"=>"success",
            "response"=>[
                "users"=>$users,
                "currencies"=>$currencies,
                "comptes"=>$comptes,
                "clients"=>$clients,
                "factures"=>$factures,
                "facture_details"=>$factureDetails,
                "items"=>$items,
                "item_natures"=>$itemNatures,
                "operations"=>$operations,
                "produits"=>$produits,
                "entrees"=>$entrees,
                "sorties"=>$sorties
            ]
        ]);
    }
}
