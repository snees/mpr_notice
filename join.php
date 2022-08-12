<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="join.css?after">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <table class="div_id">
            <tr>
                <td><div class="id"><input type="text" placeholder="아이디" class="userid" id="userid" name="userId" autocomplete='off'></div></td>
            </tr>
            <tr>
                <td><div class="email"><input type="text" placeholder="이메일" class="usermail" id="usermail" name="userMail" autocomplete='off'></div></td>
            </tr>
            <tr>
                <td><div class="pw"><input type="password" placeholder="비밀번호 (대소문자 포함8자 이상)" class="userpw" id="userpw" name="userPw"></div></td>
            </tr>
            <tr>
                <td><div class="pw1"><input type="password" placeholder="비밀번호 재입력" class="userpw1" id="userw1" name="userPw1"></div></td>
            </tr>
        </table>
        <table class="div_name">
            <tr>
                <td><div class="name"><input type="text" placeholder="이름" class="username" id="username" name="userName" autocomplete='off'></div></td>
            </tr>
            <tr>
                <td><div class="birth"><input type="text" placeholder="생년월일 (20220805)" class="userbirth" id="userbirth" name="userBirth" autocomplete='off'></div></td>
            </tr>
            <tr>
                <td><div class="tel"><input type="text" placeholder="전화번호 (010-1234-5678)" class="usertel" id="usertel" name="userTel" autocomplete='off'></div></td>
            </tr>
            </table>
            <tr>
                <td class="end"><button class="cancelBtn"><a href="./index.php">취소</a></button>&nbsp;<input type="submit" value="가입" name="join_signup" class="sign_up_Btn"/></td>
            </tr>
    </form>
    <?php
    // sign_up
    if(array_key_exists('join_signup',$_POST)){
        if(empty($_POST['userName']) || empty($_POST['userMail']) || empty($_POST['userId']) || empty($_POST['userPw']) || empty($_POST['userPw1']) || empty($_POST['userTel']) || empty($_POST['userBirth'])){
            echo "<script> alert('빈칸을 입력하세요.');</script>";
        }else{
            $conn = mysqli_connect("localhost","hmp","mpr1234!","hmp");
            $sql = "SELECT userid FROM member WHERE userid='{$_POST['userId']}'";
            $res = mysqli_query($conn, $sql);
            if(!(mysqli_num_rows($res)>0)){
                $mail = '/^[a-zA-Z0-9]{1}[a-zA-Z0-9\-_]+@[a-z0-9]{1}[a-z0-9\-]+[a-z0-9]{1}\.(([a-z]{1}[a-z.]+[a-z]{1}[a-z]+)|([a-z]+))$/';
                if(preg_match($mail, $_POST['userMail'])){  
                    $upper_pwd=false;
                    $lower_pwd=false;
                    
                    for($i=0; $i<strlen($_POST['userPw']); $i++){
                        $pwd_arr[$i] = substr($_POST['userPw'],$i,1);
                        if(97<= ord($pwd_arr[$i]) && ord($pwd_arr[$i]) <=122){
                            $lower_pwd=true;
                        }
                    }
                    for($i=0; $i<strlen($_POST['userPw']); $i++){
                        $pwd_arr[$i] = substr($_POST['userPw'],$i,1);
                        if(65<= ord($pwd_arr[$i]) && ord($pwd_arr[$i]) <=90){
                            $upper_pwd=true;
                        }
                    }
                    if(strlen($_POST['userPw'])>=8 && $upper_pwd==true && $lower_pwd==true){
                        if($_POST['userPw'] == $_POST['userPw1']){
                            //생년월일
                            if(strlen($_POST['userBirth'])==8){
                                //전화번호
                                $Phone = '/^(010|011|016|017|018|019)-[0-9]{3,4}-[0-9]{4}$/';
                                if(preg_match($Phone, $_POST['userTel'])){
                                    $conn = mysqli_connect("localhost","hmp","mpr1234!","hmp");
                                    mysqli_query($conn, "set session character_set_connection=utf8;");
                                    mysqli_query($conn, "set session character_set_results=utf8;");
                                    mysqli_query($conn, "set session character_set_client=utf8;");
                                    $sql_insert = "INSERT INTO member (name, userid, password, mail, tel, birth, createDate, autologin) VALUES ('{$_POST['userName']}','{$_POST['userId']}','{$_POST['userPw']}','{$_POST['userMail']}','{$_POST['userTel']}','{$_POST['userBirth']}',now(),0)";
                                    mysqli_query($conn, $sql_insert);
                                    echo '<script> alert("가입되었습니다.");</script>';
                                    echo "<script>location.href='./index.php'</script>";
                                }else{
                                    echo '<script> alert("전화번호의 형식이 맞지 않습니다.");</script>';
                                }
                            }else{
                                echo '<script> alert("생년월일의 형식이 맞지 않습니다.");</script>';
                            }
                        }else{
                            echo '<script> alert("비밀번호가 다릅니다."); </script>';
                        }
                    }else{
                        if(strlen(($_POST['userPw'])<8) && ($lower_pwd==true && $upper_pwd==true)){
                            echo '<script> alert("8자 이상 입력"); </script>';
                        }else if(strlen(($_POST['userPw'])>=8) && ($lower_pwd==false && $upper_pwd==false)){
                            echo '<script> alert("대소문자 입력 필수"); </script>';
                        }
                    }
                }else{
                    echo '<script> alert("이메일 형식이 맞지 않음"); </script>';
                }
            }else{
                echo '<script> alert("이미 사용중인 아이디입니다."); </script>';
            }
        }
    }
 ?>
</body>
</html>