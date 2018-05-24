<html>
 <head>
  <title>PHP Test</title>
 </head>
 <body>
 <?php
    use Eccube\Common\Uploader;
    require __DIR__.'/../autoload.php';

    echo '<p>Hello World</p>';
    $upd = new Uploader;
    $upd->upload();
    echo 'AAAAAAAAAA';
?>
 </body>
</html>
