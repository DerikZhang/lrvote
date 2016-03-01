<?php 
include "./phpqrcode.php";
$data=$_GET['url'];
$errorCorrectionLevel="L";
$matrixPointSize="4";
QRcode::png($data,false,$errorCorrectionLevel,$matrixPointSize);