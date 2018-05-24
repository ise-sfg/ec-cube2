<?php
header('Content-type: image/png');
if (false) {
	// 認証に成功した場合はそのまま画像を表示
	readfile("upload/secret_image/".basename($_SERVER['REQUEST_URI']));
} else {
	// 認証に失敗した場合はエラー画像を表示
	readfile('user_data/notdisplay.png');
}
