<?php
namespace Covoiturage\UserBundle\Services;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Covoiturage\UserBundle\Entity\Adresse;

class GoogleGeocoding {
    
    private $API_KEY ;
    
    function __construct($google_api_key) {
        $this->API_KEY = $google_api_key;
    }

    public function geoCodeAddress($address){

        if(preg_match("/[a-zA-Z]{2,}/", $address))
            $url =  "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&key=".$this->API_KEY;
        elseif(preg_match("/[-+]?([0-9]*\.[0-9]+|[0-9]+),[-+ ]?([0-9]*\.[0-9]+|[0-9]+)/", $address))
            $url =  "https://maps.googleapis.com/maps/api/geocode/json?latlng=".urlencode($address)."&key=".$this->API_KEY;
        else
            return null;
            
        $answer = file_get_contents($url);
        $results = json_decode($answer,true);
        
//        echo "<pre>";var_dump($results);echo "</pre>";
                
        if($results["status"]!=="OK" ){
            return null;
        }else{
           $result = $results["results"][0];
           $a = new Adresse();
           
           foreach ($result["address_components"] as $value) {
               $type = $value["types"][0];
               switch ($type) {
                    case "street_number":
                        $a->setNumero($value["long_name"]);
                        break;
                    case "route":
                        $a->setRue($value["long_name"]);
                        break;
                    case "locality":
                        $a->setVille($value["long_name"]);
                        break;
                    case "postal_code":
                        $a->setPostal($value["long_name"]);
                        break;
                    case "country":
                        $a->setPays($value["long_name"]);
                        break;
                    case "administrative_area_level_1":
                        $a->setRegion($value["long_name"]);
                        break;
                    case "administrative_area_level_2":
                        $a->setDepartement($value["long_name"]);
                        break;
                }
           }
           
           $a->setAdresseComplete($result["formatted_address"]);
           $a->setLatitude($result["geometry"]["location"]["lat"]);
           $a->setLongitude($result["geometry"]["location"]["lng"]);
           
           return $a;
        }
        
        
    }
 
}

