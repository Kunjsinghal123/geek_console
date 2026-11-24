<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;

// class SetupWizardController extends Controller
// {
//     public function index()
//     {
//         return view('pages.setup-wizard');
//     }
// }


namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class SetupWizardController extends Controller
{
    public function index(Request $request, $store_id)
    {
        $store = Store::where('store_id', $store_id)->first();

        if (!$store) {
            abort(404, 'Store not found.');
        }

        return view('pages.setup-wizard', [
            'store' => $store,
        ]);
    }
}
