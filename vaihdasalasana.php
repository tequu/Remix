<?php include("/home/fbcremix/public_html/yla.php"); ?>
<div id="container">
    <div class='otsikko'>Salasanan vaihto</div><hr />
<?php
	echo"<form action='".$_SERVER['PHP_SELF']."' method='post'><input type='hidden' name='lahetetty' value='35' />
	<table id='vaihdasalasana_table'>
	<tr>
	<td>
	</td>
	</tr>
	<tr><td>
	<div id='error'>".$error."</div>
	</td></tr>
	<tr>
	<td colspan='2'>Nimimerkki: ".$_SESSION['nimimerkki']."
	</td></tr>
	<tr>
	<td>
	<fieldset>
	<legend>Uusi salasana</legend>
	<table>
	<tr>
	<td>
	Uusi salasana(6-20)<br />
	<input type='password' name='uusi_salasana' /></td>
	<td>Varmista uusi salasana<br />
	<input type='password' name='v_uusi_salasana' />
	</td>
	</tr>
	</table>
	</fieldset>
	</td>
	</tr>
	<tr>
	<td>
	Vanha salasana<br />
	<input type='password' name='vanha_salasana' />
	</td>
	</tr>
	<tr>
	<td><input type='submit' value='Vaihda'></td>
	</tr>
	</table>
	</form>";
?>	
</div>
<?php include("/home/fbcremix/public_html/ala.php"); ?>