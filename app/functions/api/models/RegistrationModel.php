<?php

class RegistrationModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    public static function getRegistration($request){
        $registrations = get_field('registrations', 'options');
        
        foreach($registrations as $registration){
            if(
                $registration['registration']['course']->ID == $request['course'] and 
                $registration['registration']['user']['user_nicename'] == $request['user'] and
                $registration['registration']['state'] == true ){

                return true;
            }else {
                return false;
            }
        }
    }
}
