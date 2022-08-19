<script>
    var querySelectAll = document.querySelectorAll(".read");
    for(var i=0; i< querySelectAll.length; i++){
        querySelectAll[i].classList.add("hidden");
    }
    document.querySelector(".div_update_btn").classList.add("hidden");
    document.querySelector(".<?php echo $_GET['mode']?>").classList.add("cur_page");        
    document.querySelector(".cur_page").classList.remove("cur_page");                
</script>

    <div class="mypost">
        <table>
            <tr class="table_header">
                <th class="title">제목</th>
                <th>작성자</th>
                <th>작성일</th>
                <th>조회수</th>
            </tr> 
            <?php
            if(isset($_GET['page'])){
                $page = $_GET['page'];
            } else {
                $page = 1;
            }
                //$strQueryString.= "&page={$page}";

                //$strWhere = " `{$selected}`='{$keyword}' ";
            $strSQL = " SELECT * FROM notice where author='{$_SESSION['userid']}'";
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
                include '../cal_page.php';
                while($notice = $res2->fetch_array()){
                $title=$notice["title"]; 
                if(strlen($title)>30)
                    { 
                    $title=str_replace($notice["title"],mb_substr($notice["title"],0,30,"utf-8")."...",$notice["title"]);
                    }
            ?>
            <tr>
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
        </table>
    </div>
    <!---mypost_페이징 넘버 --->
    <?php
            }
        include '../paging.php';
    ?>