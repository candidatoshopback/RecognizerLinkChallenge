<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Jobs\XmlImporterJob;

class XmlImporterController extends Controller
{
    public function import($client_id)
    {
    	dispatch(new XmlImporterJob($client_id));
    	return response()->json(array('status' => true));
    }
}
