<?php
	$today = date('Y-m-d');
?>

<div class="intakes view no_li_bullets">
<div class="fieldset">
		<h1><?php __('Intake'); ?></h1>
		<div class="fieldset">
			<h2>Algemeen</h2>
			<table class="fixedwidth">
				<?php
					echo $format->printEmptyTableLine('Naam intaker');
					echo $format->printEmptyTableLine('Datum van intake');
				?>
			</table>
		</div>

		<div class="fieldset">
			<h2>Adresgegevens</h2>
			<table class="fixedwidth">
				<?php
					echo $format->printEmptyTableLine('Adres');
					echo $format->printEmptyTableLine('Postcode');
					echo $format->printEmptyTableLine('Woonplaats');
					echo $format->printEmptyTableLine('Verblijft in Nederland sinds');
					echo $format->printEmptyTableLine('Verblijft in Amsterdam sinds');
					echo $format->printEmptyTableLine(
						'Verblijfstatus',
						$verblijfstatussen,
						FormatHelper::UL_LIST_RADIO
					);
				?>
			</table>
		</div>
		<div class="fieldset">
			<h2>Locatiekeuze</h2>
			<table class="fixedwidth">
				<?php
					echo $format->printEmptyTableLine('Eerste locatiekeuze');
					echo $format->printEmptyTableLine('Tweede locatiekeuze');
					echo $format->printEmptyTableLine('Derde locatiekeuze');
					echo $format->printEmptyTableLine(
						'Heeft toegang tot de Vrouwen Nacht Opvang',
						'',
						FormatHelper::JANEE
					);
					echo $format->printEmptyTableLine(
						'mag_gebruiken',
						'',
						FormatHelper::JANEE
					);
				?>
			</table>
		</div>
		<div class="fieldset">
			<h2>Legitimatie</h2>
			<table class="fixedwidth">
				<?php
					echo $format->printEmptyTableLine(
						'Legitimatie',
						$legitimaties,
						FormatHelper::UL_LIST_RADIO
					);
					echo $format->printEmptyTableLine('Legitimatienummer');
					echo $format->printEmptyTableLine('Legitimatie geldig tot');
				?>
			</table>
		</div>

		<div class="fieldset">
			<h2>Verslaving</h2>
			<h3>Primaire problematiek</h3>
			<table class="fixedwidth">
				<?php
					echo $format->printEmptyTableLine(
						'Primaire problematiek',
						$problems,
						FormatHelper::UL_LIST_RADIO
					);
					echo $format->printEmptyTableLine(
						'Verslavingsfrequentie',
						$verslavingsfrequenties,
						FormatHelper::UL_LIST_RADIO
					);
					echo $format->printEmptyTableLine(
						'Verslavingsperiode',
						$verslavingsperiodes,
						FormatHelper::UL_LIST_RADIO
					);

					echo $format->printEmptyTableLine(
						'Hoe gebruikt client?',
						$verslavingsgebruikswijzen,
						FormatHelper::UL_LIST_CHECKBOXES
					);
				?>
			</table>

			<h3>Secundaire problematiek</h3>
			<table class="fixedwidth">
				<?php
					echo $format->printEmptyTableLine(
						'Verslaving',
						$problems,
						FormatHelper::UL_LIST_CHECKBOXES);

					echo $format->printEmptyTableLine('Overige vormen van verslavingen');
					echo $format->printEmptyTableLine(
						'Verslavingsfrequentie',
						$verslavingsfrequenties,
						FormatHelper::UL_LIST_RADIO
					);
					echo $format->printEmptyTableLine(
						'Verslavingsperiode',
						$verslavingsperiodes,
						FormatHelper::UL_LIST_RADIO
					);
					echo $format->printEmptyTableLine(
						'Hoe gebruikt client?',
						$verslavingsgebruikswijzen,
						FormatHelper::UL_LIST_CHECKBOXES
					);

				?>
			</table>
			<h3>Algemene problematiek</h3>
			<table class="fixedwidth">
				<?php
					echo $format->printEmptyTableLine('Wat is de datum van het eerste gebruik?');
				?>
			</table>
		</div>

		<div class="fieldset">
			<h2>Inkomen en woonsituatie</h2>
			<table class="fixedwidth">
				<?php
					echo $format->printEmptyTableLine(
						'Inkomen',
						$inkomens,
						FormatHelper::UL_LIST_CHECKBOXES);

					echo $format->printEmptyTableLine('Overige vormen van inkomen');
					echo $format->printEmptyTableLine(
						'Wat is de woonsituatie?',
						$woonsituaties,
						FormatHelper::UL_LIST_RADIO
					);
			?>
			</table>
		</div>

		<div class="fieldset">
			<h2>Overige hulpverlening</h2>
			<table class="fixedwidth extra_td_height">
				<?php
					echo $format->printEmptyTableLine(
						'Heeft de client contact met andere instanties?',
						$instanties,
						FormatHelper::UL_LIST_CHECKBOXES);

					echo $format->printEmptyTableLine('Opmerkingen van andere instanties');
					echo $format->printEmptyTableLine('Relevante medische achtergrond');
			?>
			</table>
		</div>

		<div class="fieldset">
			<h2>Verwachtingen en plannen</h2>
			<table class="fixedwidth extra_td_height">
				<?php
					echo $format->printEmptyTableLine('Wat verwacht de client van het dienstaanbod?');
					echo $format->printEmptyTableLine('Wat zijn de toekomstplannen van de client?');
			?>
			</table>
		</div>

		<div class="fieldset">
			<h2>Indruk</h2>

			<table class="fixedwidth extra_td_height">
				<?php
					echo $format->printEmptyTableLine('Indruk over client');
					echo $format->printEmptyTableLine('Deze client behoort tot de doelgroep', null, FormatHelper::JANEE);
			?>
			</table>
		</div>

		<div class="fieldset">
			<h2>Ondersteuning</h2>
			<p>
				Als je bij de vier vragen hieronder 'ja' invult,
				wordt er een e-mail verzonden naar de desbetreffende afdeling.
				Vul 'nee' in als de klant geen gebruik wenst te maken van deze
				mogelijkheden, of deze al gebruikt.
			</p>
			<table class="fixedwidth">
				<?php
					echo $format->printEmptyTableLine('Zou je het leuk vinden om iedere week met iemand samen iets te ondernemen?', null, FormatHelper::JANEE);
					echo $format->printEmptyTableLine('Zou je het leuk vinden om overdag iets te doen te hebben?', null, FormatHelper::JANEE);
					echo $format->printEmptyTableLine('Zou je een plek in de buurt willen hebben waar je iedere dag koffie kan drinken en mensen kan ontmoeten?', null, FormatHelper::JANEE);
					echo $format->printEmptyTableLine('Heeft u hulp nodig met regelzaken?', null, FormatHelper::JANEE);
			?>
			</table>
		</div>
</div>
</div>
<script type="text/javascript">
	$(function () {
		window.print();
	});
</script>
