<?php
use Eccube\Common\Download;
require __DIR__.'/../autoload.php';

header('Content-type: image/png');
if (false) {
	// 認証に成功した場合はそのまま画像を表示
	readfile("upload/secret_image/".basename($_SERVER['REQUEST_URI']));
} else {
	// 認証に失敗した場合はエラー画像を表示
//	readfile('user_data/notdisplay.png');
    $dl = new Download();
    $result = $dl->download('secret.png');
    //ファイルサイズ
    $length = $result['ContentLength'];
    //ファイルポインタを先頭に戻し、ファイルを読み込む
    $result['Body']->rewind();
    $data = $result['Body']->read($length);
    //キーからファイル名だけ取り出す
    $filename = end(explode('/', $key));
    echo $data;
}
