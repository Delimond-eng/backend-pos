<?php

namespace App\Jobs;

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
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessJsonDataJob implements ShouldQueue
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        if ($this->data) {
            $tables = $this->data;
            if(isset($tables['currencies'])) foreach ($tables['currencies'] as $data) Currencie::updateOrCreate($data);
            if(isset($tables['items'])) foreach ($tables['items'] as $data) Item::updateOrCreate($data);
            if(isset($tables['item_natures'])) foreach ($tables['item_natures'] as $data) ItemNature::updateOrCreate($data);
            if(isset($tables['users'])) foreach ($tables['users'] as $data) User::updateOrCreate($data);
            if(isset($tables['comptes'])) foreach ($tables['comptes'] as $data) Compte::updateOrCreate($data);
            if(isset($tables['clients'])) foreach ($tables['clients'] as $data) Client::updateOrCreate($data);
            if(isset($tables['factures'])) foreach ($tables['factures'] as $data) Facture::updateOrCreate($data);;
            if(isset($tables['facture_details'])) foreach ($tables['facture_details'] as $data) FactureDetail::updateOrCreate($data);
            if(isset($tables['produits'])) foreach ($tables['produits'] as $data) Produit::updateOrCreate($data);
            if(isset($tables['entrees'])) foreach ($tables['entrees'] as $data) Entree::updateOrCreate($data);
            if(isset($tables['sorties'])) foreach ($tables['sorties'] as $data) Sortie::updateOrCreate($data);
            if(isset($tables['operations'])) foreach ($tables['operations'] as $data) Operation::updateOrCreate($data);
        }
    }
}
