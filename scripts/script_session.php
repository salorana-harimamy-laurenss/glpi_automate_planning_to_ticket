<?php

class InitSession{
    private $url;
    private $userToken;
    private $appToken;
    private $sessionToken;
    private $api = null;

    public function __construct($url, $userToken, $appToken){
        $this->url = $url;
        $this->userToken = $userToken;
        $this->appToken = $appToken;
        $this->init();
    }

    private function init(){
        $this->api = curl_init();
        $api_url = $this->url . "/initSession?get_full_session=true";

        curl_setopt($this->api, CURLOPT_URL, $api_url);
        curl_setopt($this->api, CURLOPT_HTTPGET , true); 
        curl_setopt($this->api, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->api, CURLOPT_HTTPHEADER, array(
            "Authorization: user_token $this->userToken ",  
            "App-Token: $this->appToken ",               
            "Content-Type: application/json" 
        ));    
        
        $response = curl_exec($this->api);

        if (curl_errno($this->api)) {

            echo 'Erreur cURL : ' . curl_error($this->api);

        } else {
            // Afficher le résultat
            $http_code = curl_getinfo($this->api, CURLINFO_HTTP_CODE);
            
            if ($http_code == 200) {
    
                $responseData = json_decode($response, true);
    
                if (isset($responseData['session_token'])) { 
    
                    $this->sessionToken = $responseData["session_token"];
    
                } else {

                    throw new Exception("Erreur: Token de session non trouvé dans la réponse.");

                }
    
            } else {
                // Affichage du code d'erreur HTTP
                echo "Erreur HTTP Code : " . $http_code . "\n";
                echo "Détails de la réponse : " . $response;
            }
        }
    }

    public function getSessionToken(){
        return $this->sessionToken;
    }

    public function closeSession(){
        return curl_close($this->api);
    }
}   

?>