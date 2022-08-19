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

    <form method="POST" action="./search.php">
        <table class="id_table">
            <tr><td><input type="submit" class="id_search page" name="id_search" value="아이디 찾기"/></td><td><button type="button" class="pw_search" name="pw_search" onclick="location.href='./pw_search.php'">비밀번호 찾기</button></td></tr>
            <tr><td class="radio"><label for="email"><input type="radio" id="email" name="search" value="email"> 가입한 이메일로 찾기</label></td></tr>
            <tr><td colspan="2"><input type="text" class="input_mail" placeholder="이메일" name="input_mail"/></tr>
            <tr><td class="radio"><label for="phone"><input type="radio" id="phone" name="search" value="phone"/> 가입한 휴대폰으로 찾기</label></td></tr>
            <tr><td colspan="2"><input type="text" class="input_phone" placeholder="전화번호" name="input_phone/></tr>
            <tr><td colspan="2"><Button class="searchBtn" name="searchBtn">아이디 찾기</td></tr>
        </table>
        
    </form>
    <?php
        $radiobtn = $_POST['search'];
        if($radiobtn == "email"){
            echo '<script>console.log("email");</script>';
            $conn = mysqli_connect("localhost","hmp","mpr1234!","hmp");
            $sql = "SELECT mail FROM member WHERE mail='{$_POST['input_mail']}'";
            $res = mysqli_query($conn, $sql);
            if(mysqli_num_rows($res)>0){
                $to = $_POST['input_mail'];
                echo '<script>console.log("'.$_POST['input_mail'].'");</script>';
                $subject = "이메일 인증";
                $contents = "메일 발송 테스트";
                $result= mail($to, $subject, $contents);
                if($result){
                    echo '<script>console.log("mail success");</script>';
                }else{
                    echo '<script>console.log("mail fail");</script>';
                }
            }
        }else{
            echo '<script>console.log("phone");</script>';
        }
    ?>
</body>
</html>