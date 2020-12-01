<?php
session_start();

if (isset($_FILES["clientImage"]["name"])) {
    $clientImage = $_FILES["clientImage"];
    $res = uploadImage($clientImage);
    if ($res['status'] === "success") {
        $imageLink = $res['link'];
        addImageLinkToSession($imageLink);
    }
    echo json_encode($res);
    exit();
}

function addImageLinkToSession($imageLink)
{
    if (!isset($_SESSION['imgLinks']) || empty($_SESSION['imgLinks'])) return;
    $imgLinks = [];
    $imgLinks = json_decode($_SESSION['imgLinks']);
    $imgLinks[] = $imageLink;
    $_SESSION['imgLinks'] = json_encode($imgLinks);
}

// Check if image file is a actual image or fake image
function checkImage($imageFile)
{
    $res = [];
    $res['status'] = "error";

    // Check file size
    if ($imageFile["size"] > 500000) {
        $res['msg'] = "Sorry, your file is too large.";
        return $res;
    }

    // Sanitize the filename
    $filename = preg_replace('[/\\?%*:|"<>]', '', basename(html_entity_decode($imageFile['name'], ENT_QUOTES, 'UTF-8')));

    // Validate the filename length
    if ((strlen($filename) < 4) || (strlen($filename) > 255)) {
        $res['msg'] = "Invalid file name!";
        return $res;
    }

    // Allowed file extension types
    $allowed = array(
        'jpg',
        'jpeg',
        'gif',
        'png'
    );
    if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
        $res['msg'] = "Not an image file! Only JPG, JPEG, PNG & GIF files are allowed.";
        return $res;
    }

    // Allowed file mime types
    $allowed = array(
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/x-png',
        'image/gif'
    );
    if (!in_array($imageFile['type'], $allowed)) {
        $res['msg'] = "Not an image file! Only JPG, JPEG, PNG & GIF files are allowed.";
        return $res;
    }

    $res['msg'] = $filename;
    $res['status'] = "success";
    return $res;
}

function uploadImage($imageFile)
{
    $check = checkImage($imageFile);
    if (!$check['status'])
        return $check;
    $filename = $check['msg'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $filename = bin2hex(random_bytes(12)) . '.' . $ext;

    $uploads_dir = $_SERVER['DOCUMENT_ROOT'] . "/upload/";
    $clientUpload_dir = session_id() . "/";
    if (!is_dir($uploads_dir . $clientUpload_dir)) {
        mkdir($uploads_dir . $clientUpload_dir);
    }
    $target_file = $uploads_dir . $clientUpload_dir . $filename;

    $res = [];
    // error_log($target_file);
    if (move_uploaded_file($imageFile["tmp_name"], $target_file)) {
        $res['status'] = "success";
        $res['msg'] = "Your image has been uploaded.";
        $res['link'] = "http://" . $_SERVER['HTTP_HOST'] . "/upload/" . $clientUpload_dir . $filename;
    } else {
        $res['status'] = "error";
        $res['msg'] = "Sorry, there was an error uploading your file.";
    }
    return $res;
}
