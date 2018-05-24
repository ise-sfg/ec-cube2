 <?php
// mb_output_handler("pass");
$file='/app/pdf/aaa.pdf';
header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=download.pdf");
ob_end_clean();
readfile($file);
exit();
