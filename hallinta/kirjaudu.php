<div id='slide-panel_k'>
	<div class='slide'>Kirjaudu</div>
	<div class='sulje' id='pointer'><img src='/kuvat/sulje.gif' alt='sulje' /></div>
	<div id='kirjaudu_paneeli'>
	<?php
		echo"<form action='".$_SERVER['PHP_SELF'];
                if(!empty($_GET)){
                    echo"?";
                    tulostaGET(null);
                }
		echo"' method='post'>";
	?>
		<table width='500px'><input type='hidden' name='lahetetty' value='32' />
			<tr>
				<td colspan='2' align='center'>
					<?php echo (!empty($kirjauduerror)?"<div style='color:#FF0000'>".$kirjauduerror."</div>":""); ?>
				</td>
			</tr>
			<tr>
				<td align='right' width='165px'>Käyttäjätunnus:</td>
				<td align='left' width='170px'>
					<?php echo"<input type='text' name='username' value='".$_POST['nimimerkki']."' />"; ?>
				</td>
				<td width='165px'>
				</td>
			</tr>
			<tr>
				<td align='right' width='165px'>Salasana:</td>
				<td align='left' width='170px'>
					<input type='password' name='salasana' />
				</td>
				<td width='165px'>
				</td>
			</tr>
			<tr>
				<td colspan='2' align='center' >
					<input type='submit' value='Kirjaudu sisään' />
				</td>
			</tr>
			<tr>
				<td colspan='2' align='center'>
					<a href='/unohtunutsalasana.php'>Unohditko salasanasi?</a>
				</td>
			</tr>
		</table>
	</form>
	</div>
</div>