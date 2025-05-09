<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .title { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px; }
        .sale-box { margin-bottom: 30px; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        .sale-info { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <div class="title">Rapport des ventes @if($date) du {{ $date }} @endif</div>

    @foreach ($sales as $sale)
        <div class="sale-box">
            <div class="sale-info">
                <strong>Vente ID :</strong> {{ $sale->id }}<br>
                <strong>Date :</strong> {{ $sale->created_at->format('d/m/Y H:i') }}<br>
                <strong>Vendeur :</strong> {{ $sale->user->name ?? 'N/A' }}
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix Unitaire</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }} FC</td>
                            <td>{{ number_format($item->unit_price * $item->quantity, 2) }} FC</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <strong>Total de la vente :</strong>
            {{ number_format($sale->items->sum(fn($i) => $i->unit_price * $i->quantity), 2) }} FC
        </div>
    @endforeach
</body>
</html>
