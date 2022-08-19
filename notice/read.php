<?php
	include '../head.php';

	$sql = "SELECT * FROM notice where idx='{$_GET['idx']}'";
	$notice_sql = mysqli_query($conn, $sql);
	$notice = $notice_sql->fetch_array();

	$cur_id = $_SESSION['userid'];
	$cur_pw = $_SESSION['userpwd'];
	$post_id = $notice['author'];

	$is_viewCount = false;
	if(!trim($_COOKIE['notice_'.$notice['idx']."_".$cur_id])){
		setcookie("notice_".$notice['idx']."_".$cur_id, $cur_id, time()+(60*60*24));
		$is_viewCount = true;
	}
	if($is_viewCount){
		if(trim($cur_id) != trim($post_id)){
			$viewSQL = "UPDATE notice SET view = view+1 WHERE idx = '{$_GET['idx']}'";
			mysqli_query($conn, $viewSQL);
		}
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
?><!DOCTYPE html>
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
		<div class="userinfo"><?php echo $notice['author']?> <?php echo $notice['createdate']?></div><div class="hidden"><input type="text" name="w_author" value="<?php echo $notice['author']?>"/></div>
		<div class="read_contents"><textarea class="contents" readonly><?php echo $notice['content']?></textarea></div>
		<div class="write_contents_text hidden"> <h3>내용</h3> </div>
		<div class="write_contents hidden"><textarea class="contents" name="contents"><?php echo $notice['content']?></textarea></div>
		<div class="div_pw"><input type="password" placeholder="password" class="read_input_pw" name="read_input_pw"> </div>
		<div class="div_secret_check hidden"><input type="checkbox" name="seceret_check" class="secret_check"/>비밀글</div>
		<div class="div_btn"><input type="submit" value="수정" class="update_btn" name="update_btn"/> <button class="cancel_btn hidden" name="cancel_btn">취소</button> <input type="submit" value="저장" class="save_btn hidden" name="save_btn"> <input type="submit" value="삭제" class="delete_btn" name="delete_btn"/></div>
	</form>
		<div class="comment_title"><h3>댓글</h3></div>
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

				if($row_num>0){
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
	if(array_key_exists('update_btn',$_POST)){
		if(empty(!$_POST['read_input_pw'])){
			$sql_select="SELECT password FROM member WHERE userid='{$_POST['w_author']}'"; 
			$result = mysqli_query($conn, $sql_select);
			if(mysqli_num_rows($result)>0){
				while($pw = mysqli_fetch_assoc($result)){
					if($pw['password'] == md5($cur_id.$_POST['read_input_pw'])){
?>
						<script>
							document.querySelector(".read_contents").classList.add("hidden");
							document.querySelector(".delete_btn").classList.add("hidden");
							document.querySelector(".read_title").classList.add("hidden");
							document.querySelector(".update_btn").classList.add("hidden");
							document.querySelector(".userinfo").classList.add("hidden");
							document.querySelector(".div_pw").classList.add("hidden");
							// document.querySelector(".back_btn").classList.add("hidden");
							document.querySelector(".comment_title").classList.add("hidden");
							document.querySelector(".table").classList.add("hidden");
							document.querySelector(".read_comment").classList.add("hidden");
							document.querySelector(".div_comment_btn").classList.add("hidden");
							document.querySelector(".div_secret_check").classList.remove("hidden");
							document.querySelector(".cancel_btn").classList.remove("hidden");
							document.querySelector(".write_title").classList.remove("hidden");
							document.querySelector(".write_title_text").classList.remove("hidden");
							document.querySelector(".write_contents_text").classList.remove("hidden");
							document.querySelector(".save_btn").classList.remove("hidden");
							document.querySelector(".write_contents").classList.remove("hidden");
						</script>
<?php
					}
					else{
						echo '<script>alert("비밀번호가 일치하지 않습니다."); console.log("'.$_POST['read_input_pw'].'","'.$pw['password'].'");</script>';
					}
				}
			}
		}else{
			echo '<script>alert("비밀번호를 입력하세요.");</script>';
		}
	}
	if(array_key_exists('save_btn',$_POST)){
		$sql_update = "UPDATE notice SET content='{$_POST['contents']}', title='{$_POST['new_title']}', updateDate=now() WHERE idx='{$_GET['idx']}'";
		mysqli_query($conn, $sql_update);
		echo '<script> alert("변경되었습니다."); </script>';
		header("Refresh:0");
	}
	if(array_key_exists('delete_btn',$_POST)){
		if(empty(!$_POST['read_input_pw'])){
			$sql_select="SELECT password FROM member WHERE userid='{$_POST['w_author']}'"; 
			$result = mysqli_query($conn, $sql_select);
			if(mysqli_num_rows($result)>0){
				while($pw = mysqli_fetch_assoc($result)){
					if($pw['password'] == $_POST['read_input_pw']){
						$sql_delete = "DELETE FROM notice WHERE idx='{$_GET['idx']}'";
						mysqli_query($conn, $sql_delete);
						echo '<script> alert("삭제되었습니다."); </script>';
						echo "<script>location.href='./notice.php'</script>";
					}
					else{
						echo '<script>alert("비밀번호가 일치하지 않습니다."); console.log("'.$_POST['read_input_pw'].'","'.$pw['password'].'");</script>';
					}
				}
			}
		}else{
			echo '<script>alert("비밀번호를 입력하세요.");</script>';
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
</html>