<?php
$walks = new walk;
$walks = $walks->retrieveData();
$count = $walks[0];
$count1 = intval($count);
$count1 = $count1;
$r=1;
while ( $r <= $count1 ){
     ${'walk' . $r} = new walk;
     ${'walk' . $r} = ${'walk' . $r}->spitObjects($walks,$r);
     $t = ${'walk' . $r}->spitId();
     ${'walk' . $t} = ${'walk' . $r}->spitObjects($walks,$r);
     $r++;
}
?>