<?php

require(__DIR__ . '/../models/BehaviourModel.php');

class BehaviourController{
    public function __construct(){

    }

    /**
     * Methods
     */
    private function getColumns($role) {
        $questionary    = BehaviourModel::getQuestionary(["rol" => $role]);
        $columns        = (object)[
            "count" => 0,
            "list"  => []
        ];

        if ($role == 'student') {
            $columns->list  = array_merge($columns->list, $questionary->base->list);
            $columns->list  = array_merge($columns->list, $questionary->decisions->list);
            $columns->count = $questionary->base->count + $questionary->decisions->count;
        } else {
            $columns->list  = $questionary->base->list;
            $columns->count = $questionary->base->count;
        }

        $columns->list  = array_map(function($column){return $column['question']['key'];}, $columns->list);

        return $columns;
    }

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

    public function getQuestionaries($request){
        $questionaries = BehaviourModel::getQuestionaries($request);

        if ( empty($questionaries) ) {
            return new WP_Error( 'no_questionaries', __('No questionaries found'), array( 'status' => 404 ) );
        } else {
            return new WP_REST_Response($questionaries, 200);
        }
    }

    public function downloadQuestionaries($request){
        $questionaries  = BehaviourModel::getQuestionaries($request, 'all');
        $tutorColumns   = $this::getColumns('tutor');
        $studentColumns = $this::getColumns('student');

        if ( empty($questionaries) ) {
            return new WP_Error( 'no_questionaries', __('No questionaries found'), array( 'status' => 404 ) );
        } else {
            header('Content-Encoding: UTF-8');
            header("Content-Type: application/xls; charset=UTF-8");
            header("Content-Disposition: attachment; filename=resportes-cuestionarios-seguimiento-mabclick-".date('Y-m-d').".xls"); 
            echo "\xEF\xBB\xBF";

            //Header
            include_once __DIR__."/../exports/reports/questionaries.php";
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

    public function getPolls($request){
        $polls = BehaviourModel::getPolls($request);

        if ( empty($polls) ) {
            return new WP_Error( 'no_polls', __('No polls found'), array( 'status' => 404 ) );
        } else {
            return new WP_REST_Response($polls, 200);
        }
    }
    

    public function downloadPolls($request){
        $polls = BehaviourModel::getPolls($request, 'all');

        if ( empty($polls) ) {
            return new WP_Error( 'no_polls', __('No polls found'), array( 'status' => 404 ) );
        } else {
            header('Content-Encoding: UTF-8');
            header("Content-Type: application/xls; charset=UTF-8");
            header("Content-Disposition: attachment; filename=resportes-encuestas-satisfaccion-mabclick-".date('Y-m-d').".xls"); 
            echo "\xEF\xBB\xBF";

            //Header
            include_once __DIR__."/../exports/reports/polls.php";
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
