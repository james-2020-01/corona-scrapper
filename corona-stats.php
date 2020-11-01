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
            $country = Array();
            $count = 0;
            $subNode->filterXpath("//td")->each(function ($cNode) {
                global $count, $country;
                $attr = $cNode->extract(['_name', '_text', '_class']);
                
                if( $count < 7 && ($count > 0) ){
                    //Switch on the type of variable to add the key expected Later
                    switch ($count) {
                        case '1':
                            $country["Country"]=$cNode->text();
                            break;
                        case '2':
                            $country["total cases"]=$cNode->text();
                            break;
                        case '3':
                            $country["new cases"]=$cNode->text();
                            break;
                        case '4':
                            $country["total deaths"]=$cNode->text();
                            break;
                        case '5':
                            $country["new deaths"]=$cNode->text();
                            break;
                        case '6':
                            $country["total recovered"]=$cNode->text();
                            break;
                        default:
                            #$country["Country"]=$cNode->text();
                            break;
                    }
                    #array_push($country, $cNode->text());
                }
                $count++;
            });
            //Add the newly created country to the array
            array_push($countries, $country);
        }
    });
    
});

//Trim the array to remove the region data
$final_countries = array_slice($countries, 8, count($countries)-17);
var_dump($final_countries);
?>