<div id='slide-panel_r'>
	<div class='slide'>Rekister�idy</div>
	<div class='sulje' id='pointer'><img src='/kuvat/sulje.gif' alt='close' /></div>
	<?php
		if(!isset($_POST['rekisterointi'])){
			echo "<div id='rekisterointi1'>
				<form action='".$_SERVER['PHP_SELF']."' method='post'>
					<table width='500'>
						<tr>
							<td>
								<hr />
							</td>
						</tr>
						<tr>
							<td>
								K�yt�t asiallista teksti� kirjoittaessasi vieraskirjaan, et loukkaa muita tai k�yt� muuta ep�asiallista teksti�. Yll�pito ker�� kaikkien ip-osoitteen talteen ja se voidaan est�� kokonaan toistuvien rikkomusten takia.
							</td>
						</tr>
						<tr>
							<td>
								<hr />
							</td>
						</tr>
						<tr>
							<td>
								Hyv�ksym�ll� n�m� ehdot sitoudut noudattamaan niit�. Jos et hyv�ksy n�it� ehtoja, �l� rekister�idy.
								<hr />
							</td>
						</tr>
						<tr>
							<td>
								<input type='hidden' name='rekisterointi' />
								<input type='checkbox' name='hyvaksy_r' value='ok' id='saannot_hyvaksy' />
								<label for='saannot_hyvaksy'>
									Olen lukenut s��nn�t ja hyv�ksyn ne.
								</label>
							</td>
						</tr>
						<tr>
							<td>
								<input type='submit' value='Seuraava' />
							</td>
						</tr>
					</table>
				</form>
			</div>";
		}
		elseif($_POST['hyvaksy_r'] == 'ok' || !empty($rekisteroierror)){
			echo "<div id='rekisterointi2'>
				<form action='".$_SERVER['PHP_SELF']."' method='post'>
					<table width='500'>
						<input type='hidden' name='lahetetty' value='34' />
						<input type='hidden' name='rekisterointi' />
						<input type='hidden' name='hyvaksy_r' value='ok' />
						<tr>
							<td>
								".(!empty($rekisteroierror)?"<div style='color:#FF0000'>".$rekisteroierror."</div>":"")."	
								<hr />
							</td>
						</tr>
						<tr>
							<td>
								Nimimerkki(pituus 4-20)<br />
								<input type='text' name='nimimerkki' maxlenght='20' value='".$_POST['nimimerkki']."' />
							</td>
						</tr>
						<tr>
							<td>
								<fieldset>
									<legend>Salasana</legend>
									<table>
										<tr>
											<td colspan='2'>
												Muistathan tehd� salasanastasi riitt�v�n vahvan. Vahvuuden voit tarkistaa <a href='http://www.microsoft.com/finland/protect/yourself/password/checker.mspx' target='_blank' title='Microsoftin salasana varmistin'>t��lt�</a>. Pituus 6-20.<hr />
											</td>
										</tr>
										<tr>
											<td>
												Salasana<br />
												<input type='password' name='salasana' maxlenght='20'>
											</td>
											<td>
												Vahvista salasana<br />
												<input type='password' name='vsalasana' maxlenght='20'>
											</td>
										</tr>
									</table>
								</fieldset>
							</td>
						</tr>
						<tr>
							<td>
								<fieldset>
									<legend>S�hk�posti</legend>
									<table>
										<tr>
											<td colspan='2'>
												Saat S�hk�postiisi aktivointi viestin, joten annathan toimivan s�hk�postiosoitteen. Jos tunnusta ei aktivoida 7 p�iv�n kuluessa rekister�innist� se poistetaan.<hr />
											</td>
										</tr>
										<tr>
											<td>
												S�hk�posti<br />
												<input type='text' name='sahkoposti' maxlenght='50' value='".$_POST['sahkoposti']."' />
											</td>
											<td>
												Vahvista sahk�posti<br />
												<input type='text' name='vsahkoposti' maxlenght='50' value='".$_POST['vsahkoposti']."' />
											</td>
										</tr>
									</table>
								</fieldset>
							</td>
						</tr>
						<tr>
							<td>
								<fieldset>
									<legend>Nimi</legend>
									<table>
										<tr>
											<td colspan='2'>
												Annathan oman nimesi. Nime� ei luovututeta kolmannelle osapuolelle.<hr />
											</td>
										</tr>
										<tr>
											<td>
												Etunimi<br />
												<input type='text' name='etunimi' maxlenght='50' value='".$_POST['etunimi']."'>
											</td>
											<td>
												Sukunimi<br />
												<input type='text' name='sukunimi' maxlenght='50' value='".$_POST['sukunimi']."'>
											</td>
										</tr>
									</table>
								</fieldset>
							</td>
							</tr>
							<tr>
								<td>
									<hr />
								</td>
							</tr>
							<tr>
								<td>
									<input type='reset' value='Tyhjenn�' /><input type='submit' value='Rekister�idy' /></center>
								</td>
							</tr>
						</table>
					</form>
				</div>";
		}
		else{
			echo "<div id='rekisterointi1'>
				<table width='500'>
					<tr>
						<td>
							<span style='text-align:center'>Rekister�inti</span>
							<hr />
						</td>
					</tr>
					<tr>
						<td>Et hyv�ksynyt s��nt�j�. Mene takaisin <a href='index.php'>etusivulle</a>.
							<hr />
						</td>
					</tr>
				</table>
			</div>";
		}
	?>
</div>