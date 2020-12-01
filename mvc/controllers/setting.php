<?php


class setting extends Controller
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

    function account($IdAccount)
    {
        if ($_SESSION['user_id'] === $IdAccount) {
            if (isset($_SESSION["permission"])) {
                if ($_SESSION["permission"] === "admin") {
                    $user_info = $this->User->GetInfoAdminUserByID($_SESSION['user_id']);
                    $this->ViewWithPer("account-setting", "admin", [
                        "user_info" => $user_info
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
        } else {
            http_response_code(403);
            header('Location: /page-error-403.html');
            exit();
        }
    }
}