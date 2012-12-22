<?php
//mysql asetukset
$username = "fbcremix_fbc";
$password = "irwQ/es29E";
$servername = "localhost";
$tietokanta = "fbcremix_testi";
$yhteys = mysql_connect($servername, $username, $password) 
  or die("Kantaan ei saatu yhteyttä: ".mysql_error());
mysql_select_db($tietokanta, $yhteys)
  or die("Kantaa ei saatu valittua: ".mysql_error());
?>