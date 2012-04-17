<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//print_r( json_encode( $path ) );

?>
Навигация :<br/>
<ul>
<?
while($item = array_shift($path))
{
    ?>
    <li>
        <a href="<? echo URL::site($item->link()) ?>"><? echo $item->title(); ?></a>
    </li>
    <?
}
?>
</ul>