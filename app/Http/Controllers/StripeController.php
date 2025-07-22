<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function createSubscription(Request $request, string $plan = 'prod_S68PSBgXB9WyqZ')
    {
        $checkout = $request->user()
            ->newSubscription('prod_S68PSBgXB9WyqZ', 'price_1RBw8nRoBgIyV2CocrANfHgb')
            // ->trialDays(5)
            // ->allowPromotionCodes()
            ->checkout([
                'success_url' => 'http://localhost/stripe/success',
                'cancel_url' => 'http://localhost/stripe/cancel',
            ]);

        return response()->json(['url' => $checkout->url]);
    }
}
