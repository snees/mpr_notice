<?php
    include '../head.php';

    $getIdx = trim($_REQUEST['notice_idx']);
    $notice_idx = 'notice_'.$getIdx;
    $getId = trim($_REQUEST['userid']);

    $cntSQL = "SELECT count(*) as cnt FROM likeTbl WHERE notice_idx='{$notice_idx}' AND liked_id='{$getId}'";
    $res = mysqli_query($conn, $cntSQL);
    $count = mysqli_fetch_assoc($res);

    if($count['cnt']==0){
        $strSQL = "INSERT INTO likeTbl (notice_idx, liked_id, is_like, date) VALUES ('{$notice_idx}', '{$getId}', 1, now())";
        mysqli_query($conn, $strSQL);
    }

    $arrayData = array('idx'=>trim($notice_idx), 'userid' => trim($getId));
    echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);

?>