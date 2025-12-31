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

		<!-- Header -->
		<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
			<h1 class="h3 mb-0">
				{PHP.L.payordersmarket_title} № {ORDER_ID}
			</h1>
			<span class="badge bg-info fs-6 align-self-start align-self-md-center">
				{ORDER_LOCALSTATUS}
			</span>
		</div>

		<!-- Order details -->
		<div class="card mb-4">
			<div class="card-body">
				<div class="row gy-3">

					<div class="col-12 col-md-6">
						<div class="text-muted small">{PHP.L.payordersmarket_product}</div>
						<div class="fw-semibold">
							<!-- IF {ORDER_MARKET_TITLE} -->
								<a href="{ORDER_MARKET_URL}" target="_blank">
									[ID {ORDER_MARKET_ID}] {ORDER_MARKET_TITLE}
								</a>
							<!-- ELSE -->
								{ORDER_TITLE}
							<!-- ENDIF -->
						</div>
					</div>

					<div class="col-6 col-md-3">
						<div class="text-muted small">{PHP.L.payordersmarket_count}</div>
						<div class="fw-semibold">{ORDER_COUNT}</div>
					</div>

					<div class="col-6 col-md-3">
						<div class="text-muted small">{PHP.L.payordersmarket_cost}</div>
						<div class="fw-bold">
							{ORDER_COST} {PHP.cfg.payments.valuta}
						</div>
					</div>

					<!-- IF {ORDER_COMMENT} -->
					<div class="col-12">
						<div class="text-muted small">{PHP.L.payordersmarket_comment}</div>
						<div>{ORDER_COMMENT}</div>
					</div>
					<!-- ENDIF -->

					<!-- IF {ORDER_STATUS} == "new" AND {ORDER_COST} > 0 -->
					<div class="col-12">
						<a href="{ORDER_ID|cot_url('payordersmarket','m=pay&id=$this')}"
						   class="btn btn-success">
							{PHP.L.payordersmarket_neworder_pay}
						</a>
					</div>
					<!-- ENDIF -->

					<!-- IF {ORDER_STATUS} != "new" -->
					<div class="col-12 col-md-6">
						<div class="text-muted small">{PHP.L.payordersmarket_paid}</div>
						<div>{ORDER_PAID|date('d.m.Y H:i', $this)}</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="text-muted small">{PHP.L.payordersmarket_warranty}</div>
						<div>{ORDER_WARRANTYDATE|date('d.m.Y H:i', $this)}</div>
					</div>

						<!-- IF {ORDER_DOWNLOAD} -->
						<div class="col-12">
							<div class="text-muted small">{PHP.L.payordersmarket_file_for_download}</div>
							<a href="{ORDER_DOWNLOAD}" class="btn btn-outline-primary btn-sm">
								{PHP.L.payordersmarket_file_download}
							</a>
						</div>
						<!-- ENDIF -->
					<!-- ENDIF -->

				</div>
			</div>
		</div>

		<!-- Claim button -->
		<!-- IF {ORDER_WARRANTYDATE} > {PHP.sys.now} AND {ORDER_STATUS} == 'paid' AND {PHP.usr.id} == {ORDER_CUSTOMER_ID} -->
		<a class="btn btn-warning mb-4"
		   href="{ORDER_ID|cot_url('payordersmarket', 'm=addclaim&id=$this')}">
			{PHP.L.payordersmarket_addclaim_button}
		</a>
		<!-- ENDIF -->

		<!-- Claim block -->
		<!-- BEGIN: CLAIM -->
		<h3 class="h5 mb-2">{PHP.L.payordersmarket_claim_title}</h3>
		<div class="card border-warning mb-4">
			<div class="card-body">
				<div class="d-flex justify-content-end mb-2">
					<div class="text-muted small">
						{CLAIM_DATE|date('d.m.Y H:i', $this)}
					</div>
				</div>

				<p class="mb-3">{CLAIM_TEXT}</p>

				<!-- BEGIN: ADMINCLAIM -->
				<div class="d-flex flex-column flex-sm-row gap-2">
					<a href="{ORDER_ID|cot_url('payordersmarket', 'a=acceptclaim&id=$this')}"
					   class="btn btn-warning">
						{PHP.L.payordersmarket_claim_accept}
					</a>
					<a href="{ORDER_ID|cot_url('payordersmarket', 'a=cancelclaim&id=$this')}"
					   class="btn btn-danger">
						{PHP.L.payordersmarket_claim_cancel}
					</a>
				</div>
				<!-- END: ADMINCLAIM -->

			</div>
		</div>
		<!-- END: CLAIM -->

	</div>
</div>
<!-- END: MAIN -->

<div class="border-bottom border-secondary py-3 px-3">
	<nav aria-label="breadcrumb">
		<div class="ps-container-breadcrumb">
			<ol class="breadcrumb d-flex mb-0"> {BREADCRUMBS} </ol>
		</div>
	</nav>
</div>
<div class="min-vh-50 px-0 px-md-3 py-4">
	<div class="col-12 container-3xl px-2">



<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">{PHP.L.payordersmarket_title} № {ORDER_ID}</h1>
  <span class="badge bg-info">{ORDER_LOCALSTATUS}</span>
</div>

<div class="table-responsive mb-3">
  <table class="table table-bordered align-middle">
    <tr>
      <th class="text-end" style="width:176px;">{PHP.L.payordersmarket_product}:</th>
      <td>
        <!-- IF {ORDER_MARKET_TITLE} -->
          <a href="{ORDER_MARKET_URL}" target="_blank">[ID {ORDER_MARKET_ID}] {ORDER_MARKET_TITLE}</a>
        <!-- ELSE -->
          {ORDER_TITLE}
        <!-- ENDIF -->
      </td>
    </tr>
    <tr>
      <th class="text-end">{PHP.L.payordersmarket_count}:</th>
      <td>{ORDER_COUNT}</td>
    </tr>
    <tr>
      <th class="text-end">{PHP.L.payordersmarket_comment}:</th>
      <td>{ORDER_COMMENT}</td>
    </tr>
    <tr>
      <th class="text-end">{PHP.L.payordersmarket_cost}:</th>
      <td>{ORDER_COST} {PHP.cfg.payments.valuta}</td>
    </tr>

    <!-- IF {ORDER_STATUS} == "new" -->
      <!-- IF {ORDER_COST} > 0 -->
        <tr>
          <td></td>
          <td>
            <a href="{ORDER_ID|cot_url('payordersmarket','m=pay&id=$this')}" class="btn btn-success">
              {PHP.L.payordersmarket_neworder_pay}
            </a>
          </td>
        </tr>
      <!-- ENDIF -->
    <!-- ELSE -->
      <tr>
        <th class="text-end">{PHP.L.payordersmarket_paid}:</th>
        <td>{ORDER_PAID|date('d.m.Y H:i', $this)}</td>
      </tr>
      <tr>
        <th class="text-end">{PHP.L.payordersmarket_warranty}:</th>
        <td>{ORDER_WARRANTYDATE|date('d.m.Y H:i', $this)}</td>
      </tr>
      <!-- IF {ORDER_DOWNLOAD} -->
      <tr>
        <th class="text-end">{PHP.L.payordersmarket_file_for_download}:</th>
        <td><a href="{ORDER_DOWNLOAD}">{PHP.L.payordersmarket_file_download}</a></td>
      </tr>
      <!-- ENDIF -->
    <!-- ENDIF -->
  </table>
</div>

<!-- IF {ORDER_WARRANTYDATE} > {PHP.sys.now} AND {ORDER_STATUS} == 'paid' AND {PHP.usr.id} == {ORDER_CUSTOMER_ID} -->
<a class="btn btn-warning mb-3" href="{ORDER_ID|cot_url('payordersmarket', 'm=addclaim&id=$this')}">
  {PHP.L.payordersmarket_addclaim_button}
</a>
<!-- ENDIF -->

<!-- BEGIN: CLAIM -->
<h3 class="h5">{PHP.L.payordersmarket_claim_title}</h3>
<div class="card border-warning mb-3">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-2">
      <div></div>
      <div class="text-muted small">{CLAIM_DATE|date('d.m.Y H:i', $this)}</div>
    </div>
    <p class="mb-3">{CLAIM_TEXT}</p>

    <!-- BEGIN: ADMINCLAIM -->
    <div class="d-flex gap-2">
      <a href="{ORDER_ID|cot_url('payordersmarket', 'a=acceptclaim&id=$this')}" class="btn btn-warning">
        {PHP.L.payordersmarket_claim_accept}
      </a>
      <a href="{ORDER_ID|cot_url('payordersmarket', 'a=cancelclaim&id=$this')}" class="btn btn-danger">
        {PHP.L.payordersmarket_claim_cancel}
      </a>
    </div>
    <!-- END: ADMINCLAIM -->
  </div>
</div>
<!-- END: CLAIM -->
  </div>
</div>

