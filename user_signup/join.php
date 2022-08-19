<?php include '../head.php';?>

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
    <div class="container">
        <form method="POST">
            <h3>회원가입</h3>
            <table class="table_id">
                <tr>
                    <th><div><h5>이름</h5></div></th>
                    <td><div class="name"><input type="text" placeholder="이름" class="username" id="username" name="userName" autocomplete='off'></div></td>
                </tr>
                <tr>
                    <th><div><h5>아이디</h5></div></th>
                    <td><div class="id"><input type="text" placeholder="아이디" class="userid" id="userid" name="userId" autocomplete='off'></div></td>
                </tr>
                <tr>
                    <th><div><h5>이메일</h5></div></th>
                    <td><div class="email"><input type="text" placeholder="hong@naver.com" class="usermail" id="usermail" name="userMail" autocomplete='off'></div></td>
                </tr>
                <tr>
                    <th><div><h5>비밀번호</h5></div></th>
                    <td class="pw"><div><input type="password" placeholder="비밀번호 (대소문자 포함8자 이상)" class="userpw" id="userpw" name="userPw"></div></td>
                </tr>
                <tr>
                    <th><div><h5>&nbsp;</h5></div></th>
                    <td class="pw1"><div><input type="password" placeholder="비밀번호 재입력" class="userpw1" id="userw1" name="userPw1"></div>
                    </td>
                </tr>
            </table>
            <hr>
            <table class="table_info">
                <tr>
                    <th><div><h5>주소</h5></div></th>
                    <td class="td_addressNum"><div class="div_addressNum"><input type="text" placeholder="우편번호" class="addressNum" id="addressNum" name="addressNum" autocomplete='off'></div><div class="div_address_search_btn"><input type="button" onclick="address_search()" value="우편번호 찾기" class="address_search_btn"></div></td>
                </tr>
                <tr>
                    <th><div><h5>&nbsp;</h5></div></th>
                    <td class="address"><div><input type="text" placeholder="주소" class="useraddress" id="useraddress" name="useraddress" autocomplete='off'></div></td>
                </tr>
                <tr>
                    <th><div><h5>&nbsp;</h5></div></th>
                    <td class="td_detail_address">
                        <div class="div_detail_address"><input type="text" placeholder="상세 주소" class="detail_address" id="detail_address" name="detail_address" autocomplete='off'></div>
                        <div class="div_reference"><input type="text" placeholder="참고 항목" class="reference" id="reference" name="reference" autocomplete='off'></div>
                    </td>
                </tr>
                <tr>
                    <th><div><h5>생년월일</h5></div></th>
                    <td><div class="birth"><input type="text" placeholder="생년월일 (20220805)" class="userbirth" id="userbirth" name="userBirth" autocomplete='off'></div></td>
                </tr>
                <tr>
                    <th><div><h5>전화번호</h5></div></th>
                    <td><div class="tel"><input type="text" placeholder="전화번호 (010-1234-5678)" class="usertel" id="usertel" name="userTel" autocomplete='off'></div></td>
                </tr>
            </table>
            <table class="table_btn">
                <tr>
                    <td class="end"><button type="button" class="cancelBtn" onclick="location.href='../index.php'">취소</button>&nbsp;<button name="join_signup" class="sign_up_Btn">가입</button></td>
                </tr>
            </table>
        </form>        
    </div>
    <?php
        

        if(array_key_exists('join_signup',$_POST)){
            if(empty($_POST['userName']) || empty($_POST['userMail']) || empty($_POST['userId']) || empty($_POST['userPw']) || empty($_POST['userPw1']) || empty($_POST['addressNum']) || empty($_POST['useraddress']) || empty($_POST['detail_address']) || empty($_POST['userTel']) || empty($_POST['userBirth'])){
                echo "<script> alert('빈칸을 입력하세요.');</script>";
            }else{
                $sql = "SELECT userid FROM member WHERE userid='{$_POST['userId']}'";
                $res = mysqli_query($conn, $sql);

                //아이디 중복 확인
                if(!(mysqli_num_rows($res)>0)){
                    $mail = '/^[a-zA-Z0-9]{1}[a-zA-Z0-9\-_]+@[a-z0-9]{1}[a-z0-9\-]+[a-z0-9]{1}\.(([a-z]{1}[a-z.]+[a-z]{1}[a-z]+)|([a-z]+))$/';

                    //이메일
                    if(preg_match($mail, $_POST['userMail'])){  
                        $upper_pwd=false;
                        $lower_pwd=false;
                        
                        //비밀번호
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
                                        $password = md5($_POST['userId'].$_POST['userPw']);
                                        
                                        //주소-참고항목이 있을 경우
                                        if(empty($_POST['reference'])){
                                            $sql_insert = "INSERT INTO member (name, userid, password, mail, tel, birth, createDate, zip_code, address, addr_detail) 
                                                        VALUES ('{$_POST['userName']}','{$_POST['userId']}','$password','{$_POST['userMail']}','{$_POST['userTel']}','{$_POST['userBirth']}',now(), {$_POST['addressNum']}, '{$_POST['useraddress']}','{$_POST['detail_address']}')";
                                            mysqli_query($conn, $sql_insert);
                                            echo '<script> alert("가입되었습니다.");</script>';
                                            echo "<script>location.href='../index.php'</script>";
                                        }
                                        //주소-참고항목이 없을 경우
                                        else{
                                            $sql_insert = "INSERT INTO member (name, userid, password, mail, tel, birth, createDate, zip_code, address, addr_ref) 
                                                        VALUES ('{$_POST['userName']}','{$_POST['userId']}','$password','{$_POST['userMail']}','{$_POST['userTel']}','{$_POST['userBirth']}',now(), {$_POST['addressNum']}, '{$_POST['useraddress']}','{$_POST['detail_address']}' , '{$_POST['reference']}')";
                                            mysqli_query($conn, $sql_insert);
                                            echo '<script> alert("가입되었습니다.");</script>';
                                            echo "<script>location.href='../index.php'</script>";
                                        }
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
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
    <script>
        function address_search() {
            new daum.Postcode({
                oncomplete: function(data) {
                    // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                    // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                    // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                    var addr = ''; // 주소 변수
                    var extraAddr = ''; // 참고항목 변수
                    
                    //사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                    if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                        addr = data.roadAddress;
                    } else { // 사용자가 지번 주소를 선택했을 경우(J)
                        addr = data.jibunAddress;
                    }
           
                    // 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
                    if(data.userSelectedType === 'R'){
                        // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                        // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                        if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                            extraAddr += data.bname;
                        }
                        // 건물명이 있고, 공동주택일 경우 추가한다.
                        if(data.buildingName !== '' && data.apartment === 'Y'){
                            extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                        }
                        // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                        if(extraAddr !== ''){
                            extraAddr = ' (' + extraAddr + ')';
                        }
                        // 조합된 참고항목을 해당 필드에 넣는다.
                        document.getElementById("reference").value = extraAddr;
                    
                    } else {
                        document.getElementById("reference").value = '';
                    }

                    // 우편번호와 주소 정보를 해당 필드에 넣는다.
                    document.getElementById('addressNum').value = data.zonecode;
                    document.getElementById("useraddress").value = addr;
                    // 커서를 상세주소 필드로 이동한다.
                    document.getElementById("detail_address").focus();
                }
            }).open();
        }
    </script>
</body>
</html>