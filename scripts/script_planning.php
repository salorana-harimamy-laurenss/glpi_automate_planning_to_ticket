<?php

class PlanningEvent{
    private $url;
    private $userToken;
    private $appToken;
    private $sessionToken;

    public function __construct($url, $userToken, $sessionToken, $appToken){
        $this->url = $url;
        $this->userToken = $userToken;
        $this->appToken = $appToken;
        $this->sessionToken = $sessionToken;
    }
    
    public function fetchPlanningEvent(){
        $api = curl_init();
        $api_url = $this->url . "/PlanningExternalEvent/?range=0-500&sort=begin&order=ASC";

        curl_setopt($api, CURLOPT_URL, $api_url);
        curl_setopt($api, CURLOPT_HTTPGET , true); 
        curl_setopt($api, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($api, CURLOPT_HTTPHEADER, array(
            "Authorization: user_token $this->userToken ",  
            "App-Token: $this->appToken ",
            "Session-Token: $this->sessionToken ",               
            "Content-Type: application/json" 
        ));    

        $response = curl_exec($api);

        if (curl_errno($api)) {

            echo 'Erreur cURL : ' . curl_error($api);

        } else {
            // Afficher le résultat
            $http_code = curl_getinfo($api, CURLINFO_HTTP_CODE);
            
            if ($http_code == 200) {
    
                $responseData = json_decode($response, true);
    
                if (isset($responseData)) {
    
                    return $responseData;
    
                } else {

                    throw new Exception("Erreur de récupération de donnée de planning");

                }
    
            } else {
                // Affichage du code d'erreur HTTP
                echo "Erreur HTTP Code : " . $http_code . "\n";

                echo "Détails de la réponse : " . $response;
            }
        }
    }

}

?>