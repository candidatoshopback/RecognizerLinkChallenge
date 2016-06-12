<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Client;
use App\Http\Requests;

class ClientController extends Controller
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

    public function create(Request $request)
    {
        $client = new Client;
        $client->domain = $request->input("domain");
        $client->xml = $request->input("xml");
        $client->save();
        return response()->json($client);
    }
    //
}
