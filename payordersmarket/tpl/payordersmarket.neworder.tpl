<!-- BEGIN: MAIN -->
<div class="border-bottom border-secondary py-3 px-3">
	<nav aria-label="breadcrumb">
		<div class="ps-container-breadcrumb">
			<ol class="breadcrumb d-flex mb-0"> {BREADCRUMBS} </ol>
		</div>
	</nav>
</div>
<div class="min-vh-50 px-0 px-md-3 py-4">
	<div class="col-12 container-3xl px-2">
		<h1 class="h3 mb-0">{PHP.L.payordersmarket_neworder_title}</h1>
		<div class="customform">
			{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
			<form action="{NEWORDER_FORM_SEND}" method="post" name="neworderform">
				<ul class="list-group list-group-flush">
					<li class="list-group-item">
						<div class="row">
							<div class="col-4 text-end">{PHP.L.payordersmarket_neworder_product}:</div>
							<div class="col-8">[ID {NEWORDER_MARKET_ID}] <a href="{NEWORDER_MARKET_URL}" class="">{NEWORDER_MARKET_TITLE}</a></div>
						</div>
					</li>

					<li class="list-group-item">
						<div class="row">
							<div class="col-4 text-end">{PHP.L.payordersmarket_neworder_count}:</div>
							<div class="col-8">{NEWORDER_FORM_COUNT}</div>
						</div>
					</li>
					<li class="list-group-item">
						<div class="row">
							<div class="col-4 text-end">{PHP.L.payordersmarket_neworder_comment}:</div>
							<div class="col-8">{NEWORDER_FORM_COMMENT}</div>
						</div>
					</li>
					<!-- IF {PHP.usr.id} == 0 -->
					<li class="list-group-item">
						<div class="row">
							<div class="col-4 text-end">{PHP.L.payordersmarket_neworder_email}:</div>
							<div class="col-8">{NEWORDER_FORM_EMAIL}</div>
						</div>
					</li>
					<!-- ENDIF -->
					<li class="list-group-item">
						<div class="marketoptioncost-total">
							<span>{PHP.L.payordersmarket_neworder_total}</span>
							<span id="total">{NEWORDER_MARKET_COSTDFLT}</span> {PHP.cfg.payments.valuta}
						</div>
					</li>
					<li class="list-group-item">
						<div class="text-end">
							<input type="submit" class="btn btn-large btn-success" value="{PHP.L.payordersmarket_neworder_button}" />
						</div>
					</li>
				</ul>
			</form>
	
		</div>
	</div>
</div>
	<script>
	
		$().ready(function() {
			$('#count').bind('change click keyup', function (){
				var prdcost = {PHP.item.fieldmrkt_costdflt};
				var count = $('input[name="rcount"]').val();
				$('#total').html(prdcost*count);
			});
		});
		
	</script>

<!-- END: MAIN -->