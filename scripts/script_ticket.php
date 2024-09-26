<?php

class TicketCreate{
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

    public function createTicket($data){

        $ticket =[
            'input' => [
                "entities_id" => $data["entities_id"],  //Entité : USINE
                "name" => $data["name"],
                 //"date" => "2021-02-01 11:48:36", // La date d'ouverture du ticket
                "users_id_lastupdater" => $data["users_id"], // Utilisateur qui a fait la dernier mise à jour
                "status" => 1 , // Status : NOUVEAU
                "users_id_recipient" => $data["users_id"], // Utilisateur Ticket
                "requesttypes_id" => 4, // TYPE : 1- Helpdesk ; 4 - Direct
                "content" => $data["text"] ,
                "urgency" => 3,             // Urgence : Moyenne
                "impact" => 3,              // Urgence : Moyenne
                "priority" =>  4,           // Urgence : Haute
                "itilcategories_id" =>  1,  // Catégorie : Maintenance Préventive
                 //"type" => 2,
                "global_validation" => 1,  
                "locations_id" => 1,        // USINE GLOBALE
                 //"date_creation" => "2021-02-01 13:48:36"
            ]
        ];

        $api = curl_init();
        $api_url = $this->url . "/Ticket/";

        curl_setopt($api, CURLOPT_URL, $api_url);
        curl_setopt($api, CURLOPT_POST , true); 
        curl_setopt($api, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($api, CURLOPT_HTTPHEADER, array(
            "Authorization: user_token $this->userToken ",  
            "App-Token: $this->appToken ",
            "Session-Token: $this->sessionToken ",               
            "Content-Type: application/json" 
        )); 
        curl_setopt($api, CURLOPT_POSTFIELDS, json_encode($ticket));   

        $response = curl_exec($api);

        if(curl_errno($api)){
            
            throw new Exception("Erreur de requête : " . curl_error($api));
       
        }else {
            // Afficher le résultat
            $http_code = curl_getinfo($api, CURLINFO_HTTP_CODE);
            
            if ($http_code == 201) {
    
                $responseData = json_decode($response, true);
    
                if (isset($responseData['id'])) {

                    $ticketID = $responseData["id"];

                    //echo "\n\n************  TICKET  **************** \n";
                    //echo "L'ID du ticket créée est : " . $ticketID ."\n";

                    return $ticketID;

                } else {

                    throw new Exception("Erreur d'insertion dees données d'un Ticket");
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