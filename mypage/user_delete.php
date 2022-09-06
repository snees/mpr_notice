<?php
    include '../head.php';

    $like_idx = "SELECT idx FROM notice WHERE author = '{$_SESSION['userid']}'";
    $idx_res = mysqli_query($conn,$like_idx);
    while($res = mysqli_fetch_array($idx_res)){
        $idx =  'notice_'.$res['idx'];
        $like_delete = "DELETE FROM likeTbl WHERE notice_idx = '$idx'";
        mysqli_query($conn,$like_delete);
    }
    

    $delete_SQL = "DELETE a,b,c FROM member AS a JOIN notice AS b JOIN comment AS c ON b.author = '{$_SESSION['userid']}' AND a.userid = '{$_SESSION['userid']}' AND c.userid = '{$_SESSION['userid']}'";
    mysqli_query($conn, $delete_SQL);

    
    // $member_delete = "DELETE FROM member WHERE userid='{$_SESSION['userid']}'";
    // mysqli_query($conn, $member_delete);
    // $notice_delete = "DELETE FROM notice WHERE author='{$_SESSION['userid']}'";
    // mysqli_query($conn, $notice_delete);
    $_SESSION['userid']='';
    $_SESSION['userpwd']='';
    echo "<script>location.href='../index.php'</script>";
?>