<?php


class create extends Controller
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
        if ($this->User->CheckValidPlayerAccount($IdAccount) === false) {
            if (isset($_SESSION["permission"])) {
                if ($_SESSION["permission"] === "admin") {
                    if (isset($_POST["submit"])) {
                        try {
                            //check empty field
                            if (empty($_POST['Username'])) {
                                throw new Exception("Username không được để trống");
                            }
                            if (empty($_POST['Password'])) {
                                throw new Exception("Password không được để trống");
                            }
                            //delete space and special char in form data
                            $Final_IdAccount = $this->User->GetAmountAccount() + 1;
                            $Username = trim($_POST['Username']);
                            $Password = trim($_POST['Password']);
                            $Point = trim($_POST['Point']);
                            if ($this->User->CheckValidPlayerUsername($Username) === true) {
                                throw new Exception("Username đã trùng");
                            } else {
                                $create_result = $this->User->CreatePlayerAccount($Final_IdAccount, $Username, $Password, $Point);
                                if ($create_result === false) {
                                    echo '<script type="text/javascript">';
                                    echo 'alert("Có lỗi xảy ra, vui lòng thử lại!");';  //error messenge
                                    echo 'window.location.href = "javascript:history.back()";';
                                    echo '</script>';
                                    exit();
                                } else {
                                    echo '<script type="text/javascript">';
                                    echo 'alert("Tạo tài khoản thành công");';  //success messenge
                                    echo 'window.location.href = "/listed/player";'; //redirect to list teacher
                                    echo '</script>';
                                    exit();
                                }
                            }
                        } catch (Exception $e) {
                            $error_msg = $e->getMessage();
                        }
                        $this->ViewWithPer("create-player-form", "admin", [
                            "error_msg" => $error_msg
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
            } else {
                http_response_code(403);
                header('Location: /page-error-403.html');
                exit();
            }
        } else {
            echo '<script type="text/javascript">';
            echo 'alert("ID Account đã tồn tại");';  //success messenge
            echo 'window.location.href = "javascript:history.back()";'; //redirect to list manager
            echo '</script>';
            exit();
        }
    }
}