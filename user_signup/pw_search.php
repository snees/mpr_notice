<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="search.css?after">
    <title>Document</title>
</head>
<body>
    <form method="POST"> 
    <table class="pw_table">
            <tr><td><button type="button" class="id_search" name="id_search" onclick="location.href='./search.php'">아이디 찾기</button></td><td><input type="submit" class="pw_search page" name="pw_search" value="비밀번호 찾기"/></td></tr>
            <tr><td colspan="2"><input type="text" class="input_id" name="input_id" placeholder="아이디 입력" autocomplete='off'></td></tr>
            <tr><td colspan="2"><input type="text" class="input_pw" name="input_pw" placeholder="변경할 비밀번호 입력" autocomplete='off'></td></tr>
            <tr><td colspan="2"><input type="text" class="input_pw1" name="input_pw1" placeholder="변경할 비밀번호 재입력" autocomplete='off'></td></tr>
            <tr><td colspan="2"><button class="changeBtn" name="changeBtn">비밀번호 변경하기</button></td></tr>
        </table>
    </form>
    <?php
        if(array_key_exists('changeBtn',$_POST)){
            if(!empty($_POST['input_id']) || !empty($_POST['input_pw']) || !empty($_POST['input_pw1'])){
                $conn = mysqli_connect("localhost","hmp","mpr1234!","hmp");
                $sql = "SELECT userid FROM hmp.member WHERE userid='{$_POST['input_id']}'";
                $res = mysqli_query($conn, $sql);
                if(mysqli_num_rows($res)>0){
                    $upper_pwd=false;
                    $lower_pwd=false;
                    
                    for($i=0; $i<strlen($_POST['input_pw']); $i++){
                        $pwd_arr[$i] = substr($_POST['input_pw'],$i,1);
                        if(97<= ord($pwd_arr[$i]) && ord($pwd_arr[$i]) <=122){
                            $lower_pwd=true;
                        }
                    }
                    for($i=0; $i<strlen($_POST['input_pw']); $i++){
                        $pwd_arr[$i] = substr($_POST['input_pw'],$i,1);
                        if(65<= ord($pwd_arr[$i]) && ord($pwd_arr[$i]) <=90){
                            $upper_pwd=true;
                        }
                    }
                    if($_POST['input_pw'] == $_POST['input_pw1']){
                        $conn = mysqli_connect("localhost","hmp","mpr1234!","hmp");
                        mysqli_query($conn, "set session character_set_connection=utf8;");
                        mysqli_query($conn, "set session character_set_results=utf8;");
                        mysqli_query($conn, "set session character_set_client=utf8;");
                        $sql_update = "UPDATE member SET password='{$_POST['input_pw']}' WHERE userid='{$_POST['input_id']}'";
                        mysqli_query($conn, $sql_update);
                        echo '<script>self.close()</script>';
                    }else{
                        echo '<script>console.log("wrong password");</script>';
                    }
                }else{
                    echo '<script>console.log("해당아이디 없음");</script>';
                }
            }else{
                echo '<script>console.log("빈칸을 입력하세요");</script>';
            }
        }
    ?>
</body>
</html>