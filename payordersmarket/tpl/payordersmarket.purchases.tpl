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
		<h1 class="h3 mb-4">{PHP.L.payordersmarket_purchases_title}</h1>
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link<!-- IF !{PHP.status} --> active<!-- ENDIF -->" href="{PHP|cot_url('payordersmarket', 'm=purchases')}">{PHP.L.All}</a>
			</li>	
			<!-- IF {PHP.cfg.plugin.payordersmarket.showneworderswithoutpayment} -->
			<li class="nav-item">
				<a class="nav-link<!-- IF {PHP.status} == 'new' --> active<!-- ENDIF -->" href="{PHP|cot_url('payordersmarket', 'm=purchases&status=new')}">{PHP.L.payordersmarket_purchases_new}</a>
			</li>
			<!-- ENDIF -->
			
			<li class="nav-item">
				<a class="nav-link<!-- IF {PHP.status} == 'paid' --> active<!-- ENDIF -->" href="{PHP|cot_url('payordersmarket', 'm=purchases&status=paid')}">{PHP.L.payordersmarket_purchases_paidorders}</a>
			</li>
			
			<li class="nav-item">
				<a class="nav-link<!-- IF {PHP.status} == 'done' --> active<!-- ENDIF -->" href="{PHP|cot_url('payordersmarket', 'm=purchases&status=done')}">{PHP.L.payordersmarket_purchases_doneorders}</a>
			</li>
			
			<li class="nav-item">
				<a class="nav-link<!-- IF {PHP.status} == 'claim' --> active<!-- ENDIF -->" href="{PHP|cot_url('payordersmarket', 'm=purchases&status=claim')}">{PHP.L.payordersmarket_purchases_claimorders}</a>
			</li>
			
			<li class="nav-item">
				<a class="nav-link<!-- IF {PHP.status} == 'cancel' --> active<!-- ENDIF -->" href="{PHP|cot_url('payordersmarket', 'm=purchases&status=cancel')}">{PHP.L.payordersmarket_purchases_cancelorders}</a>
			</li>
		</ul>
		
		<div class="mt-3">
			
			<div class="list-group list-group-striped list-group-flush mb-3 border-bottom">
				<div class="list-group-item bd-callout bd-callout-info d-none d-lg-block">
					<div class="row align-items-center">
						<div class="col-12 col-lg-1">{PHP.L.payordersmarket_order_number}</div>
						<div class="col-12 col-lg-2">
							{PHP.L.payordersmarket_order_paid}
							<div class="text-muted small">[{PHP.L.payordersmarket_order_date}]</div>
						</div>
						<div class="col-12 col-lg-2">{PHP.L.payordersmarket_order_status}</div>
						<div class="col-12 col-lg-2">{PHP.L.payordersmarket_order_cost}</div>
						<div class="col-12 col-lg-3">{PHP.L.payordersmarket_order_market_item_title}</div>
						<div class="col-12 col-lg-2">{PHP.L.payordersmarket_order_seller_user}</div>
					</div>
				</div>
				
				<!-- BEGIN: ORDER_ROW -->
				
				<div class="list-group-item list-group-item-action">
					<div class="row align-items-center">
						<div class="col-12 col-lg-1">
							<a href="{ORDER_ROW_URL}" class="d-flex align-items-center gap-1">
								<i class="fa-solid fa-link"></i>
								<span class="d-lg-none">{PHP.L.payordersmarket_order_number}</span>
								<span class="fw-bold">{ORDER_ROW_ID}</span>
							</a>
						</div>
						
						<div class="col-12 col-lg-2">
							<!-- IF {ORDER_ROW_PAID} > 0 -->
							<div class="text-success">{ORDER_ROW_PAID|date('d.m.Y H:i', $this)}</div>
							<!-- ENDIF -->
							<!-- IF {ORDER_ROW_PAID} !== {ORDER_ROW_DATE} -->
							<div class="text-muted small">[{ORDER_ROW_DATE|date('d.m.Y H:i', $this)}]</div>
							<!-- ENDIF -->
						</div>
						<div class="col-12 col-lg-2">
							<div>{ORDER_ROW_LOCALSTATUS}</div>
							<!-- IF {ORDER_ROW_STATUS} == "new" AND {ORDER_ROW_COST} > 0 -->
							<a href="{ORDER_ROW_ID|cot_url('payordersmarket','m=pay&id=$this')}" class="btn btn-sm btn-outline-warning mb-1">
								{PHP.L.payordersmarket_neworder_pay}
							</a>
							<!-- ENDIF -->
						</div>
						<div class="col-12 col-lg-2"><span class="fw-bold">{ORDER_ROW_COST} {PHP.cfg.payments.valuta}</span></div>
						
						
						
						<div class="col-12 col-lg-3">
							[ID {ORDER_ROW_MARKET_ID}] <a href="{ORDER_ROW_MARKET_URL}">{ORDER_ROW_MARKET_TITLE}</a>
						</div>
						<div class="col-12 col-lg-2">
							<div class="text-muted small">{ORDER_ROW_SELLER_NAME}</div>
						</div>
					</div>
				</div>
				
				<!-- END: ORDER_ROW -->
			</div>
			
			
			
			
			<!-- IF {PAGENAV_COUNT} > 0 -->
			<nav>
				<ul class="pagination justify-content-center my-3">
					{PAGENAV_PAGES}
				</ul>
			</nav>
			<!-- ELSE -->
			<div class="alert alert-info my-3" role="alert">
				{PHP.L.payordersmarket_empty}
			</div>
			<!-- ENDIF -->
		</div>
	</div>
</div>
<!-- END: MAIN -->
