<?php
//include trim($_SERVER['DOCUMENT_ROOT']).'/hmp/mpr_notice/notice/head.php';
include '../head.php';

//echo trim($_SERVER['DOCUMENT_ROOT']);
//C:\Users\MPR\Desktop\mpr_notice\head.php

$getIdx = trim($_REQUEST['idx']);
$getPwd = trim($_REQUEST['pwd']);


$getCode = intval($getIdx);


$notice_select = "SELECT * FROM notice WHERE idx='{$getCode}'";
$notice_res = mysqli_query($conn, $notice_select);
$notice = mysqli_fetch_array($notice_res);

$author=$_SESSION['userid'];

if($notice['member'] == 0){
    $member_select = "SELECT password FROM member WHERE userid='{$notice['author']}'";
    $member_res = mysqli_query($conn, $member_select);
    $member = mysqli_fetch_array($member_res);

    $pwd = md5($author.$getPwd);
    if(trim($pwd) == trim($member['password'])){
        $getState = "OK";
    }else{
        $getState = "NO";
    }
}else if($notice['member'] == 1){
    if(trim($getPwd) == trim($notice['nonMember_pwd'])){
        $getState = "OK";
    }else{
        $getState = "NO";
    }
}

$arrayData = array('state'=>trim($getState));
echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
?>