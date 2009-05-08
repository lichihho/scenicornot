#!/usr/bin/php -q
<?php
/* 
 * generate-tsv:
 * Run from cron to generate a TSV file of average ratings.
 *
 * Copyright (c) 2009 UK Citizens Online Democracy. All rights reserved.
 * Email: matthew@mysociety.org. WWW: http://www.mysociety.org/
 *
 * $Id: generate-tsv.php,v 1.1 2009-05-08 11:12:52 matthew Exp $
 *
 */

include "../web/prepend.php";
include ROOT . "/include/global.php";

$mySQL->query("select place, lat, lon, avg(rating) from vote, place
    where place = place.id
        and uuid not in ('2a92cf77c3908f132b856af79f0c3267', 'fd0fd60993feaa3463c43581ee60eeba', 'd2947b36351a635e7381a17a5b22b0ba')
    group by place
    having count(rating) >= 3
    into outfile '/tmp/Votes20090508-1144'
");

$out = '';
while ($row = $mySQL->fetchArray()) {
    $out .= join("\t", $row) . "\n";
}


$fp = fopen(ROOT . '/votes.tsv.new');
fwrite($fp, $out);
fclose($fp);
rename(ROOT . '/votes.tsv.new', ROOT . '/votes.tsv');