<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\RecognizerJob;
use App\Http\Requests;
use ShopBack\Recognizer\Manager\RecognizerLinkManager;

class NotifyController extends Controller
{
 	 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function notify(Request $request)
    {
        $url = $request->input('url');
        $recognizerLinkManager = new RecognizerLinkManager();
        $product_id = $recognizerLinkManager->setUrl($url)->recognize();

        return response()->json(array('product_id' => $product_id));
        

    }
    //
}
