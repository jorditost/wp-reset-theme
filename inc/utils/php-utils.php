<?php 

/**
 * PHP Utils
 *
 * @copyright  Copyright © 2011-2012 Jordi Tost
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    1.0
 *
 * @Developer Jordi Tost (Follow Me: @jorditost)
 *
 *
 */


/////////////////
// ARRAY Utils
/////////////////

function array_insert(&$array,$element,$position=null) {
    if (count($array) == 0) {
        $array[] = $element;
    }
    elseif (is_numeric($position) && $position < 0) {
        if((count($array)+position) < 0) {
            $array = array_insert($array,$element,0);
        }
        else {
            $array[count($array)+$position] = $element;
        }
    }
    elseif (is_numeric($position) && isset($array[$position])) {
        $part1 = array_slice($array,0,$position,true);
        $part2 = array_slice($array,$position,null,true);
        $array = array_merge($part1,array($position=>$element),$part2);
        foreach($array as $key=>$item) {
            if (is_null($item)) {
                unset($array[$key]);
            }
        }
    }
    elseif (is_null($position)) {
        $array[] = $element;
    }  
    elseif (!isset($array[$position])) {
        $array[$position] = $element;
    }
    
    $array = array_merge($array);
    return $array;
}
?>