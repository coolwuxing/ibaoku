<?php

function item_cats_compare($a, $b) {
    if (is_array($a) && is_array($b) && isset($a['cnt']) && isset($b['cnt'])) {
        if ($a['cnt'] == $b['cnt']) {
            return 0;
        } else {
            return $a['cnt'] < $b['cnt'] ? 1 : -1;
        }
    } else {
        return -1;
    }
}

function cats_child_compare($a, $b) {
    if (is_array($a) && is_array($b) && is_array($a['child']) && is_array($b['child'])) {
        $la = count($a['child']);$lb = count($b['child']);
        if ($la == $lb) {
            return 0;
        } else {
            return $la < $lb ? 1 : -1;
        }
    } else {
        return -1;
    }
}

function generate_ads_items($position, $num) {
    $mItem = M('item');
    $startpos = rand()%1000 + 1;
    $itemList = $mItem->where('id>'.$startpos)->limit('0,'.$num)->select();
    return $itemList;
}