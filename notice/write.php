<?php
    include '../head.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="write.css?after">
    <title>Document</title>
</head>
<body>
    <div><a href="../notice/notice.php"><img src="../img/home.png" style="width:40px; height:30px"/></a></div>
    <div class="header"><h1>게시글 작성</h1></div>
    <form method="POST" enctype="multipart/form-data">
        <table>
            <tr><th class="text">제목</th></tr>
            <tr><td><input type="text" class="write_title" name="write_title" placeholder="제목을 입력해주세요" autocomplete='off'></td></tr>
			<input type="hidden" class="title"/>
            
			<tr><th class="text">작성자</th></tr>
            <tr class="member"><td><input type="text" class="mem_write_author" name="member_author" value="<?php echo trim($_SESSION['userid']);?>" readonly></td></tr>
            <tr class="non_member hidden"><td><input type="text" class="write_author" name="non_member_author" placeholder="작성자를 입력해주세요" autocomplete='off'></td></tr>
			<input type="hidden" class="author"/>
            
			<tr><th class="text">내용</th></tr>
			<tr class="hidden"><td><textarea class="write_contents" name="contents" placeholder="내용을 입력해주세요"></textarea></td></tr>
            <tr class=""><td>
                <div class="editor_menu ">
                    <button type="button" id="btn-bold"> <b>B</b> </button>
                    <button type="button" id="btn-italic"> <i>I</i> </button>
                    <button type="button" id="btn-underline"> <u>U</u> </button>
                    <button type="button" id="btn-strike"> <s>S</s> </button>
                    <button type="button" id="btn-ordered-list"> OL </button>
                    <button type="button" id="btn-unordered-list"> UL </button>
                </div>
                <div id="editor" class="editor" contentEditable="true">
		        </div>
            </td></tr>
        </table>
        <div class="div_secret_check"><label><input type="checkbox" name="secret_check" class="secret_check"/> 비밀글</label></div>
        <div class="div_button">
			<input type="password" class="nonMem_pwd hidden" name="nonMem_pwd" placeholder="비밀번호를 입력하세요"/> 
			<inpyt type="hidden" class="pwd"/>
			<button type="button" class="btn" onclick="location.href='./notice.php'">취소</button> 
			<!-- <input type="submit" class="btn" name="saveBtn" value="저장"/> -->
			<input type="button" class="btn" name="saveBtn" onclick="save_html()" value="저장"/>
		<div>
    </form>
    <?php

    if(!trim($_SESSION['userid'])){
        echo '<script> document.querySelector(".non_member").classList.remove("hidden");
                        document.querySelector(".nonMem_pwd").classList.remove("hidden");
                        document.querySelector(".member").classList.add("hidden");</script>';
        $ismember = 1;
    }else{
        $ismember = 0;
    }

    // $check = isset($_POST['secret_check']) ? "checked" : "unchecked";
    // if($check == "checked"){
    //     $secret_post = 1;
    // }else{
    //     $secret_post = 0;
    // }

    // if(array_key_exists('saveBtn',$_POST)){

    //     if((($_FILES['fileUpload']['error'])==0)){
    //         $file_tmp_name = $_FILES['fileUpload']['tmp_name'];
    //         $file_name = $_FILES['fileUpload']['name'];
    //         //한글 파일명이 깨지지 않도록 보호
    //         $fileName = iconv("UTF-8", "EUC-KR",$_FILES['fileUpload']['name']);

    //         //파일 저장 경로 - document_root //파일 다운 경로 - server_name  다운로드는 url로 가야함
    //         $upload_folder = $_SERVER['DOCUMENT_ROOT'].'/hmp/mpr_notice/notice/upload_file/';
            
    //         move_uploaded_file($file_tmp_name, $upload_folder.$file_name);
            
    //     }else{
    //         $file_name=NULL;
    //     }
        
    //     if(!empty($_POST['title']) && ( !empty($_POST['member_author']) || !empty($_POST['non_member_author']) ) && ( !empty($_POST['contents']) || (($_FILES['fileUpload']['error'])==0) )){
    //         if($ismember==1){
    //             if(!empty($_POST['nonMem_pwd'])){
    //                 $sql_insert = "INSERT INTO notice (title, author, content, createdate, file, secret_post, member, nonMember_pwd) VALUES ('{$_POST['title']}','{$author}','{$_POST['contents']}',now(), '{$file_name}' ,{$secret_post} ,$ismember, '{$pwd}')";
    //                 mysqli_query($conn, $sql_insert);
    //                 echo "<script>alert('글쓰기가 완료되었습니다.');
    //                     location.href='./notice.php'</script>";
    //             }else{
    //                 echo "<script>alert('비밀번호를 입력하세요');</script>";
    //             }
    //         }else{
    //             $sql_insert = "INSERT INTO notice (title, author, content, createdate, file, secret_post, member) VALUES ('{$_POST['title']}','{$author}','{$_POST['contents']}',now(), '{$file_name}' ,{$secret_post} ,$ismember)";
    //                 mysqli_query($conn, $sql_insert);
    //                 echo "<script>alert('글쓰기가 완료되었습니다.');
    //                 location.href='./notice.php'</script>";
    //         }
            
    //     }else{
    //         echo "<script>alert('빈칸을 입력하세요');</script>";
    //     }
    // }
    ?>
    <script>
		<?php include './editorMenu.js'?>
	</script>
	<script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
	<script>
		function save_html(){
            const isChecked = document.querySelector(".secret_check").checked;
            if(isChecked == true){
                var secret = 1;
            }else{
                var secret = 0;
            }
            $(document).ready(function(){
                var title = $(".write_title").val();
				var jbHtml = $(".editor").html();
				if( <?php echo $ismember?> == 1 ){
                    
                    var pwd = $(".nonMem_pwd").val();
					var author = $(".write_author").val();

					if(pwd){
						$.post("http://192.168.0.52/hmp/mpr_notice/notice/write_dbSave.php", {"title":title,"author":author,"content":jbHtml,"secret_post":secret, "member": <?php echo $ismember?>,"nonMember": pwd}, function(data){
							alert("작성 완료되었습니다.");
							location.href='./notice.php';
						}, "json");
					}
				}else{
					var author = $(".mem_write_author").val();

					$.post("http://192.168.0.52/hmp/mpr_notice/notice/write_dbSave.php", {"title":title,"author":author,"content":jbHtml,"secret_post":secret, "member": <?php echo $ismember?>}, function(data){
						alert("작성 완료되었습니다.");
						location.href='./notice.php';
						}, "json");
					}
			});
		}
	</script>
</body>
</html>