<?php

// require(__DIR__ . '/../models/SessionModel.php');

class SessionController{

    public function __construct(){

    }

    /**
     * Methods
     */
    public function saveResource($request) {
        $target_dir = __DIR__ . "/../uploads/";

        $type = explode('.', $_FILES['fileToUpload']['name']);
        $type = $type[count($type)-1];
        $url = $target_dir . $_FILES['fileToUpload']['name'];
        $urlpdf = strtok($url, ".."); 

        if(is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {			
            if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $url)) {
                return "Ok";
            }else{
                return "No";
            }
        }else{
            return "No";
        }
    }
}
