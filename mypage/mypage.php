<?php
    include '../head.php';
    
    //userinfo
    $member_sql = "SELECT * FROM member WHERE userid = '{$_SESSION['userid']}'";
    $member_res = mysqli_query($conn, $member_sql);
    $member = $member_res->fetch_array();

    //user-notice
    // $notice_sql = "SELECT * FROM notice WHERE author='{$member['userid']}'";
    // $notice_res = mysqli_query($conn, $notice_sql);
    // $notice = $notice_res->fetch_array();

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mypage.css?after">
    <title>Document</title>
</head>
<body>
    <div><a href="../notice/notice.php"><img src="../img/home.png" style="width:40px; height:30px"/></a></div>
    <h1>마이페이지</h1>
    <hr>
    <div class="select_link">
        <a href="./mypage.php" class="userinfo_update cur_page">회원정보 수정</a>
        <a href="./mypage.php?mode=mypost" class="mypost">나의 게시글</a>
        <a href="./mypage.php?mode=leave" class="leave">탈퇴하기</a>
    </div>
<!-- userinfo_update-->
        <div class="userinfo">
            <form method="post">
                <table>
                    <tr class="update hidden">
                        <th><h4>필수 입력사항</h4></th>
                    </tr>
                    <tr class="read">
                        <th>이름</th><td><?php echo $member['name']?></td>
                    </tr>
                    <tr class="update hidden">
                        <th>이름</th><td><input type="text" name="name_update" value="<?php echo $member['name']?>" placeholder="이름을 입력하세요"/></td>
                    </tr>
                    <tr class="read">
                        <th>아이디</th><td><?php echo $member['userid']?></td>
                    </tr>
                    <tr class="update hidden">
                        <th>아이디</th><td><input type="text" name="id_update" value="<?php echo $member['userid']?>" placeholder="아이디를 입력하세요"/></td>
                    </tr>
                    <tr class="read">
                        <th>생년월일</th><td><?php echo $member['birth']?></td>
                    </tr>
                    <tr class="update hidden">
                        <th>생년월일</th><td><input type="text" name="birth_update" value="<?php echo $member['birth']?>" placeholder="2000-01-01"/></td>
                    </tr>
                    <tr class="update hidden">
                        <th>비밀번호</th><td><input type="password" name="pwd_update" placeholder="비밀번호를 입력하세요"/>&nbsp;<b>비밀번호 확인</b>&nbsp;<input type="password" name="pwd_update1" placeholder="비밀번호를 한번 더 입력하세요"/></td>
                        
                    </tr>
                    <tr class="read">
                        <th>휴대폰 번호</th><td><?php echo $member['tel']?></td>
                    </tr>
                    <tr class="update hidden">
                        <th>휴대폰 번호</th><td><input type="text" name="tel_update" value="<?php echo $member['tel']?>" placeholder="010-0000-0000"/></td>
                    </tr>
                    <tr class="read">
                        <th>이메일</th><td><?php echo $member['mail']?></td>
                    </tr>
                    <tr class="update hidden">
                        <th>이메일</th><td><input type="text" name="mail_update" value="<?php echo $member['mail']?>" placeholder="hongildong@korea.com"/></td>
                    </tr>
                    <tr class="read">
                        <th>주소</th><td><?php echo $member['address']?> <?php echo $member['addr_detail']?> (<?php echo $member['zip_code']?>) </td>
                    </tr>
                    <tr class="read">
                        <th>&nbsp;</th><td><div id="map"></div></td>
                    </tr>
                    <tr class="update hidden">
                        <th class="address"><div><h5>주소</h5></div></th>
                        <td class="td_addressNum address">
                            <div class="div_addressNum">
                                <input type="text" placeholder="우편번호" class="addressNum" id="addressNum" name="addressNum" value="<?php echo $member['zip_code']?>" autocomplete='off'>
                            </div> 
                            <div class="div_address_search_btn">
                                <input type="button" onclick="address_search()" value="우편번호 찾기" class="address_search_btn">
                            </div>
                            <div>
                                <input type="text" placeholder="주소" class="useraddress" id="useraddress" name="useraddress" value="<?php echo $member['address']?>"autocomplete='off'>
                            </div>
                            <div class="div_detail_address"><input type="text" placeholder="상세 주소" class="detail_address" id="detail_address" name="detail_address" value="<?php echo $member['addr_detail']?>" autocomplete='off'></div>
                            <div class="div_reference"><input type="text" placeholder="참고 항목" class="reference" id="reference" name="reference"  value="<?php echo $member['addr_ref']?>" autocomplete='off'></div>
                        </td>
                </table>
                <div class="div_update_btn"><button class="update_btn" name="update_btn">수정</button></div>
                <div class="div_save_btn hidden"><Button type="button" class="back_btn" name="back_btn" onclick="location.href='./mypage.php'">취소</Button> <Button class="save_btn" name="save_btn">저장</Button></div>
            </form>
        </div>
    <?php

        if(array_key_exists('update_btn',$_POST)){
    ?>
                <script>
                    var querySelectAll = document.querySelectorAll(".read");
                    var querySelectAll_rm = document.querySelectorAll(".update");
                    for(var i=0; i< querySelectAll.length; i++){
                        querySelectAll[i].classList.add("hidden");
                    }
                    for(var i=0; i<querySelectAll_rm.length; i++){
                        querySelectAll_rm[i].classList.remove("hidden");
                    }
                    document.querySelector(".div_update_btn").classList.add("hidden");
                    document.querySelector(".div_save_btn").classList.remove("hidden");
                </script>
    <?php
        }
        if(array_key_exists('save_btn',$_POST)){
            //비밀번호를 변경할 경우
            if(!empty($_POST['pwd_update'])){
                for($i=0; $i<strlen($_POST['pwd_update']); $i++){
                    $pwd_arr[$i] = substr($_POST['pwd_update'],$i,1);
                    if(97<= ord($pwd_arr[$i]) && ord($pwd_arr[$i]) <=122){
                        $lower_pwd=true;
                    }
                }
                for($i=0; $i<strlen($_POST['pwd_update']); $i++){
                    $pwd_arr[$i] = substr($_POST['pwd_update'],$i,1);
                    if(65<= ord($pwd_arr[$i]) && ord($pwd_arr[$i]) <=90){
                        $upper_pwd=true;
                    }
                }
                if(strlen($_POST['pwd_update'])>=8 && $upper_pwd==true && $lower_pwd==true){
                    if($_POST['pwd_update'] == $_POST['pwd_update1']){
                        $password = md5($_POST['id_update'].$_POST['pwd_update']);
                        if(empty($_POST['reference'])){
                            $member_update = "UPDATE member SET name='{$_POST['name_update']}', userid='{$_POST['id_update']}', password='$password', tel='{$_POST['tel_update']}', mail='{$_POST['mail_update']}', birth='{$_POST['birth_update']}', zip_code={$_POST['addressNum']}, address='{$_POST['useraddress']}', addr_detail='{$_POST['detail_address']}' WHERE userid='{$_SESSION['userid']}'";
                        }else{
                            $member_update = "UPDATE member SET name='{$_POST['name_update']}', userid='{$_POST['id_update']}', password='$password', tel='{$_POST['tel_update']}', mail='{$_POST['mail_update']}', birth='{$_POST['birth_update']}', zip_code={$_POST['addressNum']}, address='{$_POST['useraddress']}', addr_detail='{$_POST['detail_address']}', addr_ref='{$_POST['reference']}' WHERE userid='{$_SESSION['userid']}'";
                        }
                        mysqli_query($conn, $member_update);
                        header("Refresh:0");
                    }else{
                        echo '<script> alert("비밀번호가 일치하지 않습니다."); </script>';
                    }
                }else{
                    if(strlen(($_POST['userPw'])<8) && ($lower_pwd==true && $upper_pwd==true)){
                        echo '<script> alert("8자 이상 입력"); </script>';
                    }else if(strlen(($_POST['userPw'])>=8) && ($lower_pwd==false && $upper_pwd==false)){
                        echo '<script> alert("대소문자 입력 필수"); </script>';
                    }
                }
            }else{
                if(empty($_POST['reference'])){
                    $member_update = "UPDATE member SET name='{$_POST['name_update']}', userid='{$_POST['id_update']}', tel='{$_POST['tel_update']}', mail='{$_POST['mail_update']}', birth='{$_POST['birth_update']}', zip_code={$_POST['addressNum']}, address='{$_POST['useraddress']}', addr_detail='{$_POST['detail_address']}' WHERE userid='{$_SESSION['userid']}'";
                }else{
                    $member_update = "UPDATE member SET name='{$_POST['name_update']}', userid='{$_POST['id_update']}', tel='{$_POST['tel_update']}', mail='{$_POST['mail_update']}', birth='{$_POST['birth_update']}', zip_code={$_POST['addressNum']}, address='{$_POST['useraddress']}', addr_detail='{$_POST['detail_address']}', addr_ref='{$_POST['reference']}' WHERE userid='{$_SESSION['userid']}'";
                }
                mysqli_query($conn, $member_update);
                header("Refresh:0");
            }
        }
    ?>
<!-- mypost-->
    <?php
        if(trim($_GET['mode'])=='mypost'){
            include 'mypost.php';
        }
    ?>
<!--leave-->
    <?php
        if(trim($_GET['mode'])=='leave'){
            
    ?>
        <script>
            if(confirm("탈퇴하시겠습니까?")){
                location.href="./user_delete.php";
            }else{
                location.href="./mypage.php";
            }
        </script>
    <?php
        }
    ?>
<!-- 주소찾기 api -->
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

    <!-- map api -->
    <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=a646543869ea434781203a39d3559b41&libraries=services,clusterer,drawing"></script>
    <script>
        var container = document.getElementById('map'); //지도를 담을 영역의 DOM 레퍼런스
        var options = { //지도를 생성할 때 필요한 기본 옵션
            center: new kakao.maps.LatLng(33.450701, 126.570667), //지도의 중심좌표.
            level: 3 //지도의 레벨(확대, 축소 정도)
        };

        var map = new kakao.maps.Map(container, options); //지도 생성 및 객체 리턴

        // 주소-좌표 변환 객체를 생성합니다
        var geocoder = new kakao.maps.services.Geocoder();

        // 주소로 좌표를 검색합니다
        geocoder.addressSearch('<?php echo $member['address']?>', function(result, status) {

            // 정상적으로 검색이 완료됐으면 
            if (status === kakao.maps.services.Status.OK) {

                var coords = new kakao.maps.LatLng(result[0].y, result[0].x);

                // 결과값으로 받은 위치를 마커로 표시합니다
                var marker = new kakao.maps.Marker({
                    map: map,
                    position: coords
                });

                marker.setMap(map);

                // 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
                map.setCenter(coords);
            } 
        });    
    </script>
</body>
</html>