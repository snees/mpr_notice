<?php
include 'session.php';

$conn = mysqli_connect("localhost","hmp","mpr1234!","hmp");
$sql = "SELECT * FROM notice where idx='{$_GET['idx']}'";
$notice_sql = mysqli_query($conn, $sql);
$notice = $notice_sql->fetch_array();

$cur_id = $_SESSION['userid'];
$post_id = $notice['author'];

$is_viewCount = false;
if(!trim($_COOKIE['notice_'.$notice['idx']."_".$cur_id])){
	setcookie("notice_".$notice['idx']."_".$cur_id, $cur_id, time()+(60*60*24));
	$is_viewCount = true;
}
if($is_viewCount){
	if(trim($cur_id) != trim($post_id)){
		$viewSQL = "UPDATE notice SET view = view+1 WHERE author = '{$post_id}'";
		mysqli_query($conn, $viewSQL);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="read.css?after">
    <title>Document</title>
</head>
<body>
	<form method="POST">
    <div class="read_title"> <h1><?php echo $notice['title']?></h1> </div>
	<div class="write_title_text hidden"> <h3>제목</h3> </div>
	<div class="write_title hidden"> <input type="text" class="new_title" name="new_title" value="<?php echo $notice['title']?>"/></div>
    <div class="userinfo"><?php echo $notice['author']?> <?php echo $notice['createdate']?></div><div class="hidden"><input type="text" name="w_author" value="<?php echo $notice['author']?>"/></div>
	<div class="read_contents"><textarea readonly><?php echo $notice['content']?></textarea></div>
	<div class="write_contents_text hidden"> <h3>내용</h3> </div>
	<div class="write_contents hidden"><textarea name="contents"><?php echo $notice['content']?></textarea></div>
	<div class="div_pw"><input type="password" placeholder="password" class="read_input_pw" name="read_input_pw"></div>
	<div class="div_btn"><Button class="back_btn"><a href="./notice.php">목록으로</a></button><input type="submit" value="수정" class="update_btn" name="update_btn"/> <button class="cancel_btn hidden" name="cancel_btn">취소</button> <input type="submit" value="저장" class="save_btn hidden" name="save_btn"> <input type="submit" value="삭제" class="delete_btn" name="delete_btn"/></div>
	</form>

</body>
<?php
	if(array_key_exists('update_btn',$_POST)){
		if(empty(!$_POST['read_input_pw'])){
			$sql_select="SELECT password FROM member WHERE userid='{$_POST['w_author']}'"; 
			$result = mysqli_query($conn, $sql_select);
			if(mysqli_num_rows($result)>0){
				while($pw = mysqli_fetch_assoc($result)){
					if($pw['password'] == $_POST['read_input_pw']){
						echo '<script> document.querySelector(".read_contents").classList.add("hidden");
						document.querySelector(".delete_btn").classList.add("hidden");
						document.querySelector(".read_title").classList.add("hidden");
						document.querySelector(".update_btn").classList.add("hidden");
						document.querySelector(".userinfo").classList.add("hidden");
						document.querySelector(".div_pw").classList.add("hidden");
						document.querySelector(".back_btn").classList.add("hidden");
						document.querySelector(".cancel_btn").classList.remove("hidden");
						document.querySelector(".write_title").classList.remove("hidden");
						document.querySelector(".write_title_text").classList.remove("hidden");
						document.querySelector(".write_contents_text").classList.remove("hidden");
						document.querySelector(".save_btn").classList.remove("hidden");
						document.querySelector(".write_contents").classList.remove("hidden");</script>';
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
?>
</html>