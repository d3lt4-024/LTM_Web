<?php


class delete extends Controller
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
        if (isset($_SESSION["permission"])) {
            if ($_SESSION["permission"] === "admin") {
                $delete_result = $this->User->DeletePlater($IdAccount);
                if ($delete_result === true) {
                    echo '<script type="text/javascript">';
                    echo 'alert("Delete successful");';  //success messenge
                    echo 'window.location.href = "/listed/player";'; //redirect to list
                    echo '</script>';
                    exit();
                } else {
                    echo '<script type="text/javascript">';
                    echo 'alert("Something wrong, try again!");';  //success messenge
                    echo 'window.location.href = "/listed/player";'; //redirect to list
                    echo '</script>';
                    exit();
                }
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