<?php

namespace App\Http\Controllers;

use Iodev\Whois\Factory;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportData;
use phpWhois\Whois;
use ipinfo\ipinfo\IPinfo;

use Illuminate\Http\Request;

class LookupDataContoller extends Controller
{
    //
    public function extractData(Request $request)
    {

        // dd($request->all());
        if (isset($request->domain)) {
            $domainName = $request->domain;
            $whois = Factory::get()->createWhois();
            // $ip_address = '216.58.209.142';
            // dd($whois->lookupDomain("google.com"));
            try {

                $domainData = $whois->loadDomainInfo($domainName);
                $c_name = $domainData->getExtra()['groups'][0]['Registrant Country'];
                $owner = $domainData->getExtra()['groups'][0]['Registrar'];
                $org = $domainData->getExtra()['groups'][0]['Registrant Organization'];
                $spam = $domainData->getExtra()['groups'][0]['Registrar Abuse Contact Email'];
                $created_on = $domainData->getExtra()['groups'][0]['Creation Date'];
                $isp = $domainData->getExtra()['groups'][0]['Name Server'][0];
                $ip = gethostbyname($domainName);
                // dd(gethostbyaddr('199.7.202.92'));
                // get country full name
                $names = json_decode(file_get_contents("http://country.io/names.json"), true);
                $country = $names[$c_name];
                // $domainDetailArray = [];

                $domainDetailArray = ['domain' => $domainName, 'country' => $country, 'owner' => $owner, 'org' => $org, 'spam' => $spam, 'created_on' => $created_on, 'isp' => $isp,  'ip' => $ip];
            } catch (\Exception $e) {
                $domainDetailArray = [' '];
            }


            // $domainIP = gethostbyname("oplayblueprints.com");
            // dd($domainData->getExtra());
            // $extra = $domainData->getExtra();
            // dd($domainData->getExtra()['groups'][0]['Name Server'][0]);



        }

        if (isset($request->ip)) {
            $IP = $request->ip;
            $whois = new Whois();
            $result = $whois->lookup($request->ip);
            // dd($result);
            if (!isset($result['regrinfo']['owner'])) {
                $IPDetailArray = [' '];
            } else {
                $i_country = $result['regrinfo']['owner']['address']['country'];
                $names = json_decode(file_get_contents("http://country.io/names.json"), true);
                $ip_country = $names[$i_country];
                $ip_owner = $result['regrinfo']['owner']['organization'];
                $ip_org = $result['regrinfo']['owner']['organization'];
                $ip_spam = $result['regrinfo']['abuse']['email'];
                $ip_isp = $result['regrinfo']['network']['name'];
                $ip_created = $result['regrinfo']['network']['created'];
                $ip_domain = gethostbyaddr($request->ip);
                $IPDetailArray = [];
                $IPDetailArray = [
                    'ip' => $IP, 'country' => $ip_country, 'owner' => $ip_owner, 'org' => $ip_org, 'spam' => $ip_spam,
                    'created_on' => $ip_created, 'isp' => $ip_isp,  'domain' => $ip_domain
                ];
            }
        }
        return Excel::download(new ExportData($domainDetailArray, $IPDetailArray), 'details.xls');
    }
}
