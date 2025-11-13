<?php
date_default_timezone_set("Asia/Jakarta");
function tgl_ina($parameter){ //ini untuk mengubah format 2015-06-15 ke dalam format 15 Juni 2015
$thn=substr($parameter,0,4); //menngambil 4 digit dari kiri, 0 adalah index pertama dari tahun (angka 2 dari 2015), 4 banyaknya digit yg diambil
$b=substr($parameter,5,2); //mengambil 2 digit, index 5 adalah angka 0 dari 06
$tgl=substr($parameter,-2); //mengambil 2 digit dari kanan
if($b==1) {$bln="Januari";}
else if($b==2) {$bln="Februari";}
else if($b==3) {$bln="Maret";}
else if($b==4) {$bln="April";}
else if($b==5) {$bln="Mei";}
else if($b==6) {$bln="Juni";}
else if($b==7) {$bln="Juli";}
else if($b==8) {$bln="Agustus";}
else if($b==9) {$bln="September";}
else if($b==10){$bln="Oktober";}
else if($b==11){$bln="November";}
else if($b==12){$bln="Desember";}
$tanggal=$tgl . " ".$bln ." ".$thn;
return $tanggal;
}


// copy-paste-edit dari yg atas, kegunaaan dari function ini adalah untuk penyusunan laporan
function tgl_ina2($parameter){
$thn=substr($parameter,2,2);
$bln=substr($parameter,5,2);
$tgl=substr($parameter,-2);
$tanggal=$tgl . "-".$bln ."-".$thn ;
$waktu=$tanggal;
return $waktu;
}


//mengubah hari bahasa inggiris ke dalam bahasa indonesia
function hari_ina($day)
{
if ($day=="Monday")
{$hari="Senin";}
else if ($day=="Tuesday")
{$hari="Selasa";}
else if ($day=="Wednesday")
{$hari="Rabu";}
else if ($day=="Thursday")
{$hari="Kamis";}
else if ($day=="Friday")
{$hari="Jumat";}
else if ($day=="Saturday")
{$hari="Sabtu";}
else if ($day=="Sunday")
{$hari="Minggu";}
return $hari;
}

function bln_ina($bln_ing)
{
  if($bln_ing=="January") {$bln_ina="Januari";}
  else if($bln_ing=="February") {$bln_ina="Februari";}
  else if($bln_ing=="March") {$bln_ina="Maret";}
  else if($bln_ing=="April") {$bln_ina="April";}
  else if($bln_ing=="May") {$bln_ina="Mei";}
  else if($bln_ing=="June") {$bln_ina="Juni";}
  else if($bln_ing=="July") {$bln_ina="Juli";}
  else if($bln_ing=="August") {$bln_ina="Agustus";}
  else if($bln_ing=="September") {$bln_ina="September";}
  else if($bln_ing=="October"){$bln_ina="Oktober";}
  else if($bln_ing=="November"){$bln_ina="November";}
  else if($bln_ing=="December"){$bln_ina="Desember";}
  return $bln_ina;
}
?>
