<?php
namespace Eccube\Common;
use \Aws\S3\S3Client;
// use \Aws\Exception;
class Download {
    public function download($filename) {
        require '/app/vendor/autoload.php';
        $accesskey = getenv('BUCKETEER_AWS_ACCESS_KEY_ID');
        $secretkey = getenv('BUCKETEER_AWS_SECRET_ACCESS_KEY');
        $region = getenv('BUCKETEER_AWS_REGION');
        $s3client = new \Aws\S3\S3Client([
            'credentials' => [
                'key' => $accesskey,
                'secret' => $secretkey
            ],
            'region' => $region,
            'version' => 'latest'
        ]);
        $bucket=getenv('BUCKETEER_BUCKET_NAME');
        // $keyname='public/'.$filename;
        $keyname=$filename;
        try {
            $result = $s3client->getObject(array(
                'Bucket' => $bucket,
                'Key' => $keyname
            ));
            $h=1;
            return $result;
            log_info($result . '');
            log_info("ダウンロード成功");
        } catch (\Exception $exc) {
            $h=2;
            log_info("ダウンロード失敗");
            log_info($exc->getMessage());
        }

    }
}