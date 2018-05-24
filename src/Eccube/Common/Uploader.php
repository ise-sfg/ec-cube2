<?php
namespace Eccube\Common;
use \Aws\S3\S3Client;
class Uploader {
    public function upload($file) {
        require '/app/vendor/autoload.php';
        $s3client = new \Aws\S3\S3Client([
            'credentials' => [
                'key' => getenv('BUCKETEER_AWS_ACCESS_KEY_ID'),
                'secret' => getenv('BUCKETEER_AWS_SECRET_ACCESS_KEY'),
            ],
            'region' => getenv('BUCKETEER_AWS_REGION'),
            'version' => 'latest',
        ]);
        $hoge=$file->getfileName();
        log_info($hoge);
        $fuga=$file->getPathname();
        log_info($fuga);

        try {
            $result = $s3client->putObject(array(
                'Bucket' => getenv('BUCKETEER_BUCKET_NAME'),
                'Key' => 'public/'.$file->getfileName(),
                'Body' => \Guzzle\Http\EntityBody::factory(fopen($file->getPathname(), 'r')),
            ));
            log_info($result . '');
            log_info("アップロード成功");
        } catch (S3Exception $exc) {
            log_info("アップロード失敗");
            log_info($exc->getMessage());
        }

    }
}