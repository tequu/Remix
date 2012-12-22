<div id='slide-panel_r'>
	<div class='slide'>Rekisteröidy</div>
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
								Käytät asiallista tekstiä kirjoittaessasi vieraskirjaan, et loukkaa muita tai käytä muuta epäasiallista tekstiä. Ylläpito kerää kaikkien ip-osoitteen talteen ja se voidaan estää kokonaan toistuvien rikkomusten takia.
							</td>
						</tr>
						<tr>
							<td>
								<hr />
							</td>
						</tr>
						<tr>
							<td>
								Hyväksymällä nämä ehdot sitoudut noudattamaan niitä. Jos et hyväksy näitä ehtoja, älä rekisteröidy.
								<hr />
							</td>
						</tr>
						<tr>
							<td>
								<input type='hidden' name='rekisterointi' />
								<input type='checkbox' name='hyvaksy_r' value='ok' id='saannot_hyvaksy' />
								<label for='saannot_hyvaksy'>
									Olen lukenut säännöt ja hyväksyn ne.
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
												Muistathan tehdä salasanastasi riittävän vahvan. Vahvuuden voit tarkistaa <a href='http://www.microsoft.com/finland/protect/yourself/password/checker.mspx' target='_blank' title='Microsoftin salasana varmistin'>täältä</a>. Pituus 6-20.<hr />
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
									<legend>Sähköposti</legend>
									<table>
										<tr>
											<td colspan='2'>
												Saat Sähköpostiisi aktivointi viestin, joten annathan toimivan sähköpostiosoitteen. Jos tunnusta ei aktivoida 7 päivän kuluessa rekisteröinnistä se poistetaan.<hr />
											</td>
										</tr>
										<tr>
											<td>
												Sähköposti<br />
												<input type='text' name='sahkoposti' maxlenght='50' value='".$_POST['sahkoposti']."' />
											</td>
											<td>
												Vahvista sahköposti<br />
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
												Annathan oman nimesi. Nimeä ei luovututeta kolmannelle osapuolelle.<hr />
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
									<input type='reset' value='Tyhjennä' /><input type='submit' value='Rekisteröidy' /></center>
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
							<span style='text-align:center'>Rekisteröinti</span>
							<hr />
						</td>
					</tr>
					<tr>
						<td>Et hyväksynyt sääntöjä. Mene takaisin <a href='index.php'>etusivulle</a>.
							<hr />
						</td>
					</tr>
				</table>
			</div>";
		}
	?>
</div>