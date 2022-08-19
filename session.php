<?php
    session_start();
    // echo '<br>=>'.$_SESSION['userid'];
    // echo '<br>=>'.$_SESSION['userpwd']; 
    //exit;
    if(trim($_SESSION['userid']) && trim($_SESSION['userpwd'])){ // isset은 null값이면 false 아니면 true
        $islogin=true;
        // echo '<br>1';exit;
    } else {
    //    echo '<br>2';exit;
    }

?>