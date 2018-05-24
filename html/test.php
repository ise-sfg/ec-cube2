<?php
putenv("DATABASE_URL=postgres://pnfaviwzxrvgyp:95b0e4b4ceb3fbefba6edc62db0aec799bb64ed7dc08f8b12b8d02e800e82edf@ec2-174-129-223-193.compute-1.amazonaws.com:5432/d3b20o6poacpi0");
$hoge = getenv("DATABASE_URL");


$url = parse_url(getenv("DATABASE_URL"));

echo $url["host"];
echo "<br/>";
echo substr($url["path"],1);
echo "<br/>";
echo $url["port"];
echo "<br/>";
echo $url["user"];
echo "<br/>";
echo $url["pass"];
echo "<br/>";
