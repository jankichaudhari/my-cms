<?php

$postdata = urlencode(serialize($postdata));


header("Location:index.php?process=advanced_search_page&param2=$listing_id&param3=$postdata");

?>