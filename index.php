<?php
include 'head.php';

if(trim($_COOKIE['id'])){
    $auto_id = $_COOKIE['id'];
    $ischcecked = 'checked';
}
//test
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css?after" type="text/css">
    <title>notice</title>
</head>
<body>
<?php
    if($islogin){
        echo "<script>location.href='notice/notice.php'</script>";
    }else{?>
    <!-- <div class="text"><h1>로그인</h1></div> -->
    <form method="POST" class="form">
        <div><input type="text" placeholder="id" class="login" name="login_id" autocomplete='off' value="<?php echo $auto_id?>"/></div>
        <div><input type="password" placeholder="password" class="login" name="login_pw" autocomplete='off'/></div>
        <div class="loginbtn"><input type="submit" class="loginBtn" name="loginBtn" value="login"/></div>
        <div class="auto_login"><label><input type="checkbox" name="auto_login" value="auto_login" <?php echo $ischcecked?>> 아이디 저장</label></div>
        <div class="bottom"><button type="button" class="joinBtn" name="home_joinBtn" onclick="location.href='./user_signup/join.php'">회원가입</button>
        <button type="button" class="searchBtn" name="searchBtn" onclick="window.open('./user_signup/search.php','new','scrollbars=yes,resizable=no width=500 height=600, left=-1220,top=200');return false">ID/PW 찾기</button></div>
        <button type="button" onclick="tempCookie();">비회원으로 로그인</button>
    </form>
    <?php
        if(trim($_COOKIE['id'])){
            echo '<script> document.querySelector(".login").classList.add("auto");</script>';
        }
        if(array_key_exists('loginBtn',$_POST)){
            $user_id=false;
            $sql_id = mysqli_query($conn, "SELECT userid FROM member WHERE userid='{$_POST['login_id']}'");
            $sql_pw = mysqli_query($conn, "SELECT password FROM member WHERE userid='{$_POST['login_id']}'");
            if(mysqli_num_rows($sql_id)>0){
                $_SESSION['userid'] = $_POST['login_id'];
                while($pw_res = mysqli_fetch_assoc($sql_pw)){
                    if($pw_res['password']==md5($_POST['login_id'].$_POST['login_pw'])){
                        $check = isset($_POST['auto_login']) ? "checked" : "unchecked";
                        $_SESSION['userpwd'] = md5($_POST['login_id'].$_POST['login_pw']);
                        if($check == "checked"){
                            if(!trim($_COOKIE['id'])){
                                setcookie('id', $_POST['login_id'], time()+86400*30);
                            }else{
                                if(trim($_COOKIE['id']) != trim($_POST['login_id'])){
                                    setcookie('id', '', time()-86400*30);
                                    setcookie('id', $_POST['login_id'], time()+86400*30);
                                }
                            }
                        }
                        echo "<script>location.href='notice/notice.php'</script>";
                        
                    }else{
                        echo '<script>alert("비밀번호가 틀렸습니다.");</script>';
                    }
                }
            }else{
                echo '<script>alert("아이디가 틀렸습니다.");</script>';
            }
        }
    }
    ?>
    <script>
        function tempCookie(){
            <?php
                $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
                $id_len = rand(5,10);
                $var_size = strlen($chars);
                $random_str="";
                for( $i = 0; $i < $id_len ; $i++ ) {  
                    $random_str= $random_str.$chars[ rand( 0, $var_size - 1 ) ];
            ?>
            // console.log(<?php echo $random_str;?>);
            <?php
                }
                setcookie('non-member', $random_str, time()+86400*30);
            ?>
            location.href='notice/notice.php';
        }
    </script>
</body>
</html>