<?php
include 'session.php';
$conn = mysqli_connect("localhost","hmp","mpr1234!","hmp");

//---- $_GET
//---- $_POST
//---- $_REQUEST
if ( trim($_GET['mode'])=='logout' ) {
    $_SESSION['userid'] = ''; // isset사용할거면 null로 지정
    $_SESSION['userpwd'] = '';
    setcookie('id', '', time()-999999);
    setcookie("login_time",'', time()-999999);
    setcookie("token",'', time()-999999);
    
    // session_destroy();

    echo '<script>alert("로그아웃 되었습니다.");</script>';
    echo "<script>location.href='./index.php'</script>";
}
//탈퇴
if ( trim($_GET['mode'])=='leave' ) {
  mysqli_query($conn,"DELETE FROM member WHERE userid='{$_SESSION['userid']}'");
  mysqli_query($conn,"DELETE FROM notice WHERE author='{$_SESSION['userid']}'");
  $auto_increment_reset= mysqli_query($conn,"ALTER table notice idx=1");
  mysqli_query($conn,"UPDATE notice SET author='unknown' WHERE author='{$_SESSION['userid']}'");
  echo '<script>alert("탈퇴되었습니다.");</script>';
  $_SESSION['userid'] = ''; // isset사용할거면 null로 지정
  $_SESSION['userpwd'] = '';
  echo "<script>location.href='./index.php'</script>";
}

$strSearch = trim($_GET['search']);
$keyword = trim($_GET['input_search']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="notice.css?after">
    <title>Document</title>
</head>
<body>
  <form method="POST">
    <div class="userinfo">
      <div class="logout_btn"><a href="./notice.php?mode=logout">로그아웃</a></div>
      <div class="leave_btn"><a href="./notice.php?mode=leave">탈퇴하기</a></div>
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
      </div>
    </form>
    <div class="write_btn"><a href="./write.php"><button>글쓰기</button></a></div>
    <div class="back_btn hidden"><a href="./notice.php"><button>목록으로</button></a></div>
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
          }else{
            $list = 10; //한 페이지에 보여줄 개수
            $block_ct = 5; //블록당 보여줄 페이지 개수
                    
            $block_num = ceil($page/$block_ct); // 현재 페이지 블록 구하기
            $block_start = (($block_num - 1) * $block_ct) + 1; // 블록의 시작번호
            $block_end = $block_start + $block_ct - 1; //블록 마지막 번호
            
            // 페이징한 페이지 수 구하기
            $total_page = ceil($row_num / $list); 
            if($block_end > $total_page) {
              $block_end = $total_page; //만약 블록의 마지박 번호가 페이지수보다 많다면 마지박번호는 페이지 수
            }
            $total_block = ceil($total_page/$block_ct); //블럭 총 개수
            $start_num = ($page-1) * $list; //시작번호 (page-1)에서 $list를 곱한다.

            $first_num = $row_num-$list*($page-1);
          
            $strSQL.= " ORDER BY idx DESC LIMIT {$start_num}, {$list} ";
            $res2 = mysqli_query($conn,$strSQL);
            while($notice = $res2->fetch_array()){
              $title=$notice["title"]; 
              if(strlen($title)>30)
                { 
                  $title=str_replace($notice["title"],mb_substr($notice["title"],0,30,"utf-8")."...",$notice["title"]);
                }
        ?>
            
            <tr>
              <td><?php echo $first_num; ?></td>
              <td><a href="./read.php?idx=<?php echo $notice['idx']?>"><?php echo $title;?></a></td>
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
    <div id="page_num">
      <ul>
        <?php
          for($i=$block_start; $i<=$block_end; $i++){ 
            if($page == $i){  
              echo "<li class='now_page'>$i</li>";
            }else{
              echo "<li><a href='?page={$i}&{$strQueryString}'>$i</a></li>";
            }
          }
          if($block_num > $total_block){
            $prev = $page - 1;
            echo "<li><a href='?page=$prev'>이전</a></li>"; 
          } else if($block_num < $total_block){
            $next = $page + 1;
            echo "<li><a href='?page=$next'>다음</a></li>";
          }
        }
        ?>
      </ul>
    </div>
  </div> 
</body>
</html>