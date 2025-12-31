<!-- Подключите Bootstrap 5.3.8 в <head> вашего шаблона:
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
-->

<!-- BEGIN: MAIN -->
<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">{PHP.L.payordersmarket_sales_title}</h3>
    <!-- При необходимости можно добавить кнопку "Создать" или фильтры -->
  </div>

  <!-- Навигационные вкладки статусов -->
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link<!-- IF !{PHP.status} --> active<!-- ENDIF -->" href="{PHP|cot_url('admin', 'm=other&p=payordersmarket')}" role="tab">{PHP.L.All}</a>
    </li>

    <!-- IF {PHP.cfg.plugin.payordersmarket.showneworderswithoutpayment} -->
    <li class="nav-item" role="presentation">
      <a class="nav-link<!-- IF {PHP.status} == 'new' --> active<!-- ENDIF -->" href="{PHP|cot_url('admin', 'm=other&p=payordersmarket&status=new')}" role="tab">{PHP.L.payordersmarket_purchases_new}</a>
    </li>
    <!-- ENDIF -->

    <li class="nav-item" role="presentation">
      <a class="nav-link<!-- IF {PHP.status} == 'paid' --> active<!-- ENDIF -->" href="{PHP|cot_url('admin', 'm=other&p=payordersmarket&status=paid')}" role="tab">{PHP.L.payordersmarket_sales_paidorders}</a>
    </li>

    <li class="nav-item" role="presentation">
      <a class="nav-link<!-- IF {PHP.status} == 'done' --> active<!-- ENDIF -->" href="{PHP|cot_url('admin', 'm=other&p=payordersmarket&status=done')}" role="tab">{PHP.L.payordersmarket_sales_doneorders}</a>
    </li>

    <li class="nav-item" role="presentation">
      <a class="nav-link<!-- IF {PHP.status} == 'claim' --> active<!-- ENDIF -->" href="{PHP|cot_url('admin', 'm=other&p=payordersmarket&status=claim')}" role="tab">{PHP.L.payordersmarket_sales_claimorders}</a>
    </li>

    <li class="nav-item" role="presentation">
      <a class="nav-link<!-- IF {PHP.status} == 'cancel' --> active<!-- ENDIF -->" href="{PHP|cot_url('admin', 'm=other&p=payordersmarket&status=cancel')}" role="tab">{PHP.L.payordersmarket_sales_cancelorders}</a>
    </li>
  </ul>

  <div class="mt-3">
    <!-- BEGIN: ORDER_ROW -->
    <div class="card mb-3">
      <div class="card-body">
        <div class="row align-items-start">
          <div class="col-auto">
            <div class="small text-muted">
              <a class="stretched-link text-decoration-none" href="{ORDER_ROW_URL}">№ {ORDER_ROW_ID}</a>
              <div>
                [<!-- IF {ORDER_ROW_PAID} > 0 -->{ORDER_ROW_PAID|date('d.m.Y H:i', $this)}<!-- ELSE -->{ORDER_ROW_DATE|date('d.m.Y H:i', $this)}<!-- ENDIF -->]
              </div>
            </div>
          </div>

          <div class="col-md-6 col-sm-12">
            <div class="fw-semibold">
              <a href="{ORDER_ROW_MARKET_URL}" class="text-decoration-none">{ORDER_ROW_MARKET_SHORTTITLE}</a>
            </div>
            <div class="text-muted small">Продавец: {ORDER_ROW_SELLER_NAME}</div>
          </div>

          <div class="col-md-3 col-sm-8">
            <div class="small text-muted">Покупатель</div>
            <div>
              <!-- IF {ORDER_ROW_CUSTOMER_ID} > 0 -->{ORDER_ROW_CUSTOMER_NAME}<!-- ELSE -->{ORDER_ROW_EMAIL}<!-- ENDIF -->
            </div>
          </div>

          <div class="col-auto text-end">
            <div class="h6 mb-1">{ORDER_ROW_COST} {PHP.cfg.payments.valuta}</div>
            <!-- Пример метки статуса (можно динамически вставлять класс/текст) -->
            <!-- IF {ORDER_ROW_PAID} > 0 -->
              <span class="badge bg-success">Оплачен</span>
            <!-- ELSE -->
              <span class="badge bg-warning text-dark">Не оплачен</span>
            <!-- ENDIF -->
          </div>
        </div>
      </div>
    </div>
    <hr class="my-2"/>
    <!-- END: ORDER_ROW -->

    <!-- IF {PAGENAV_COUNT} > 0 -->
    <nav aria-label="Page navigation">
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
<!-- END: MAIN -->
