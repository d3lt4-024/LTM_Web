<?php


class edit extends Controller
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
                    if (isset($_POST["submit"])) {
                        try {
                            if (empty($_POST['Username'])) {
                                throw new Exception("Username không được để trống");
                            }
                            if (empty($_POST['Password'])) {
                                throw new Exception("Password không được để trống");
                            }
                            //delete space and special char in form data
                            $IdAccount = trim($IdAccount);
                            $Post_Username = trim($_POST['Username']);
                            $Post_Password = trim($_POST['Password']);
                            $Post_Point = trim($_POST['Point']);
                            if ($this->User->CheckValidPlayerUsername($Post_Username) === true) {
                                throw new Exception("Username đã trùng");
                            } else {
                                $Final_Username = "";
                                $Final_Password = "";
                                $Final_Point = "";
                                $user_info = $this->User->GetInfoPlayerUserByID($IdAccount);
                                if ($user_info["username"] === $Post_Username) {
                                    $Final_Username = $user_info["username"];
                                } else {
                                    $Final_Username = $Post_Username;
                                }
                                if ($user_info["password"] === $Post_Password) {
                                    $Final_Password = $user_info["password"];
                                } else {
                                    $Final_Password = $Post_Password;
                                }
                                if ($user_info["point"] === $Post_Point) {
                                    $Final_Point = $user_info["point"];
                                } else {
                                    $Final_Point = $Post_Point;
                                }
                                $edit_result = $this->User->UpdatePlayerAccount($IdAccount, $Final_Username, $Final_Password, $Final_Point);
                                if ($edit_result === false) {
                                    echo '<script type="text/javascript">';
                                    echo 'alert("Có lỗi xảy ra, vui lòng thử lại!");';  //error messenge
                                    echo 'window.location.href = "javascript:history.back()";';
                                    echo '</script>';
                                    exit();
                                } else {
                                    echo '<script type="text/javascript">';
                                    echo 'alert("Sửa thông tin thành công");';  //success messenge
                                    echo 'window.location.href = "/listed/player";'; //redirect to list teacher
                                    echo '</script>';
                                    exit();
                                }
                            }
                        } catch (Exception $e) {
                            $error_msg = $e->getMessage();
                        }
                        $this->ViewWithPer("edit-user-player", "admin", [
                            "error_msg" => $error_msg
                        ]);
                        exit();
                    } else {
                        http_response_code(500);
                        header('Location: /page-error-500.html');
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
        } else {
            http_response_code(404);
            header('Location: /page-error-404.html');
            exit();
        }
    }

    function account($IdAccount)
    {
        if ($this->User->CheckValidAdminAccountId($IdAccount) === true) {
            if ($IdAccount === $_SESSION['user_id']) {
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
                                $Post_Username = trim($_POST['Username']);
                                $Post_Password = trim($_POST['Password']);
                                if ($this->User->CheckValidPlayerUsername($Post_Username) === true) {
                                    throw new Exception("Username đã trùng");
                                } else {
                                    $Final_Username = "";
                                    $Final_Password = "";
                                    $user_info = $this->User->GetInfoAdminUserByID($IdAccount);
                                    if ($user_info["username"] === $Post_Username) {
                                        $Final_Username = $user_info["username"];
                                    } else {
                                        $Final_Username = $Post_Username;
                                    }
                                    if ($user_info["password"] === $Post_Password) {
                                        $Final_Password = $user_info["password"];
                                    } else {
                                        $Final_Password = $Post_Password;
                                    }
                                    $edit_result = $this->User->UpdateAdminAccount($Final_Username, $Final_Password);
                                    if ($edit_result === false) {
                                        echo '<script type="text/javascript">';
                                        echo 'alert("Có lỗi xảy ra vui lòng thử lại!");';  //error messenge
                                        echo 'window.location.href = "javascript:history.back()";';
                                        echo '</script>';
                                        exit();
                                    } else {
                                        echo '<script type="text/javascript">';
                                        echo 'alert("Cập nhật thông tin thành công");';  //success messenge
                                        echo 'window.location.href = "/";'; //redirect to list teacher
                                        echo '</script>';
                                        exit();
                                    }
                                }
                            } catch (Exception $e) {
                                $error_msg = $e->getMessage();
                            }
                            $this->ViewWithPer("account-setting", "admin", [
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
            http_response_code(404);
            header('Location: /page-error-404.html');
            exit();
        }
    }
}