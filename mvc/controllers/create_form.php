<?php


class create_form extends Controller
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
        if (isset($_SESSION["permission"])) {
            if ($_SESSION["permission"] === "admin") {
                $user_info = $this->User->GetInfoAdminUserByID($_SESSION['user_id']);
                $amount_account = $this->User->GetAmountAccount();
                $next_IdAccount = $amount_account + 1;
                $this->ViewWithPer("create-player-form", "admin", [
                    "next_IdAccount" => $next_IdAccount,
                    "user_info" => $user_info
                ]);
                exit();
            } else {
                http_response_code(500);
                header('Location: /page-error-500.html');
                exit();
            }
        } else {
            http_response_code(403);
            header('Location: /page-error-403.html');
            exit();
        }
    }


}