<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Srmklive\PayPal\Services\PayPal;

class SubscriptionController extends Controller
{
    public function pricingPage()
    {
        return Inertia::render('Pricing/Index');
    }

    public function subscribe(Request $request)
    {
        $provider = new PayPal();
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        // Création de la commande PayPal (5.00 EUR pour 1 mois)
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success'),
                "cancel_url" => route('pricing'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "EUR",
                        "value" => "5.00"
                    ],
                    "description" => "MotoRaid Premium - 1 Mois"
                ]
            ]
        ]);

        // Redirection vers la page de paiement PayPal
        if (isset($response['links'][1]['href'])) {
            return redirect($response['links'][1]['href']);
        }

        return redirect()->route('pricing')->with('error', 'Erreur de connexion à PayPal. Vérifiez vos clés API dans le fichier .env');
    }

    public function paypalSuccess(Request $request)
    {
        $provider = new PayPal();
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        // Capture du paiement
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $user = $request->user();
            $user->role = 'premium';
            $user->save();

            return redirect()->route('dashboard')->with('success', 'Félicitations ! Vous êtes maintenant MotoRaid Premium.');
        }

        return redirect()->route('pricing')->with('error', 'Le paiement a échoué ou a été annulé.');
    }
}