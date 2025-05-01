<?php

namespace App\Http\Controllers;

use App\Models\Currencie;
use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if(Auth::user()->role ==='gestionnaire stock'){
            return view('stockage', [
                "title" => "stockage"
            ]);
        }
        else{
            return view('dashboard', [
                "title"=>"Dashboard"
            ]);
        }

    }

    /**
     * Show the application clients manage page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function clientsManage()
    {
        return view('clients', [
            "title"=>"Clients"
        ]);
    }


     /**
     * Show the application facture manage
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function facturesManage()
    {
        return view('factures', [
            "title"=>"Factures"
        ]);
    }


     /**
     * Show the application paiements manager
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function paiementsManage()
    {
        return view('paiements', [
            "title"=>"Paiements"
        ]);
    }


    /**
     * Show the application users manager
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function usersManage()
    {
        return view('users', [
            "title"=>"Utilisateurs"
        ]);
    }

     /**
     * Show the application users manager
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function accountingManage()
    {
        return view('accounting', [
            "title"=>"Compte"
        ]);
    }


      /**
     * Show the application invoice create
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function invoiceCreate()
    {
        return view('invoice', [
            "title"=>"Facturation"
        ]);
    }

      /**
     * Show the application invoice create
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function invoicePreview(int $id)
    {
        $latestFacture = Facture::join('clients', 'factures.client_id', '=', 'clients.id')
                    ->with('details')
                    ->with('client')
                    ->select('factures.*')
                    ->where('factures.facture_state', 'allowed')
                    ->where('factures.id', $id)
                    ->first();
                    $currencie = Currencie::all()->last();
        if($latestFacture){
            return view('invoicepreview', [
            "title"=>"Impression facture",
            "data"=>$latestFacture,
            "currencie"=>$currencie['currencie_value']
        ]);
        }else{
            return response()->view('errors.404', [], 404);
        }
    }
      /**
     * Show the application stockage manage
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function stockageManage()
    {
        return view('stockage', [
            "title"=>"Stockage"
        ]);
    }
      /**
     * Show the application invoice create
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function configManage()
    {
        return view('config', [
            "title"=>"Configuration"
        ]);
    }
      /**
     * Show the application invoice create
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function inventoriesManage()
    {
        return view('inventories', [
            "title"=>"Inventories"
        ]);
    }

}