<?php
require '/app/vendor/autoload.php';
use Aws\S3\S3Client;

$s3client = new Aws\S3\S3Client([
    'credentials' => [
        'key' => 'AKIAI7F42F6O3QOUACPA',
        'secret' => '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',
    ],
    'region' => 'us-east-1',
    'version' => 'latest',
]);


$tmpfile = $_FILES["upfile"]["tmp_name"];
if (!is_uploaded_file($tmpfile)) {
    die('ファイルがアップロードされていません');
}
//$key = "test3.jpg";
//// バケット名
$bucket = 'bucketeer-0714e1a3-7949-48f5-a1a4-8fb59aa37e3d';

//$result = $s3client->putObject([
//    'Bucket' => $bucket,
//    'Key' => $tmpfile,
//    'Body' => Guzzle\Http\EntityBody::factory(fopen($tmpfile, 'r')),
//]);



echo $tmpfile.'<br/>';
echo '-----------------------------------------------<br/>';
try {
    $result = $s3client->putObject(array(
        'Bucket' => $bucket,
        'Key' => 'public/AAAAAAAAAAAAAAAAAA.jpg',
        'Body' => Guzzle\Http\EntityBody::factory(fopen($tmpfile, 'r')),
    ));
    echo $result . '';
    echo "アップロード成功！";
} catch (S3Exception $exc) {
    echo "アップロード失敗";
    echo $exc->getMessage();
}

/*
 sssss


// アップロードファイル名

*/
?>
