<?php

class TicketUser{
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

    public function setUserTicket($data, $id){
        $user_ticket =[
            'input' => [
                "tickets_id" =>  $id,
                "users_id" =>  $data["user_id"], // Identifiant de l'utilisateur
                "type" => $data["type"], // Type de l'utilisateur
                "use_notification" =>  1,
                "alternative_email" =>  $data["alternative_email"], // Email alternative de l'utilsiateur
            ]
        ];

        $api = curl_init();
        $api_url = $this->url . "/Ticket/". $id ."/Ticket_User/";

        curl_setopt($api, CURLOPT_URL, $api_url);
        curl_setopt($api, CURLOPT_POST , true); 
        curl_setopt($api, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($api, CURLOPT_HTTPHEADER, array(
            "Authorization: user_token $this->userToken ",  
            "App-Token: $this->appToken ",
            "Session-Token: $this->sessionToken ",               
            "Content-Type: application/json" 
        )); 
        curl_setopt($api, CURLOPT_POSTFIELDS, json_encode($user_ticket));   

        $response = curl_exec($api);

        if(curl_errno($api)){
            
            throw new Exception("Erreur de requête : " . curl_error($api));
       
        }else {
            // Afficher le résultat
            $http_code = curl_getinfo($api, CURLINFO_HTTP_CODE);
            
            if ($http_code == 201) {
    
                $responseData = json_decode($response, true);
    
                if (isset($responseData['id'])) {

                    $user_ticketID = $responseData["id"];
                   
                    //echo " L'ID de l'UserTicket créée est : " . $user_ticketID . "\n";

                    return $user_ticketID;

                } else {

                    throw new Exception("Erreur d'insertion de donnée de UserTicket . ");
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