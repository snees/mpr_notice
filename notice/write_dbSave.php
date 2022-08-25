<?php
    include '../head.php';

    $getTitle = trim($_REQUEST['title']);
    $getAuthor = trim($_REQUEST['author']);
    $getContent = trim($_REQUEST['content']);
    $getSecret = trim($_REQUEST['secret_post']);
    $getMember = trim($_REQUEST['member']);
    $getNonMember = trim($_REQUEST['nonMember']);

    if(trim($getNonMember)){
        $strSql = "INSERT INTO notice (title, author, content, createdate, secret_post, member, nonMember_pwd) 
                    VALUES ('{$getTitle}','{$getAuthor}','{$getContent}',now() ,{$getSecret} ,$getMember, '{$getNonMember}')";
    }else{
        $strSql = "INSERT INTO notice (title, author, content, createdate, secret_post, member) 
                    VALUES ('{$getTitle}','{$getAuthor}','{$getContent}',now() ,{$getSecret} ,$getMember)";
    }
    mysqli_query($conn, $strSql);

    $arrayData = array('title'=>trim($getTitle), 'author' => trim($getAuthor), 'content' => trim($getContent), 'secret' => trim($getSecret), 'member' => trim($getMember), 'nonMemberPwd' => trim($getNonMember),);
    echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
?>