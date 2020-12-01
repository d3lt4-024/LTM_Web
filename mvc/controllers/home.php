<?php

class home extends Controller
{
    private $User;
    private $Manager;
    private $Employee;

    public function __construct()
    {
        $this->User = $this->model("User");
    }

    function index()
    {
        if (isset($_SESSION['permission'])) {
            if ($_SESSION['permission'] === "admin") {
                $user_info = $this->User->GetInfoAdminUserByID($_SESSION['user_id']);
                $amount_account = $this->User->GetAmountAccount();
                $amount_account_online = $this->User->GetAmountAccountOnline();
                $amount_account_offline = $this->User->GetAmountAccountOffline();
                $amount_account_player = $this->User->GetAmountAccountPlayer();
                $amount_account_playing = $this->User->GetAmountAccountPlaying();
                $amount_account_notplaying = $this->User->GetAmountAccountNotPlaying();
                $this->ViewWithPer("home", "admin", [
                    "user_info" => $user_info,
                    "amount_acc" => $amount_account,
                    "amount_acc_onl" => $amount_account_online,
                    "amount_acc_off" => $amount_account_offline,
                    "amount_acc_player" => $amount_account_player,
                    "amount_acc_playing" => $amount_account_playing,
                    "amount_acc_not_playing" => $amount_account_notplaying,
                ]);
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
