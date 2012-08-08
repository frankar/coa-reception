<?php include("header.php"); ?>
<script src="js/jquery.lightbox_me.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$("ul.help table a.sub").click(function(e) {
		var idel = '#pop_'+$(this).attr('href');
		//alert(this.parent().html());
		$(idel)
			.append('<a class="close" href="#"><img src="css/close_button.png" alt="Close Button" /></a>')
			.lightbox_me({
		        centered: true, 
		        });
		e.preventDefault();
	});
	
	
});
</script>
<?php include("menu.php"); ?>
	<h2>Guida all'utilizzo dell'applicativo</h2>
	
	
	<div class="pop" id="pop_qual">
		<h4>Aggiungi Qualifica</h4>
		<table width="100%">
            <tr>
                <td width="30%"><u>Sigla </u> </td>
                <td> Acronimo qualifica del CNVVF oppure dicitura per personale esterno.</td>
            </tr>
            <tr>
                <td><u>Nome </u></td>
                <td> Nome per esteso della sigla</td>
            </tr>
        </table>
	</div>
	
	<div class="pop" id="pop_mans">
		<h4>Aggiungi Mansione</h4>
		<table width="100%">
            <tr>
                <td width="30%"><u>Nome </u></td>
                <td> Nome mansione per il dipendente</td>
            </tr>
        </table>
		
	</div>

	<div class="pop" id="pop_com">
		<h4>Aggiungi Comando Provenienza</h4>
        <table width="100%">
            <tr>
                <td width="30%"><u>Sigla </u> </td>
                <td> Sigla della provincia o sede VF non provinciale (Direzione Centrale/Regionale).</td>
            </tr>
        </table>
	</div>

	<div class="pop" id="pop_tipomezzo">
		<h4>Aggiungi Tipo Mezzo</h4>
        <table width="100%">
            <tr>
                <td width="30%"><u>Nome </u></td>
                <td> Nome o acronimo per il mezzo.</td>
            </tr>
        </table>
	</div>

<?php
$message_class = 'ok';
$message = '';
if (isset($_REQUEST['p'])) {
	$par = $_REQUEST['p'];
	switch ($par) {
		case 'pers':
			?>
			<h3>Gestione Personale</h3>
                <ul class="help">
                    <li><strong>Accoglienza</strong>: scheda di inserimento nominativi del personale in servizio per 
                        il periodo con :
                        <table><tr>
							<td width="30%"><u>Qualifica </u> </td>
							<td>Elenco acronimi qualifiche del CNVVF 
                            (integrabile premendo a fianco l&rsquo;apposito pulsante <a class="sub" href="qual">"Aggiungi Qualifica"</a> se non presente 
                            nell&rsquo;elenco, gi&aacute; molto completo anche di qualifiche esterne e di personale dell&rsquo;ANVVF)			                
							</td>
						</tr>
                        <tr><td><u>Nome </u></td><td> Nome del dipendente</td></tr>
                        <tr><td><u>Cognome</u></td><td> Cognome del dipendente</td></tr>
                        <tr><td><u>Telefono </u></td><td> Numeri di telefono (cellulare) cui &eacute; possibile rintracciare il dipendente, 
                            anche pi&uacute; di uno</td></tr>
                        <tr><td><u>Posto tenda assegnato </u></td><td> Assegnazione del posto nella tenda del campo</td></tr>
                        <tr><td><u>Comando di provenienza </u> </td><td> Comando di appartenenza del dipendente 
                            (integrabile premendo a fianco l&rsquo;apposito pulsante <a class="sub" href="com">"Aggiungi Comando Provenienza"</a>
                            se non presente nell&rsquo;elenco)</td></tr>
                        <tr><td><u>Data di ingresso </u></td><td> Casella "calendario" cui selezionare data (e ora, eventualemente) 
                            dell&rsquo;arrivo del dipendente oppure premendo il pulsante a lato "Ora Attuale" verrano inserite in 
                            automatico quelle del momento in cui si sta compilando la scheda</td></tr>
                        <tr><td><u>Data di uscita presunta</u></td><td> Casella compilata in automatico con una turnazione settimanale, 
                            ma modificabile come per il precedente</td></tr>
                        <tr><td><u>Mansione</u></td><td> Mansione assegnata al dipendente (integrabile premendo a fianco l&rsquo;apposito 
                                pulsante <a class="sub" href="mans">"Aggiungi Mansione"</a> se non presente nell&rsquo;elenco)</td>
                        </tr>
                        </table>  
                        <p>
                        Tabella <strong>"Ultime operazioni"</strong>: elenco delle ultime modifiche fatte sul personale presente, con la possibilit&aacute; di farne altre premendo 
                        su "Modifica" che rimanda alla scheda del dipendente con i relativi dettagli. Per modificarlo bisogna premere "Abilita modifiche", 
                        e questo permette di aggiornare i dati, infine premere su "Salva ->" che rimanda su una pagina di avviso dell&rsquo;avvenuta modifica se andata 
                        a buon fine. 
                        Andare nuovamente sul menu "Gestione personale" e quindi su "Accoglienza" per continuare le operazioni.
                        </p>
                    <li><strong>Rientri</strong>: scheda del rientro del personale alla sede di provenienza con :
                        <table><tr><td width="30%"><u>Nome </u> </td><td> inserendo le prime lettere del nominativo viene fatta una ricerca tra il personale
                            ancora in sede, dal quale si selezione il dipendente
                            </td></tr>
                        <tr><td><u>Data e ora rientro </u></td><td> Casella "calendario" cui selezionare data (e ora, eventualemente) 
                            della partenza del dipendente dalla sede oppure premendo il pulsante a lato "Ora Attuale" verrano inserite in 
                            automatico quelle del momento in cui si sta compilando la scheda</td></tr>
                        </tr>
                        </table>  
                        <p>
                        Tabella <strong>"Ultime operazioni"</strong>: elenco degli ultimi rientri inseriti per il personale presente, con la possibilit&aacute; di annullarli premendo 
                        su "Annulla".
                        </p>
                    <li><strong>Riassunto presenti</strong>:    
                        <table>
                            <tr><td width="30%">Esportazione anagrafica presenti per Excel</td><td> 
                                    <ul><li>"Genera dati CSV" per esportare in un file con campi delimitati da virgola</li>
                                        <li>
                                            "Genera dati TSV" per esportare i dati in un file can campi separati da 'tabulazione'</li></ul> </td></tr>
                            <tr><td>Tabella anagrafica PRESENTI</td><td>Elenco del personale presente nel turno in sede che pu&oacute; essere ordinato premendo
                                sopra ogni colonna con ordini ascendente/discendente e ricercato compilando la casella "Cerca nella tabella"</td></tr>
                        </table>
                    </li>    
                    <li><strong>Riassunto non presenti</strong>:    
                   <table>
                            <tr><td width="30%">Esportazione dati del personale non pi&uacute; presente per Excel</td><td> 
                                    <ul><li>"Genera dati CSV" per esportare in un file con campi delimitati da virgola</li>
                                        <li>
                                            "Genera dati TSV" per esportare i dati in un file can campi separati da 'tabulazione'</li></ul> </td></tr>
                            <tr><td>Tabella anagrafica personale non pi&uacute; presente</td><td>Elenco del personale non pi&uacute; presente 
                                    nel turno in sede, quindi che ha concluso il periodo,
                                    che pu&aacute; essere ordinato premendo
                                sopra ogni colonna con ordini ascendente/discendente e ricercato compilando la casella "Cerca nella tabella"</td></tr>
                        </table>             
                    </li>
                </ul>
			<?php
			break;
		case 'mezzi':
			?>
			<h3>Gestione Automezzi</h3>
                <ul class="help">
                    <li><strong>Ingresso Mezzi</strong>: scheda di inserimento nominativi del personale in servizio per 
                        il periodo con :
                        <table>
                            <tr><td width="30%"><u>Tipo Automezzo </u> </td><td>Elenco acronimi mezzi del CNVVF 
                            (integrabile premendo a fianco l&rsquo;apposito pulsante <a class="sub" href="tipomezzo">"Aggiungi tipo Mezzo"</a> se non presente 
                            nell&rsquo;elenco)</td></tr>
                            <tr><td><u>Targa</u></td><td> Targa del mezzo, senza "VF" (Es. 21000)</td></tr>
                            <tr><td><u>Comando di provenienza </u> </td><td> Comando di appartenenza del dipendente 
                            (integrabile premendo a fianco l&rsquo;apposito pulsante <a class="sub" href="com">"Aggiungi Comando Provenienza"</a>
                            se non presente nell&rsquo;elenco)</td></tr>
                            <tr><td><u>Responsabile </u> </td><td> inserendo le prime lettere del nominativo viene fatta una ricerca tra il personale
                            ancora in sede, dal quale si selezione il dipendente
                            </td></tr>
                            <tr><td><u>Data di ingresso </u></td><td> Casella "calendario" cui selezionare data (e ora, eventualemente) 
                            dell&rsquo;arrivo del dipendente oppure premendo il pulsante a lato "Ora Attuale" verrano inserite in 
                            automatico quelle del momento in cui si sta compilando la scheda</td></tr>
                        </table>  
                        <p>
                        Tabella <strong>"Ultime operazioni"</strong>: elenco delle ultime modifiche di ingresso mezzi, con la possibilit&aacute; di farne altre premendo 
                        su "Modifica" che rimanda alla scheda dettagliata. Per modificarlo bisogna premere "Abilita modifiche", 
                        e questo permette di aggiornare i dati, infine premere su "Salva ->" che rimanda su una pagina di avviso dell&rsquo;avvenuta modifica se andata 
                        a buon fine. 
                        Andare nuovamente sul menu "Gestione automezzi" e quindi su "Ingresso Mezzi" per continuare le operazioni.
                        </p>
                    <li><strong>Uscita Mezzi</strong>: scheda del rientro del mezzo alla sede di provenienza.
                        <table>
                            <tr><td width="30%"><u>Targa</u></td><td> Targa del mezzo, senza "VF" (Es. 21000)</td></tr>
                            <tr><td><u>Data e ora di rientro </u></td><td> Casella "calendario" cui selezionare data (e ora, eventualemente) 
                            della partenza del mezzo, oppure premendo il pulsante a lato "Ora Attuale" verrano inserite in 
                            automatico quelle del momento in cui si sta compilando la scheda)</td></tr>
                        </table>  
                        <p>
                        <p>
                        Tabella <strong>"Ultime operazioni"</strong>: elenco degli ultimi rientri inseriti per i mezzi presenti, con la possibilit&aacute; di annullarli premendo 
                        su "Annulla".
                        </p>
                        </p>
                    <li><strong>Cambia Responsabile Mezzo</strong>    
                        <table>
                            <tr><td width="30%"><u>Targa</u></td><td> Targa del mezzo, senza "VF" (Es. 21000)</td></tr>                            
                            <tr><td><u>Vecchio Responsabile </u> </td><td> inserendo le prime lettere del nominativo viene fatta una ricerca tra il personale
                            ancora in sede, dal quale si selezione il dipendente
                            </td></tr>
                            <tr><td><u>Nuovo Responsabile </u> </td><td> inserendo le prime lettere del nominativo viene fatta una ricerca tra il personale
                            ancora in sede, dal quale si selezione il dipendente, cui assegnare il mezzo
                            </td></tr>
                        </table>
                        <p>
                        Tabella <strong>"Ultime operazioni"</strong>: elenco delle ultime modifiche di ingresso mezzi, con la possibilit&aacute; di farne altre premendo 
                        su "Modifica" che rimanda alla scheda dettagliata. Per modificarlo bisogna premere "Abilita modifiche", 
                        e questo permette di aggiornare i dati, infine premere su "Salva ->" che rimanda su una pagina di avviso dell&rsquo;avvenuta modifica se andata 
                        a buon fine. 
                        Andare nuovamente sul menu "Gestione automezzi" e quindi su "Cambia Responsabile Mezzi" per continuare le operazioni.
                        </p>
                    </li>    
                    <li><strong>Riassunto mezzi presenti</strong>:    
                        <table>
                            <tr><td width="30%">Esportazione dati del personale non pi&uacute; presente per Excel</td><td> 
                                    <ul><li>Premendo "Genera dati CSV" viene creato un campo testuale da cui copiare (per esportare in altra applicazione) i dati della 
                                            tabella sottostante con le colonne  delimitate da virgola</li>
                                        <li>
                                            "Genera dati TSV" viene creato un campo testuale da cui copiare (per esportare in altra applicazione) i dati della 
                                            tabella sottostante con le colonne  delimitate da 'tabulazione'</li></ul> </td></tr>

                            <tr><td>Tabella anagrafica Mezzi</td><td>Elenco dei mezzi presenti
                                    nel turno in sede, che possono essere ordinati premendo
                                    sopra ogni colonna in modo ascendente/discendente e ricercati compilando la casella "Cerca nella tabella"</td></tr>
                        </table>             
                    </li>
                    <li><strong>Riassunto mezzi usciti</strong>:    
                        <table>
                            <tr><td width="30%">Esportazione dati del personale non pi&uacute; presente per Excel</td><td> 
                                    <ul><li>Premendo "Genera dati CSV" viene creato un campo testuale da cui copiare (per esportare in altra applicazione) i dati della 
                                            tabella sottostante con le colonne  delimitate da virgola</li>
                                        <li>
                                            "Genera dati TSV" viene creato un campo testuale da cui copiare (per esportare in altra applicazione) i dati della 
                                            tabella sottostante con le colonne  delimitate da 'tabulazione'</li></ul> </td></tr>
                            <tr><td>Tabella anagrafica Mezzi Usciti dal COA</td><td>Elenco dei mezzi non pi&uacute; presenti
                                    nel turno in sede, tornati alle sedi di provenienza, che possono essere ordinati premendo
                                    sopra ogni colonna in modo ascendente/discendente e ricercati compilando la casella "Cerca nella tabella"</td></tr>
                        </table>             
                    </li>
                </ul>
			<?php
			break;
		case 'opz':
			?>
			<h3>Opzioni</h3>
                <ul class="help">
                    <li><strong>Parametri</strong>: scheda di inserimento nominativi del personale in servizio per 
                        il periodo con :
                        <table>
                            <tr><td width="30%"><u>Nome C.O.A.</u> </td>
                                    <td>Inserire il nome del Centro Operativo Avanzato, visualizzato in testata</td></tr>
                            <tr><td width="30%"><u>Messaggi Audio</u> </td>
                                    <td>Impostare 1 per abilitare i messaggi audio, 0 per non attivarli</td></tr>
                            <tr><td width="30%"><u>Media giorni permanenza</u> </td>
                                    <td>Indicare il numero medio di giorni di permanenza (numero intero, es: 7)</td></tr>
                            <tr><td width="30%"><u>Ultima ricerca versione</u> </td>
                                    <td>Data ultimo controllo di nuova versione disponibile (normalmente non serve modificare questo valore)</td></tr>
                            <tr><td width="30%"><u>Ultimo backup:</u> </td><td>
                                    Data ultimo backup effettuato (normalmente non serve modificare questo valore)</td></tr>
                            <tr><td width="30%"><u>Percorso di backup:</u> </td><td>
                                Specificare il percorso dove effettuare il backup automatico dei dati 
                                (es. "serverbackupcoa" opp. "d:\backup"), 
                                lasciare il campo vuoto per non effettuare il backup giornaliero automatico.</td></tr>
                            <tr><td width="30%"><u>Frequenza Backup Automatico Dati:</u> </td><td>
                                Specificare ogni quanto tempo effettuare il backup automatico. (es: "30 min", "1 hour", "3 day", 
                                "1 week"). Specificare 0 per disabilitare il backup automatico</td></tr>
                        </table>
                    </li>
                    <li><strong>Backup dati</strong>: file testuale SQL per esportare la banca dati, prendendo CTRL+S.

                    </li>
                </ul>
			<?php
			break;
		default:
			$message = "Sezione help non trovata";
			$message_class = 'ko';
			break;
	}
	if ($message <> '') {
		$soundfile = ($message_class == "ko") ? "error" : "ok";
		?><div id="message" class="<?php echo $message_class ?>"><?php echo $message ?>
			<?php if ($options['audio']) { ?>
			<audio autoplay="autoplay">
			  <source src="<?php echo dirname($_SERVER['PHP_SELF']).'/files/'.$soundfile.'.wav' ?>" type="audio/wav" />
			  <source src="<?php echo dirname($_SERVER['PHP_SELF']).'/files/'.$soundfile.'.ogg' ?>" type="audio/ogg" />
			  <source src="<?php echo dirname($_SERVER['PHP_SELF']).'/files/'.$soundfile.'.mp3' ?>" type="audio/mpeg" />
			Your browser does not support this audio
			</audio>
			<?php } ?>
		</div>
		<?php
	} 
	?>
		<p><a href="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>">Torna alla pagina principale della guida</a></p>
		
	<?php
} else {
?>
	<h3>Progetto di Assistenza al Centro Operativo Avanzato (C.O.A.)</h3>
	Menu dell'applicazione e relative funzioni :
	<ul>
	    <li><strong>Home</strong>: pagina iniziale dell&rsquo;applicazione.               
	    </li><br>
	    <li>
	        <strong><a href="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>?p=pers">Gestione Personale</a></strong>: funzioni di gestione di arrivi e rientri del personale presso la sede del C.O.A.
	    </li>
	        <br>
	    <li>
	        <strong><a href="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>?p=mezzi">Gestione Automezzi</a></strong> : funzioni di gestione di arrivi e rientri dei mezzi presso la sede del C.O.A.
	    </li>
	    <br>
	    <li><strong><a href="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>?p=opz">Opzioni</a></strong>: funzioni di configurazione dei parametri dell&rsquo;applicazione e backup della banca dati.
	    </li>   
	    <br>   
	    <li><strong>Help</strong>: questa pagina.
        
	    </li>            

	</ul>
	</p>

	<p>Per accedere all'ultima versione disponibile clicca <a href="https://github.com/frankar/coa-reception/zipball/master">qui</a></p>

<?php } ?>
<?php include("footer.php");  ?>