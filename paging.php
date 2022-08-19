<div class="page_num">
	<ul>
        <?php
            for($i=$block_start; $i<=$block_end; $i++){ 
                if($page == $i){  
                    echo "<li class='now_page'>$i</li>";
                }else{
                    echo "<li><a href='?{$strQueryString}&page={$i}'>$i</a></li>";
                }
            }
            if($block_num > $total_block){
                $prev = $page - 1;
                echo "<li><a href='?page=$prev'>이전</a></li>"; 
            } else if($block_num < $total_block){
                $next = $page + 1;
                echo "<li><a href='?page=$next'>다음</a></li>";
            }
        ?>
    </ul>
</div>