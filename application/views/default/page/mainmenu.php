<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<ul>
    <? echo getChildMenu($list) ?>
</ul>

<?

function getChildMenu($list)
{
    $m = array();
    while($item = array_shift($list))
    {
        $m[] = '<li><a href="'.  URL::site( $item['link'] ).'">'.$item['title'].'</a></li>';
        if(count($item['childs']) > 0 )
        {
            $m[] = '<ul>'. getChildMenu($item['childs']) .'</ul>';
        }
    }
    return implode('', $m);
}

?>