<?php

class listed extends Controller
{
    private $User;

    public function __construct()
    {
        $this->User = $this->model("User");
    }

    function index()
    {
        http_response_code(403);
        header('Location: /page-error-403.html');
        exit();
    }

    function player()
    {
        if (isset($_SESSION['permission'])) {
            if ($_SESSION['permission'] === "admin") {
                $user_info = $this->User->GetInfoAdminUserByID($_SESSION['user_id']);
                $result = $this->User->GetAllPlayer();
                $this->ViewWithPer("player-list", "admin", [
                    "player_list" => $result,
                    "user_info" => $user_info
                ]); //if user is teacher
                exit();
            } else {
                http_response_code(403);
                header('Location: /page-error-403.html');
                exit();
            }
        } else {
            http_response_code(403);
            header('Location: /page-error-403.html');
            exit();
        }
    }

}