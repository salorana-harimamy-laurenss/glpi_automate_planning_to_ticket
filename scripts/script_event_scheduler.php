<?php 

class EventScheduler{
    protected $date_today;
    private $data_planning;
    public function __construct($data, $date){
        //Récupération de la date d'aujourd'hui (date du jour)
        $this->date_today = new DateTime($date);
        //Récupération des données du planning
        $this->data_planning = $data;
    }

    /**
     * Récupérer la fréquence et l'intervalle à partir de la rrule de données de planning.
    */
    public function getReccurenceFrequencyAndInterval($data)
    {
        if (isset($data)) {
            $rrule = json_decode($data, true);

            $frequency = $rrule["freq"] ?? null;  
            $interval = $rrule["interval"] ?? null; 

            return [
                "frequency" => $frequency,
                "interval" => $interval
            ];
        }

        return null;
    }

    /**
     * Vérification de l'évenement d'aujourd'hui
    */

  
    public function isEventDueToday(){
        $startDate = new DateTime($this->data_planning["begin"]); 
        $rruleData = $this->getReccurenceFrequencyAndInterval($this->data_planning["rrule"]);

        $frequency = $rruleData["frequency"];
        $interval = (int)$rruleData["interval"];

        if (!$rruleData) {
            return false;
        }else{
            $frequency = $rruleData['frequency'];
            $interval = (int)$rruleData['interval'];
    
            switch ($frequency) {
                case "daily":
                    //echo "JOURNALIER";
                    return $this->isDaily($startDate, $interval);
                case "weekly":
                    //echo "HEBDOMADAIRE";
                    return $this->isWeekly($startDate, $interval);
                case "monthly":
                    //echo "MOIS";
                    return $this->isMonthly($startDate, $interval);
                case "yearly":
                    //echo "ANNEE";
                    return $this->isYearly($startDate, $interval);
                default:
                    return false;
            }
        }
    }

    protected function isDaily($startDate, $interval){
        // Vérifie si l'événement est prévu tous les X jours
        $daysDiff = $startDate->diff($this->date_today)->days;
        return $daysDiff % $interval === 0;
    }

    protected function isWeekly($startDate, $interval){
        // Vérifie si l'événement est prévu toutes les X semaines
        if ($startDate->format('N') !== $this->date_today->format('N')) {
            return false;
        }
    
        // Calcul de la différence en semaines entre la date de début et aujourd'hui
        $weeksDiff = floor($startDate->diff($this->date_today)->days / 7);
    
        // Vérifie si la différence en semaines est un multiple exact de l'intervalle
        return $weeksDiff % $interval === 0;
       
    }

    protected function isMonthly($startDate, $interval){
        // Vérifie si la date du mois correspond à celle de l'événement
        if ($startDate->format('d') !== $this->date_today->format('d')) {
            return false;
        }
        // Vérifie si l'événement est prévu tous les X mois
        $monthsDiff = ($this->date_today->format('Y') - $startDate->format('Y')) * 12 + ($this->date_today->format('m') - $startDate->format('m'));
        return $monthsDiff % $interval === 0 && $startDate->format('d') === $this->date_today->format('d');
    }

    protected function isYearly($startDate, $interval){
         // Vérifie si le mois et le jour correspondent
        if ($startDate->format('m') !== $this->date_today->format('m') || $startDate->format('d') !== $this->date_today->format('d')) {
            return false;
        }
        // Vérifie si l'événement est prévu tous les X ans
        $yearsDiff = $this->date_today->format('Y') - $startDate->format('Y');
        return $yearsDiff % $interval === 0 && $startDate->format('m-d') === $this->date_today->format('m-d');
    }
/**/
}

?>