<?php

    include 'head.php';

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

?>