<div align="right" style="padding: 20px"><a href="/beheer/leden/importeren"><b>Leden importeren</b></a></div>
<div class="container-fluid">
	<div class="col-md-6">
		<div class="table-responsive">
			<h2>Zwemmers</h2>
			<table class="table table-condensed table-striped table-hover table-responsive">
				<thead>
				<th>Naam</th>
				<th>Email</th>
				</thead>
				<tbody>
				<?php foreach( $leden as $lid ) {
					if( $lid->lidmaatschap === "zwemmer" ) { ?>
						<tr class="clickable-row" data-href="/profile/id/<?= $lid->id ?>">
							<td><?= $lid->naam ?></td>
							<td><a href="mailto:<?= $lid->email ?>"><?= $lid->email ?></a></td>
						</tr>
					<?php }
				} ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-6">
		<h2>Waterpolo</h2>
		<div class="table-responsive">
			<table class="table table-condensed table-striped table-hover table-responsive">
				<thead>
				<th>Naam</th>
				<th>Email</th>
				</thead>
				<tbody>
				<?php foreach( $leden as $lid ) {
					if( $lid->lidmaatschap === "waterpolo_competitie" || $lid->lidmaatschap === "waterpolo_recreatief" ) { ?>
						<tr class="clickable-row" data-href="/profile/id/<?= $lid->id ?>">
							<td><?= $lid->naam ?></td>
							<td><a href="mailto:<?= $lid->email ?>"><?= $lid->email ?></a></td>
						</tr>
					<?php }
				} ?>
				</tbody>
			</table>
		</div>
	</div>
</div>