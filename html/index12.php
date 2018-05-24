<?php
use Eccube\Common\Download;
require __DIR__.'/../autoload.php';
$key = "bbb.pdf";
$dl = new Download();
$result = $dl->download($key);
//ファイルサイズ
$length = $result['ContentLength'];
//ファイルポインタを先頭に戻し、ファイルを読み込む
$result['Body']->rewind();
$data = $result['Body']->read($length);
//キーからファイル名だけ取り出す
$filename = end(explode('/', $key));
//Content-Type
header('Content-Type: application/octet-stream');
//ファイル名
$disposition = 'Content-Disposition: attachment; filename="' . $filename . '"';
header($disposition);
//Content-Length
$contentlength = 'Content-Length: ' . $length;
header($contentlength);
echo $data;
