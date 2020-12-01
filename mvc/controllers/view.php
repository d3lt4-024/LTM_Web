<?php


class view extends Controller
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

    function player($IdAccount)
    {
        if ($this->User->CheckValidPlayerAccount($IdAccount) === true) {
            if (isset($_SESSION["permission"])) {
                if ($_SESSION["permission"] === "admin") {
                    $user_info = $this->User->GetInfoAdminUserByID($_SESSION['user_id']);
                    $result1 = $this->User->GetInfoPlayerUserByID($IdAccount);
                    $this->ViewWithPer("edit-user-player", "admin", [
                        "default" => $result1,
                        "user_info" => $user_info
                    ]);
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
        } else {
            http_response_code(404);
            header('Location: /page-error-404.html');
            exit();
        }
    }

}