<?php
/*
Template : Rframe
*/

$rframe = get_post_meta( get_the_ID(), 'html_rframe', true );

echo html_entity_decode($rframe);
?>