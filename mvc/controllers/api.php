<?php

class api extends Controller
{
    private $User;

    public function __construct()
    {
        $this->User = $this->model("User");
    }

    public function index()
    {
        http_response_code(403);
        header('Location: /page-error-403.html');
        exit();
    }

    public function GetAccount()
    {
        if (isset($_SESSION["permission"])) {
            if ($_SESSION["permission"] === "admin") {
                $acc_array = [];
                $result = $this->User->GetAccStateForJs();
                foreach ($result as $key => $value) {
                    array_push($acc_array, intval($value["Amount"]));
                }
                echo json_encode($acc_array);
            } else {
                http_response_code(403);
                header('Location: /page-error-403.html');
                exit();
            }
        }
    }

    public function GetPlayer()
    {
        if (isset($_SESSION["permission"])) {
            if ($_SESSION["permission"] === "admin") {
                $acc_array = [];
                $result = $this->User->GetPlayerStateForJs();
                foreach ($result as $key => $value) {
                    array_push($acc_array, intval($value["Amount"]));
                }
                echo json_encode($acc_array);
            } else {
                http_response_code(403);
                header('Location: /page-error-403.html');
                exit();
            }
        }
    }

}