<?php
parse_str($_SERVER["QUERY_STRING"],$myArray);
header('location:'.$myArray['q']);
exit;