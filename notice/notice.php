<?php
include '../head.php';
// include './modal.php';

//---- $_GET
//---- $_POST
//---- $_REQUEST
if ( trim($_GET['mode'])=='logout' ) {
  $_SESSION['userid'] = ''; // isset사용할거면 null로 지정
  $_SESSION['userpwd'] = '';
  
  // session_destroy();

  echo '<script>alert("로그아웃 되었습니다.");</script>';
  echo "<script>location.href='../index.php'</script>";
}
//탈퇴
// if ( trim($_GET['mode'])=='leave' ) {
// mysqli_query($conn,"DELETE FROM member WHERE userid='{$_SESSION['userid']}'");
// mysqli_query($conn,"DELETE FROM notice WHERE author='{$_SESSION['userid']}'");
// $auto_increment_reset= mysqli_query($conn,"ALTER table notice idx=1");
// mysqli_query($conn,"UPDATE notice SET author='unknown' WHERE author='{$_SESSION['userid']}'");
// echo '<script>alert("탈퇴되었습니다.");</script>';
// $_SESSION['userid'] = ''; // isset사용할거면 null로 지정
// $_SESSION['userpwd'] = '';
// setcookie('id','',time()-999999);
// echo "<script>location.href='../index.php'</script>";
// }

$strSearch = trim($_GET['search']);
$keyword = trim($_GET['input_search']);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="notice.css?after">
    <title>Document</title>
	
	 <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
	<div><a href="../notice/notice.php"><img src="../img/home.png" style="width:40px; height:30px"/></a></div>
	<div id="notice_list" class="modal-overlay">
		<form method="POST">
    		<div class="userinfo">
				<div class="mypage_btn"><a href="../mypage/mypage.php">마이페이지</a></div>
				<div class="logout_btn"><a href="./notice.php?mode=logout">로그아웃</a></div>
				<!-- <div class="leave_btn"><a href="./notice.php?mode=leave">탈퇴하기</a></div> -->
    		</div>
  		</form>
  		<div class="notice"><h1>게시판</h1></div>
    	<form method="GET" action="./notice.php?mode=search" class="form">
      		<input type="hidden" name="mode" value="search">
      		<div class="div_search_btn">
          		<select name="search">
					<option value="title" <?php echo trim($strSearch)=='title'?' selected ':'';?>>제목</option>
					<option value="author" <?php echo trim($strSearch)=='author'?' selected ':'';?>>작성자</option>
          		</select>
          		<input type="text" class="input_search" name="input_search" value="<?php echo trim($keyword);?>" placeholder="검색어 입력" autocomplete='off'>
          		<input type="submit" class="search_btn" name="search_btn" value="검색"/>
				<button type="button" onclick="location.href='./write.php'">글쓰기</button>
      		</div>
    	</form>
		<div class="table">
			<table>
				<thead>
				<tr>
					<th>번호</th>
						<th class="title">제목</th>
						<th>작성자</th>
						<th>작성일</th>
						<th>조회수</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$arryWhere = array();
						$strQueryString= "";

						if ( trim($_GET['search']) && trim($_GET['input_search']) ) {
							$strWhere = ' and ';
							$arryWhere[] = " `{$strSearch}` like '%{$keyword}%' ";
							$strWhere.= implode(' and ', $arryWhere);//---- 배열로 만든다. explode('@', '문자열@문자열@문자열')
							//echo $strWhere;exit;
							$strQueryString.= "&search={$strSearch}&input_search={$keyword}";
						}
						echo '<script>console.log("'.$selected.'");</script>';
						if(isset($_GET['page'])){
							$page = $_GET['page'];
						} else {
							$page = 1;
						}
						//$strQueryString.= "&page={$page}";

						//$strWhere = " `{$selected}`='{$keyword}' ";
						$strSQL = " SELECT * FROM notice where (1) {$strWhere} ";
						$sql = mysqli_query($conn, $strSQL);
						$row_num = mysqli_num_rows($sql); //게시판 총 레코드 수
							
						if($row_num == 0){
					?>
							<tr>
								<td>&nbsp;</td>
								<td>검색결과가 없습니다.</td>
								<td colspan="3">&nbsp;</td>
							</tr>
					<?php
						// echo "<script>location.href='./notice.php'</script>";
						} else {
							$list = 5;
							$block_ct = 10;
									
							$block_num = ceil($page/$block_ct); // 현재 페이지 블록 구하기
							$block_start = (($block_num - 1) * $block_ct) + 1; // 블록의 시작번호
							$block_end = $block_start + $block_ct - 1; //블록 마지막 번호
							
							
							$total_page = ceil($row_num / $list);
							if($block_end > $total_page) {
								$block_end = $total_page; //만약 블록의 마지박 번호가 페이지수보다 많다면 마지박번호는 페이지 수
							}
							$total_block = ceil($total_page/$block_ct); //블럭 총 개수
							$start_num = ($page-1) * $list; //시작번호 (page-1)에서 $list를 곱한다.

							$first_num = $row_num-$list*($page-1);

							$strSQL.= " ORDER BY idx DESC LIMIT {$start_num}, {$list} ";
							$res2 = mysqli_query($conn,$strSQL);
							
							while($notice = mysqli_fetch_array($res2)){
								// print_r($notice);
								// echo '<br>';

								$title=$notice["title"]; 
								if(strlen($title)>30){ 
									$title=str_replace($notice["title"],mb_substr($notice["title"],0,30,"utf-8")."...",$notice["title"]);
								}
					?>
								<tr>
									<td><?php echo $first_num; ?></td>
									<td><a href="#" class="modal" onclick="modal('<?php echo intval($notice['idx']);?>', '<?php echo intval($notice['secret_post']);?>');"><?php echo $title;?></a></td>
									<td><?php echo $notice['author']?></td>
									<td>
										<?php 
										if(empty($notice['updateDate'])){
											echo $notice['createdate'];
										}else{
											echo $notice['updateDate'];
										}
										?>
									</td>
									<td>
										<?php
										echo $notice['view']; 
										?>
									</td>
								</tr>
						
							<?php
								$first_num--;
							}
							?>
				</tbody>
         </table>
    	</div>
    	<!---페이징 넘버 --->
		<?php
						}
						include '../paging.php';
		?>
  	</div>
	<script>
		function modal(idx,mode){
			if(mode == 1){
				document.querySelector(".modal_container").classList.remove("hidden");
				document.getElementById("modal_idx").value=idx;
			}else{
				location.href='./read.php?idx='+ idx;
			}

			/* $.post("http://192.168.0.52/hmp/mpr_notice/notice/ajax.sec.php", {"code":idx}, function(data){
				alert( data.code );

				if ( $.trim(data.state)=='OK' ) {
					//alert('비밀글입니다. 비밀번호를 입력하세요.');
					alert($.trim(data.msg));
				} else {

				}
			}, "json"); */

		}
	</script>
	<div id="modal" class="modal_container hidden">	
		<div class="modal-window">
			<div class="modal_title">
				<h2>비밀글입니다.</h2>
			</div>
			<div class="close-area"><button id="close" class="close_btn">X</button></div>
			<div class="text"><p>비밀번호를 입력하세요</p></div>
			<div class="input"><input type="password" id="modal_pw" name="modal_pw"/> <input type="hidden" id="modal_idx" name="modal_idx"/><button onclick="modal_pw(document.getElementById('modal_idx').value, document.getElementById('modal_pw').value);">확인</button></div>
		</div>
	</div>
	<script>
		function modal_pw(idx, pwd){
			$.post("http://192.168.0.52/hmp/mpr_notice/notice/ajax.sec.php", {"idx":idx, "pwd": pwd}, function(data){
				if(data.state == "OK"){
					location.href='./read.php?idx='+idx;
				}else{
					alert("비밀번호가 일치하지 않습니다.");
					document.getElementById("modal_pw").value="";
					document.querySelector('.modal_container').classList.add("hidden");
				}
			}, "json");
		}
		const close= document.getElementById('close');

		close.addEventListener('click',()=>{
			document.querySelector('.modal_container').classList.add("hidden");
		});
	</script>
	
</body>
</html>