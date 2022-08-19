<?php
    include '../head.php';

    $member_delete = "DELETE FROM member WHERE userid='{$_SESSION['userid']}'";
    mysqli_query($conn, $member_delete);
    $notice_delete = "DELETE FROM notice WHERE author='{$_SESSION['userid']}'";
    mysqli_query($conn, $notice_delete);
    $_SESSION['userid']='';
    $_SESSION['userpwd']='';
    echo "<script>location.href='../index.php'</script>";
?>