<?php

    function check_start() {
        $start_index = 0;

        if(isset($_GET['start'])) {
            if(is_numeric($_GET['start'])) {

              $start_index = intval($_GET[start]);
            }
        }
        return $start_index;
    }
    
    function generate_page_navi($file, $items_per_page) {
        global $prev_index, $pagecount, $curpage, $next_index;
        
        echo "<a href=\"" . $file . "?start=" . $prev_index . "\"><<</a> |";
        
        for($i = 1; $i <= $pagecount; $i++) {
            if($i == $curpage + 1) {
                echo " {$i} |";
            } else {
                $idx = ($i - 1) * $items_per_page;
                echo " <a href='" . $file . "?start={$idx}'>{$i}</a> |";
            }
        }
        echo "<a href=\"" . $file . "?start=" . $next_index . "\">>></a>";
    }
    
    function update_page_indices($table, $items_per_page) {
        global $start_index, $prev_index, $next_index, $pagecount, $id, $curpage;
        
        $result = pg_query("SELECT COUNT(*) FROM {$table} WHERE user_id={$id}") 
            or die($table . ' count query failed: ' . pg_last_error());
        $row = pg_fetch_row($result);
        $rowcount = intval($row[0]);
        if($start_index >= $rowcount) {
            $start_index = floor(($rowcount - 1) / $items_per_page) * $items_per_page;
        }
        
        if($start_index < 0) {
            $start_index = 0;
        }

        $prev_index = $start_index - $items_per_page;
        if($prev_index < 0) $prev_index = 0;

        $next_index = $start_index + $items_per_page;
        if($next_index >= $rowcount) {
            $next_index = $start_index;
        }

        $pagecount = ceil($rowcount / $items_per_page);
        if($pagecount <= 0) $pagecount = 1;
        $curpage = floor($start_index / $items_per_page);
    }
    
?>
