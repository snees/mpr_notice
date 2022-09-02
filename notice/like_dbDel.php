<?php
    include '../head.php';

    $getIdx = trim($_REQUEST['notice_idx']);
    $notice_idx = 'notice_'.$getIdx;
    $getId = trim($_REQUEST['userid']);

    $strSQL = "DELETE FROM likeTbl WHERE notice_idx='{$notice_idx}' AND liked_id='{$getId}'";
    mysqli_query($conn, $strSQL);

    $arrayData = array('idx'=>trim($notice_idx), 'userid' => trim($getId));
    echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);


?>