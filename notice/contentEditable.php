<?php
    include '../head.php';

    $getContent = trim($_REQUEST['content']);
    $getIdx = trim($_REQUEST['idx']);
    $getTitle = trim($_REQUEST['new_title']);
    $getImg = trim($_REQUEST['img']);

    $strSql = "UPDATE notice SET content='{$getContent}', title='{$getTitle}', file='{$getImg}'  WHERE idx = '{$getIdx}'";
    mysqli_query($conn, $strSql);

    $arrayData = array('content'=>trim($getContent),'idx'=>trim($getIdx));
    echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
?>