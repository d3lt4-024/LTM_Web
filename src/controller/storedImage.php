<?php
session_start();
if (isset($_POST['action'])) {
    if ($_POST['action'] === "getStoredImages") {
        $res = getStoredImages();
        echo json_encode($res);
        exit();
    }
}

function getStoredImages()
{
    $imgLinks = [];
    $imgLinks = json_decode($_SESSION['imgLinks']);
    $res = [];
    $res['status'] = 'success';
    $res['msg'] = $imgLinks;
    return $res;
}
