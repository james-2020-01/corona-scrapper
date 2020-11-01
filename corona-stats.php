<?php

require 'vendor/autoload.php';
use Goutte\Client;
$url = 'https://www.worldometers.info/coronavirus/';

$client = new Client();
$crawler = $client->request('GET', $url);
global $countCountries, $countries;
$countries = Array();
$countCountries = 0;
$crawler->filter('#main_table_countries_today')->each(function ($node) {
    $subcrawler = $node->filterXpath("//tbody/tr")->each(function ($subNode) {
        global $count, $country, $countries;
        $attributes = $subNode->extract(['_name', '_text', '_class']);

        //print $attributes;
        //Remove everything not country related
        if($attributes[0][2] == ""){
            print "x\n";
            $country = Array();
            $count = 0;
            $subNode->filterXpath("//td")->each(function ($cNode) {
                global $count, $country;
                $attr = $cNode->extract(['_name', '_text', '_class']);
                
                if( $count < 7 && ($count > 0) ){
                    
                    array_push($country, $cNode->text());
                }
                $count++;
            });
            array_push($countries, $country);
        }
    });
    
});
$final_countries = array_slice($countries, 8, count($countries)-17);
var_dump($final_countries);
?>