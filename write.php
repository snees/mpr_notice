<?php
include 'session.php';?>
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
    <div class="header"><h1>게시글 작성</h1></div>
    <form method="POST">
        <table>
            <tr><th class="text">제목</th></tr>
            <tr><td><input type="text" class="write_title" name="title" placeholder="제목을 입력해주세요"></td></tr>
            <tr><th class="text">작성자</th></tr>
            <tr><td><input type="text" class="write_author" name="author" value="<?php echo trim($_SESSION['userid']);?>" readonly></td></tr>
            <tr><th class="text">내용</th></tr>
            <tr><td><textarea class="write_contents" name="contents" placeholder="내용을 입력해주세요"></textarea></td></tr>
        </table>
        <div class="div_button"><button class="btn"><a href="./notice.php">취소</a></button> <input type="submit" class="btn" name="saveBtn" value="저장"/><div>
    </form>
    <?php
    if(array_key_exists('saveBtn',$_POST)){
        $conn = mysqli_connect("localhost","hmp","mpr1234!","hmp");
        mysqli_query($conn, "set session character_set_connection=utf8;");
        mysqli_query($conn, "set session character_set_results=utf8;");
        mysqli_query($conn, "set session character_set_client=utf8;");
        if(!empty($_POST['title']) || !empty($_POST['author']) || !empty($_POST['contents'])){
            $sql_insert = "INSERT INTO notice (title, author, content, createdate) VALUES ('{$_POST['title']}','{$_POST['author']}','{$_POST['contents']}',now())";
            mysqli_query($conn, $sql_insert);
            echo "<script>alert('글쓰기가 완료되었습니다.');
                location.href='./notice.php'</script>";
        }else{
            echo "<script>console.log('빈칸을 입력하세요');</script>";
        }
    }
    ?>
</body>
</html>