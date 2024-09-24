<?php

    require 'vendor/autoload.php';

    require "./scripts/script_session.php";
    require "./scripts/script_planning.php";
    require "./scripts/script_event_scheduler.php";
    require "./scripts/script_guest_user.php";
    require "./scripts/script_ticket.php";
    require "./scripts/script_user_ticket.php";

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
   
    /*
    **  API DISTANT ? SERVEUR GLPI
    */
    $API_URL = $_ENV["API_URL"];
    $USER_TOKEN = $_ENV["USER_TOKEN"];
    $APP_TOKEN = $_ENV["DB_HOST"];

    $session = new InitSession($API_URL,$USER_TOKEN,$APP_TOKEN);
    $session_token = $session->getSessionToken();

    echo "Le Token de la session " . $session_token . "\n\n";

    $planning = new PlanningEvent($API_URL,$USER_TOKEN,$session_token, $APP_TOKEN);
    $planning_all = $planning->fetchPlanningEvent();  // Récupération des données des plannings

    date_default_timezone_set('Africa/Nairobi'); // Définir la timezone à Madagascar
    
    $dateToday = date('Y-m-d H:i:s');                //Date actuel / Aujourd'hui

    $total = 0;

    echo "Aujourd'hui, nous sommes le : $dateToday \n\t  ";

    foreach($planning_all as $plan){
    
        $plan_id = $plan["id"]; 
        $plan_name = $plan["name"];
        $plan_description = $plan["text"];
        $plan_catégorie = json_decode($plan["rrule"],true);       
        $plan_user = $plan["users_id"];
        $plan_user_guest = json_decode($plan["users_id_guests"], true);
        $plan_entite = $plan["entities_id"];
 
        $date = $dateToday;
        $get_plan = new EventScheduler($plan, $date);
        
        if($get_plan->isEventDueToday() == true){
     
            $total = $total + 1;
            /*
            *    echo "Le nom : $plan_name  et Le text : ". strip_tags(html_entity_decode($plan_description)) ."\n ";
            *    echo "\n Début de l'événement : " . $plan_debut ."\n\n";
            *    echo "Récurrence : ";
            *    print_r($plan_catégorie);
            *    echo "\n ";
            */

            // Création du ticket lié au planning
            $ticket = new TicketCreate($API_URL, $USER_TOKEN, $session_token, $APP_TOKEN);
            $ticket_new_id = $ticket->createTicket($plan);


            /*
            * Création du UserTicket : Type : Demandeur
            */
            $guests = new GuestUser($API_URL, $USER_TOKEN, $session_token, $APP_TOKEN);
            $mail_guests = $guests->getInformationUser($plan_user);
            $data = [
                'user_id' => $plan_user ,
                'type' => 1 ,
                'alternative_email' => $mail_guests ,
            ];
          
            $user_ticket = new TicketUser($API_URL, $USER_TOKEN, $session_token, $APP_TOKEN);
            $user_ticket_add = $user_ticket->setUserTicket( $data, $ticket_new_id);

            if(!!$user_ticket_add){
                echo "Action | Demandeur | Add-UserTicket avec ID : " . $user_ticket_add . " réussi ! \n";
            }

            /*
            * Création du UserTicket : Type : Observateur
            */

            if(is_array($plan_user_guest)){
                foreach ($plan_user_guest as $guest) {

                    //var_dump($guest);
                    
                    //Récupération de l'email de l'utilisateur invité
                    $guests = new GuestUser($API_URL, $USER_TOKEN, $session_token, $APP_TOKEN);
                    $mail_guests = $guests->getInformationUser($guest);
                    $data = [
                        'user_id' =>  $guest,
                        'type' =>  3 ,
                        'alternative_email' => $mail_guests,
                    ];
                    
                    $user_ticket = new TicketUser($API_URL, $USER_TOKEN, $session_token, $APP_TOKEN);
                    $user_ticket_add = $user_ticket->setUserTicket( $data, $ticket_new_id);
    
                    if(!!$user_ticket_add){
                        echo "Action | Observateur | Add-UserTicket avec ID : " . $user_ticket_add . " réussi ! \n";
                    }
                }
            }else{
                echo "Impossible de traiter les utilisateurs invités, la variable n'est pas un tableau.";
            }        
 
        }

    }

    $session->closeSession();

    echo "Total est : " . $total ;

?>