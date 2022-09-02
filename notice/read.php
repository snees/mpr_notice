<?php
	include '../head.php';
	
	$sql = "SELECT * FROM notice where idx='{$_GET['idx']}'";
	$notice_sql = mysqli_query($conn, $sql);
	$notice = mysqli_fetch_array($notice_sql);

	$notice_idx = 'notice_'.$_GET['idx'];

	if(trim($_SESSION['userid'])){
		$cur_id = $_SESSION['userid'];
		$cur_pw = $_SESSION['userpwd'];
		$post_id = $notice['author'];

		$memberView = false;
		if(!trim($_COOKIE['notice_'.$notice['idx']."_".$cur_id])){
			setcookie("notice_".$notice['idx']."_".$cur_id, $cur_id, time()+(60*60*24));
			$memberView = true;
		}

		if($memberView){
			if(trim($cur_id) != trim($post_id)){
				$viewSQL = "UPDATE notice SET view = view+1 WHERE idx = '{$_GET['idx']}'";
				mysqli_query($conn, $viewSQL);
			}
		}

		// 좋아요 확인

		$sql = "SELECT is_like FROM likeTbl where notice_idx = '{$notice_idx}' AND liked_id='{$cur_id}'";
		$like_sql = mysqli_query($conn, $sql);
		$like = mysqli_fetch_array($like_sql);

		if($like['is_like'] == 1){
			$chk_Like = "hidden";
			$unlike = "";
		}else{
			$chk_Like="";
			$unlike = "hidden";
		}

	}else{
		$chk_Like = "hidden";
		$unlike = "hidden";
		$non_memberView = false;
		if(!trim($_COOKIE['notice_'.$notice['idx']."_".$_COOKIE['non-member']])){
			setcookie("notice_".$notice['idx']."_".$_COOKIE['non-member'], $_COOKIE['non-member'], time()+(60*60*24));
			$non_memberView = true;
		}
		if($non_memberView){
			$viewSQL = "UPDATE notice SET view = view+1 WHERE idx = '{$_GET['idx']}'";
			mysqli_query($conn, $viewSQL);
		}
	}

	//비밀글 체크
	if(trim($notice['secret_post']==1)){
		$ischcecked = 'checked';
	}


	// if ( trim($_GET['mode'])=='update' ) {
	// 	$sql= "SELECT * FROM comment where idx='{$_GET['comment_idx']}'";
	// 	$comment_sql = mysqli_query($conn, $sql);
	// 	$comment = $comment_sql->fetch_array();

	// 	if($cur_id == $commnet['userid'] && $cur_pw == $comment['userpw']){
	// 		$sql_update = "UPDATE comment SET content='{$_POST['comment']}', updateDate=now() WHERE idx='{$_GET['comment_idx']}'";
	// 		mysqli_query($conn, $sql_update);
	// 	}
	// }
	if ( trim($_GET['mode'])=='delete' ) {
		$sql= "SELECT * FROM comment where idx='{$_GET['comment_idx']}'";
		$comment_sql = mysqli_query($conn, $sql);
		$comment = $comment_sql->fetch_array();

		if($comment['userid']==$cur_id){
			$sql_delete = "DELETE FROM comment where idx='{$_GET['comment_idx']}'";
			mysqli_query($conn, $sql_delete);
		}else{
			echo '<script>alert("삭제 권한이 없습니다.");</script>';
		}
	}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="read.css?after">
    <title>Document</title>
</head>
<body>
	<div><a href="../notice/notice.php"><img src="../img/home.png" style="width:40px; height:30px"/></a></div>
	<form method="POST">
		<div class="read_title"> <h1><?php echo $notice['title']?></h1> </div>
		<div class="write_title_text hidden"> <h3>제목</h3> </div>
		<div class="write_title hidden"> <input type="text" class="new_title" name="new_title" value="<?php echo $notice['title']?>"/></div>
		<div class="userinfo">
			<?php echo $notice['author']?> <?php echo $notice['createdate']?>
			<button type="button" class="unlike_btn <?php echo $chk_Like ?>" name="unlike_btn" onclick="like(<?php echo $_GET['idx'] ?>,'<?php echo $_SESSION['userid']?>');"></button>
			<button type="button" class="like_btn <?php echo $unlike ?>" name="like_btn" onclick="unlike(<?php echo $_GET['idx'] ?>,'<?php echo $_SESSION['userid']?>');"></button>
		</div>
		<div class="hidden"><input type="text" name="w_author" value="<?php echo $notice['author']?>"/></div>
		
		<div class="editor_view" contentEditable="false">
			<?php 
				echo $notice['content'];
				echo $notice['file'];
			?>
			<!-- <?php

				echo $_SERVER["SERVER_NAME"].'/hmp/mpr_notice/notice/upload_file/'.$notice['file'];
			?> -->
		</div>
		<div class="write_contents_text hidden"> <h3>내용</h3> </div>

		<div class="editor_menu hidden" >
			<button type="button" id="btn-bold"> <b>B</b> </button>
			<button type="button" id="btn-italic"> <i>I</i> </button>
			<button type="button" id="btn-underline"> <u>U</u> </button>
			<button type="button" id="btn-strike"> <s>S</s> </button>
			<button type="button" id="btn-ordered-list"> OL </button>
			<button type="button" id="btn-unordered-list"> UL </button>
		</div>
		<div id="editor" class="editor hidden" contentEditable="true">
			<?php echo $notice['content']?>
		</div>
		
		<div class="div_pw hidden"><input type="password" placeholder="password" class="read_input_pw" name="read_input_pw"> </div>
		<div class="div_secret_check hidden"><label><input type="checkbox" name="secret_check" class="secret_check" <?php echo $ischcecked?>/> 비밀글</label></div>
		<div class="div_btn">
			<input type="submit" value="수정" class="update_btn" name="update_btn"/> 
			<!-- <input type="password" class="non_member_pwd hidden" name="non_member_pwd" placeholder="비밀번호를 입력하세요"/> -->
			<button class="cancel_btn hidden" name="cancel_btn">취소</button> 
			<button type="button" class="save_btn hidden" name="save_btn" onclick="save_html(<?php echo $_GET['idx']?>);">저장</button> 
			<input type="submit" value="삭제" class="delete_btn" name="delete_btn"/>
		</div>
	</form>
		<div class="comment_title"><h3>댓글</h3></div>
		<hr class="comment_title_line">
		<table class="table">
			<?php
				$strQueryString= "idx={$notice['idx']}";

				if(isset($_GET['page'])){
					$page = $_GET['page'];
				} else {
					$page = 1;
				}

				$strSQL = "SELECT * FROM comment WHERE notice_idx = {$notice['idx']}";
				$sql = mysqli_query($conn, $strSQL);
				$row_num = mysqli_num_rows($sql);

				if($row_num <=0 ){
			?>
					<div class="non_member">댓글이 없습니다.</div>
			<?php
				}
				if($row_num > 0){
					include '../cal_page.php';
					while($comment = $res2->fetch_array()){
			?>
					<tr>
						<td><b><?php echo $comment['userid'];?> <?php echo $comment['createdate'];?></b></td>
						<td class="comment_btn"> <!--<a href="./read.php?<?php echo $strQueryString?>&mode=update&comment_idx=<?php echo $comment['idx'];?>" class="comment_update">수정</a> --> <a href="./read.php?<?php echo $strQueryString?>&mode=delete&comment_idx=<?php echo $comment['idx'];?>" class="comment_delete">삭제</a></td>
					</tr>
					<tr>
						<td colspan="2" class="sql_comment"><?php echo $comment['content']?></td>
						<td colspan="2" class="comment_update hidden"><input type="text" value="<?php echo $comment['content']?>"/></td>
					</tr>
			<?php
				}
			?>
		</table>
		<?php
			include '../paging.php';
				}
		?>
	<form method="POST">
		<div class="read_comment"><textarea class="comment" name="comment" placeholder="댓글을 작성하세요"></textarea></div>
		<div class="div_comment_btn"><Button class="comment_write_btn" name="comment_btn">작성</button></div>
	</form>
</body>
<?php

	//비회원일 경우 댓글 입력 불가
	if(!trim($_SESSION['userid'])){
?>
		<script>
			document.querySelector(".read_comment").classList.add("hidden");
			document.querySelector(".div_comment_btn").classList.add("hidden");
			document.querySelector(".div_pw").classList.remove("hidden");
		</script>
<?php
	}

	if(array_key_exists('delete_btn',$_POST)){
		//회원게시물 삭제
		if(trim($_SESSION['userid']) == $post_id){
			$like_delete = "DELETE FROM likeTbl WHERE notice_idx='{$notice_idx}'";
			mysqli_query($conn, $like_delete);
			$sql_delete = "DELETE FROM notice WHERE idx='{$_GET['idx']}'";
			mysqli_query($conn, $sql_delete);

			echo '<script> alert("삭제되었습니다."); </script>';
			echo "<script>location.href='./notice.php'</script>";
		}else if(trim($_SESSION['userid']) != $post_id){
			echo '<script> alert("삭제권한이 없습니다."); </script>';
		}else{
			//비회원 게시물 삭제
			if(!empty($_POST['read_input_pw'])){
				if(trim($notice['nonMember_pwd']) == trim($_POST['read_input_pw'])){
					$like_delete = "DELETE FROM likeTbl WHERE notice_idx='{$notice_idx}'";
					mysqli_query($conn, $like_delete);
					$sql_delete = "DELETE FROM notice WHERE idx='{$_GET['idx']}'";
					mysqli_query($conn, $sql_delete);
					echo '<script> alert("삭제되었습니다."); </script>';
					echo "<script>location.href='./notice.php'</script>";
				}else{
					echo '<script>alert("비밀번호가 일치하지 않습니다."); console.log("'.$_POST['read_input_pw'].'","'.$notice['nonMember_pwd'].'");</script>';
				}

			}
		}
	}		
//게시글 수정
	if(array_key_exists('update_btn',$_POST)){

		if((isset($_SESSION['userid'])) == $post_id){
?>
		<script>
			document.querySelector(".delete_btn").classList.add("hidden");
			document.querySelector(".read_title").classList.add("hidden");
			document.querySelector(".update_btn").classList.add("hidden");
			document.querySelector(".userinfo").classList.add("hidden");
			document.querySelector(".comment_title").classList.add("hidden");
			document.querySelector(".table").classList.add("hidden");
			document.querySelector(".read_comment").classList.add("hidden");
			document.querySelector(".div_comment_btn").classList.add("hidden");
			document.querySelector(".comment_title_line").classList.add("hidden");
			document.querySelector(".editor_view").classList.add("hidden");
			document.querySelector(".editor_menu").classList.remove("hidden");
			document.querySelector(".editor").classList.remove("hidden");
			document.querySelector(".div_secret_check").classList.remove("hidden");
			document.querySelector(".cancel_btn").classList.remove("hidden");
			document.querySelector(".write_title").classList.remove("hidden");
			document.querySelector(".write_title_text").classList.remove("hidden");
			document.querySelector(".write_contents_text").classList.remove("hidden");
			document.querySelector(".save_btn").classList.remove("hidden");
		</script>
<?php
		}else if(trim($_SESSION['userid']) != $post_id){
			echo '<script> alert("수정권한이 없습니다."); </script>';
		}
		if(!trim($_SESSION['userid'])){
			if(trim($notice['nonMember_pwd']) == trim($_POST['read_input_pw'])){
?>
				<script>
					document.querySelector(".delete_btn").classList.add("hidden");
					document.querySelector(".read_title").classList.add("hidden");
					document.querySelector(".update_btn").classList.add("hidden");
					document.querySelector(".userinfo").classList.add("hidden");
					document.querySelector(".comment_title").classList.add("hidden");
					document.querySelector(".table").classList.add("hidden");
					document.querySelector(".read_comment").classList.add("hidden");
					document.querySelector(".div_comment_btn").classList.add("hidden");
					document.querySelector(".non_member").classList.add("hidden");
					document.querySelector(".comment_title_line").classList.add("hidden");
					document.querySelector(".editor_view").classList.add("hidden");
					document.querySelector(".editor_menu").classList.remove("hidden");
					document.querySelector(".editor").classList.remove("hidden");
					document.querySelector(".div_secret_check").classList.remove("hidden");
					document.querySelector(".cancel_btn").classList.remove("hidden");
					document.querySelector(".write_title").classList.remove("hidden");
					document.querySelector(".write_title_text").classList.remove("hidden");
					document.querySelector(".write_contents_text").classList.remove("hidden");
					document.querySelector(".save_btn").classList.remove("hidden");
				</script>
<?php
			}
		}

		$check = isset($_POST['secret_check']) ? "checked" : "unchecked";
		if($check == "checked"){
			$secret_post = 1;
		}else{
			$secret_post = 0;
		}
	
	}
	if(array_key_exists('comment_btn',$_POST)){
		if(!empty($_POST['comment']) && !empty($cur_id) && !empty($cur_pw)){
			$sql_insert = "INSERT INTO comment (notice_idx, userid, userpw, content, createdate) VALUES ({$notice['idx']},'{$cur_id}','{$cur_pw}','{$_POST['comment']}', now())";
			mysqli_query($conn, $sql_insert);
			header("Refresh:0");
		}else{

		}
	}

	
?>
<!-- editor_menu -->
	<script>
		<?php include './editorMenu.js'?>
	</script>
	<script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
	<script>
		function save_html(idx){

			$(document).ready(function(){
				var jbHtml = $(".editor").html();
				var title = $(".new_title").val();
				$.post("http://192.168.0.52/hmp/mpr_notice/notice/contentEditable.php", {"content":jbHtml, "idx":idx, "new_title": title}, function(data){
					alert("수정되었습니다.");
					location.href='./read.php?idx='+idx;
				}, "json");
			});
		}

		function like(idx, userid){
			document.querySelector(".like_btn").classList.remove("hidden");
			document.querySelector(".unlike_btn").classList.add("hidden");

			if(userid){
				$.post("http://192.168.0.52/hmp/mpr_notice/notice/like_dbSave.php", {"notice_idx":idx, "userid":userid}, function(data){
					console.log(data.idx, data.userid);
				}, "json");
			}

		}

		function unlike(idx, userid){
			document.querySelector(".like_btn").classList.add("hidden");
			document.querySelector(".unlike_btn").classList.remove("hidden");

			if(userid){
				$.post("http://192.168.0.52/hmp/mpr_notice/notice/like_dbDel.php", {"notice_idx":idx, "userid":userid}, function(data){
					console.log(data.idx, data.userid);
				}, "json");
			}
		}
	</script>
</html>