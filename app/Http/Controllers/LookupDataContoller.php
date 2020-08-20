<?php

namespace App\Http\Controllers;
use Iodev\Whois\Factory;

use Illuminate\Http\Request;

class LookupDataContoller extends Controller
{
    //
    public function extractData(Request $request){

        // dd($request->all());
        $whois = Factory::get()->createWhois();
        $response = $whois->loadDomainInfo("fiverr.com");
        $ip = gethostbyname("google.com");

        // $response = $whois->loadDomainInfo($domain);
        dd($response);

    }
}
