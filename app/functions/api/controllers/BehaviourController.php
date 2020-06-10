<?php

require(__DIR__ . '/../models/BehaviourModel.php');

class BehaviourController{

    public function __construct(){

    }

    /**
     * Methods
     */
    public function getQuestionary($request){
        $questionary = BehaviourModel::getQuestionary($request);

        if (isset($request['rol'])) {
            if ( empty($questionary) ) {
                return new WP_Error( 'no_questionary', __('No questionary found'), array( 'status' => 404 ) );
            } else {
                return new WP_REST_Response($questionary, 200);
            }
        } else {
            return new WP_Error( 'no_questionary_params', __('No questionary valid params'), array( 'status' => 404 ) );
        }
    }

    public function checkoutQuestionaryEnable($request){
        $questionary_enable = BehaviourModel::checkoutQuestionaryEnable($request);

        if ( empty($questionary_enable) ) {
            return new WP_Error( 'no_questionary_enable', __('No questionary enable'), array( 'status' => 404 ) );
        } else {
            return new WP_REST_Response($questionary_enable, 200);
        }      
    }

    public function saveQuestionary($request){
        if (isset($request['rol'])) {
            if (BehaviourModel::saveQuestionary($request)) {
                return new WP_REST_Response('Questionary saved', 200);
            } else {
                return new WP_Error( 'no_questionary_saved', __('No Questionary saved'), array( 'status' => 404 ) );
            }
        } else {
            return new WP_Error( 'no_questionary_params', __('No questionary valid params'), array( 'status' => 404 ) );
        }
    }

    public function getPoll($request){
        $poll = BehaviourModel::getPoll($request);

        if ( empty($poll) ) {
            return new WP_Error( 'no_poll', __('No poll found'), array( 'status' => 404 ) );
        } else {
            return new WP_REST_Response($poll, 200);
        }
    }

    public function checkoutPollEnable($request){
        $poll_enable = BehaviourModel::checkoutPollEnable($request);

        if ( empty($poll_enable) ) {
            return new WP_Error( 'no_poll_enable', __('No poll enable'), array( 'status' => 404 ) );
        } else {
            return new WP_REST_Response($poll_enable, 200);
        }      
    }

    public function savePoll($request){
        if (BehaviourModel::savePoll($request)) {
            return new WP_REST_Response('Poll saved', 200);
        } else {
            return new WP_Error( 'no_poll_saved', __('No poll saved'), array( 'status' => 404 ) );
        }
    }    
}
