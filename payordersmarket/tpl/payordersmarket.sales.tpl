<!-- BEGIN: MAIN -->
<div class="border-bottom border-secondary py-3 px-3">
	<nav aria-label="breadcrumb">
		<div class="ps-container-breadcrumb">
			<ol class="breadcrumb d-flex mb-0">
				{BREADCRUMBS}
			</ol>
		</div>
	</nav>
</div>

<div class="min-vh-50 px-0 px-md-3 py-4">
	<div class="col-12 container-3xl px-2">
		<h1 class="h3 mb-4">{PHP.L.payordersmarket_sales_title}</h1>

		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link<!-- IF !{PHP.status} --> active<!-- ENDIF -->"
				   href="{PHP|cot_url('payordersmarket', 'm=sales')}">
					{PHP.L.All}
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link<!-- IF {PHP.status} == 'paid' --> active<!-- ENDIF -->"
				   href="{PHP|cot_url('payordersmarket', 'm=sales&status=paid')}">
					{PHP.L.payordersmarket_sales_paidorders}
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link<!-- IF {PHP.status} == 'done' --> active<!-- ENDIF -->"
				   href="{PHP|cot_url('payordersmarket', 'm=sales&status=done')}">
					{PHP.L.payordersmarket_sales_doneorders}
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link<!-- IF {PHP.status} == 'claim' --> active<!-- ENDIF -->"
				   href="{PHP|cot_url('payordersmarket', 'm=sales&status=claim')}">
					{PHP.L.payordersmarket_sales_claimorders}
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link<!-- IF {PHP.status} == 'cancel' --> active<!-- ENDIF -->"
				   href="{PHP|cot_url('payordersmarket', 'm=sales&status=cancel')}">
					{PHP.L.payordersmarket_sales_cancelorders}
				</a>
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
						<div class="col-12 col-lg-2">{PHP.L.payordersmarket_order_cost}</div>
						<div class="col-12 col-lg-3">{PHP.L.payordersmarket_order_market_item_title}</div>
						<div class="col-12 col-lg-2">{PHP.L.payordersmarket_buyers}</div>
						<div class="col-12 col-lg-2">{PHP.L.payordersmarket_order_status}</div>
					</div>
				</div>

				<!-- BEGIN: ORDER_ROW -->
				<div class="list-group-item list-group-item-action">
					<div class="row">
						<div class="col-12 col-lg-1">
							<a href="{ORDER_ROW_URL}" class="d-flex align-items-center gap-1">
								<i class="fa-solid fa-link"></i>
								<span class="d-lg-none">{PHP.L.payordersmarket_order_number}</span>
								<span>{ORDER_ROW_ID}</span>
							</a>
						</div>


						<div class="col-12 col-lg-2">
							<!-- IF {ORDER_ROW_PAID} > 0 -->
							<div class="text-success">
								{ORDER_ROW_PAID|date('d.m.Y H:i', $this)}
							</div>
							<!-- ENDIF -->
							<div class="text-muted small">
								[{ORDER_ROW_DATE|date('d.m.Y H:i', $this)}]
							</div>
						</div>

						<div class="col-12 col-lg-2">
							<span class="fw-bold">
								{ORDER_ROW_COST} {PHP.cfg.payments.valuta}
							</span>
						</div>

						<div class="col-12 col-lg-3">
							[ID {ORDER_ROW_MARKET_ID}]
							<a href="{ORDER_ROW_MARKET_URL}">
								{ORDER_ROW_MARKET_TITLE}
							</a>
						</div>

						<div class="col-12 col-lg-2">
							<div class="text-muted small">
								<!-- IF {ORDER_ROW_CUSTOMER_ID} > 0 -->
								{ORDER_ROW_CUSTOMER_NAME}
								<!-- ELSE -->
								{ORDER_ROW_EMAIL}
								<!-- ENDIF -->
							</div>
						</div>

						<div class="col-12 col-lg-2">
							{ORDER_ROW_LOCALSTATUS}
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


<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">{BREADCRUMBS}</ol>
</nav>

<h1 class="h3 mb-4">{PHP.L.payordersmarket_sales_title}</h1>

<ul class="nav nav-tabs mb-3" id="myTab">
  <li class="nav-item">
    <a class="nav-link<!-- IF !{PHP.status} --> active<!-- ENDIF -->" href="{PHP|cot_url('payordersmarket', 'm=sales')}">{PHP.L.All}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link<!-- IF {PHP.status} == 'paid' --> active<!-- ENDIF -->" href="{PHP|cot_url('payordersmarket', 'm=sales&status=paid')}">{PHP.L.payordersmarket_sales_paidorders}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link<!-- IF {PHP.status} == 'done' --> active<!-- ENDIF -->" href="{PHP|cot_url('payordersmarket', 'm=sales&status=done')}">{PHP.L.payordersmarket_sales_doneorders}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link<!-- IF {PHP.status} == 'claim' --> active<!-- ENDIF -->" href="{PHP|cot_url('payordersmarket', 'm=sales&status=claim')}">{PHP.L.payordersmarket_sales_claimorders}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link<!-- IF {PHP.status} == 'cancel' --> active<!-- ENDIF -->" href="{PHP|cot_url('payordersmarket', 'm=sales&status=cancel')}">{PHP.L.payordersmarket_sales_cancelorders}</a>
  </li>
</ul>

<div class="list-group mb-3">
  <!-- BEGIN: ORDER_ROW -->
  <div class="list-group-item d-flex justify-content-between align-items-center">
    <div>
      <a href="{ORDER_ROW_URL}" class="fw-bold text-decoration-none">№ {ORDER_ROW_ID} [{ORDER_ROW_PAID|date('d.m.Y H:i', $this)}]</a>
      <div class="small text-muted">
        [ID {ORDER_ROW_MARKET_ID}] <a href="{ORDER_ROW_MARKET_URL}" class="text-decoration-none">{ORDER_ROW_MARKET_SHORTTITLE}</a>
      </div>
    </div>
    <div class="text-end">
      <div>{ORDER_ROW_COST} {PHP.cfg.payments.valuta}</div>
      <div class="small text-muted">
        <!-- IF {ORDER_ROW_CUSTOMER_ID} > 0 -->{ORDER_ROW_CUSTOMER_NAME}<!-- ELSE -->{ORDER_ROW_EMAIL}<!-- ENDIF -->
      </div>
    </div>
  </div>
  <!-- END: ORDER_ROW -->
</div>

<!-- IF {PAGENAV_COUNT} > 0 -->
<nav>
  <ul class="pagination justify-content-center">
    {PAGENAV_PAGES}
  </ul>
</nav>
<!-- ELSE -->
<div class="alert alert-info">{PHP.L.payordersmarket_empty}</div>
<!-- ENDIF -->


