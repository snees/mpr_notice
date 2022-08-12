<?php
include 'session.php';

$conn = mysqli_connect("localhost","hmp","mpr1234!","hmp");
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
        echo "<script>location.href='./notice.php'</script>";
    }else{?>
    <!-- <div class="text"><h1>로그인</h1></div> -->
    <form method="POST" class="form">
        <div><input type="text" placeholder="id" class="login" name="login_id" autocomplete='off'/></div>
        <div><input type="password" placeholder="password" class="login" name="login_pw" autocomplete='off'/></div>
        <div class="loginbtn"><input type="submit" class="loginBtn" name="loginBtn" value="login"/></div>
        <div class="auto_login"><input type="checkbox" name="auto_login" value="auto_login"> 자동로그인</div>
        <div class="bottom"><button class="joinBtn" name="home_joinBtn"><a href="./join.php">회원가입</a></button>
        <button class="searchBtn" name="searchBtn"><a href="#none" onclick="window.open('./search.php','new','scrollbars=yes,resizable=no width=500 height=600, left=-1220,top=200');return false">ID/PW 찾기</a></button></div>
    </form>
    <?php
        if(array_key_exists('loginBtn',$_POST)){
            $user_id=false;
            $sql_id = mysqli_query($conn, "SELECT userid FROM member WHERE userid='{$_POST['login_id']}'");
            $sql_pw = mysqli_query($conn, "SELECT password FROM member WHERE userid='{$_POST['login_id']}'");
            if(mysqli_num_rows($sql_id)>0){
                $_SESSION['userid'] = $_POST['login_id'];
                while($pw_res = mysqli_fetch_assoc($sql_pw)){
                    if($pw_res['password']==$_POST['login_pw']){
                        $_SESSION['userpwd'] = $_POST['login_pw'];
                        $check = isset($_POST['auto_login']) ? "checked" : "unchecked";
                        if($check == "checked"){
                            setcookie('id', $_POST['login_id'], time()+86400*30);
                            setcookie("login_time",time(), time()+86400*30);
                            setcookie("token",md5($_['login_id'].['login_pw']), time()+86400*30);
                        }
                        echo "<script>location.href='./notice.php'</script>";
                        
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
</body>
</html>