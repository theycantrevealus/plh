<div class="container-fluid page__heading-container">
  <div class="page__heading d-flex align-items-center">
    <div class="flex">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Report</li>
        </ol>
      </nav>
    </div>
  </div>
</div>


<div class="container-fluid page__container">
  <div class="card">
    <div class="card-header card-header-large bg-white d-flex align-items-center">
      <h5 class="card-header__title flex m-0">Daily Sales Report</h5>
      <button id="btnCetakDSR" class="btn btn-info pull-right">
        <i class="fa fa-print"></i> Cetak DSR
      </button>
    </div>
    <div class="card-body table-responsive" id="report_result">
      <table class="table table-striped table-report table-bordered">
        <thead class="thead-dark">
          <tr>
            <th rowspan="2">Description</th>
            <th rowspan="2">Total Actual</th>
            <th class="wrap_content" colspan="3">Month to Date</th>
            <th class="wrap_content" colspan="3">Year to Date Budget</th>
          </tr>
          <tr>
            <th class="wrap_content">Actual</th>
            <th class="wrap_content">Budget</th>
            <th class="wrap_content">Variance</th>
            <th class="wrap_content">Actual</th>
            <th class="wrap_content">Budget</th>
            <th class="wrap_content">Variance</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="8">
              <b>Room</b>
            </td>
          </tr>
          <tr>
            <td class="pad_1">Room Available</td>
            <td class="number_style" id="cap_room_available_actual">0</td>
            <td class="number_style" id="cap_room_available_month">0</td>
            <td class="number_style" id="cap_room_available_month_budget">0</td>
            <td class="number_style" id="cap_room_available_month_variance">0</td>
            <td class="number_style" id="cap_room_available_year">0</td>
            <td class="number_style" id="cap_room_available_year_budget">0</td>
            <td class="number_style" id="cap_room_available_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">Room Saleable</td>
            <td class="number_style" id="cap_room_saleable_actual">0</td>
            <td class="number_style" id="cap_room_saleable_month">0</td>
            <td class="number_style" id="cap_room_saleable_month_budget">0</td>
            <td class="number_style" id="cap_room_saleable_month_variance">0</td>
            <td class="number_style" id="cap_room_saleable_year">0</td>
            <td class="number_style" id="cap_room_saleable_year_budget">0</td>
            <td class="number_style" id="cap_room_saleable_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">Room Sold</td>
            <td class="number_style" id="cap_room_sold_actual">0</td>
            <td class="number_style" id="cap_room_sold_month">0</td>
            <td class="number_style" id="cap_room_sold_month_budget">0</td>
            <td class="number_style" id="cap_room_sold_month_variance">0</td>
            <td class="number_style" id="cap_room_sold_year">0</td>
            <td class="number_style" id="cap_room_sold_year_budget">0</td>
            <td class="number_style" id="cap_room_sold_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">Room Occupied</td>
            <td class="number_style" id="cap_room_occupied_actual">0</td>
            <td class="number_style" id="cap_room_occupied_month">0</td>
            <td class="number_style" id="cap_room_occupied_month_budget">0</td>
            <td class="number_style" id="cap_room_occupied_month_variance">0</td>
            <td class="number_style" id="cap_room_occupied_year">0</td>
            <td class="number_style" id="cap_room_occupied_year_budget">0</td>
            <td class="number_style" id="cap_room_occupied_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">%Room Occupied</td>
            <td class="number_style" id="cap_room_occupied_per_actual">0</td>
            <td class="number_style" id="cap_room_occupied_per_month">0</td>
            <td class="number_style" id="cap_room_occupied_per_month_budget">0</td>
            <td class="number_style" id="cap_room_occupied_per_month_variance">0</td>
            <td class="number_style" id="cap_room_occupied_per_year">0</td>
            <td class="number_style" id="cap_room_occupied_per_year_budget">0</td>
            <td class="number_style" id="cap_room_occupied_per_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">%Room Sold</td>
            <td class="number_style" id="cap_room_sold_per_actual">0</td>
            <td class="number_style" id="cap_room_sold_per_month">0</td>
            <td class="number_style" id="cap_room_sold_per_month_budget">0</td>
            <td class="number_style" id="cap_room_sold_per_month_variance">0</td>
            <td class="number_style" id="cap_room_sold_per_year">0</td>
            <td class="number_style" id="cap_room_sold_per_year_budget">0</td>
            <td class="number_style" id="cap_room_sold_per_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">OO Room</td>
            <td class="number_style" id="cap_room_oo_actual">0</td>
            <td class="number_style" id="cap_room_oo_month">0</td>
            <td class="number_style" id="cap_room_oo_month_budget">0</td>
            <td class="number_style" id="cap_room_oo_month_variance">0</td>
            <td class="number_style" id="cap_room_oo_year">0</td>
            <td class="number_style" id="cap_room_oo_year_budget">0</td>
            <td class="number_style" id="cap_room_oo_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">Number of Guest</td>
            <td class="number_style" id="cap_number_guest_actual">0</td>
            <td class="number_style" id="cap_number_guest_month">0</td>
            <td class="number_style" id="cap_number_guest_month_budget">0</td>
            <td class="number_style" id="cap_number_guest_month_variance">0</td>
            <td class="number_style" id="cap_number_guest_year">0</td>
            <td class="number_style" id="cap_number_guest_year_budget">0</td>
            <td class="number_style" id="cap_number_guest_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">Compliment</td>
            <td class="number_style" id="cap_compliment_actual">0</td>
            <td class="number_style" id="cap_compliment_month">0</td>
            <td class="number_style" id="cap_compliment_month_budget">0</td>
            <td class="number_style" id="cap_compliment_month_variance">0</td>
            <td class="number_style" id="cap_compliment_year">0</td>
            <td class="number_style" id="cap_compliment_year_budget">0</td>
            <td class="number_style" id="cap_compliment_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">Coorporate</td>
            <td class="number_style" id="cap_CPY_actual">0</td>
            <td class="number_style" id="cap_CPY_month">0</td>
            <td class="number_style" id="cap_CPY_month_budget">0</td>
            <td class="number_style" id="cap_CPY_month_variance">0</td>
            <td class="number_style" id="cap_CPY_year">0</td>
            <td class="number_style" id="cap_CPY_year_budget">0</td>
            <td class="number_style" id="cap_CPY_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">Government</td>
            <td class="number_style" id="cap_GOV_actual">0</td>
            <td class="number_style" id="cap_GOV_month">0</td>
            <td class="number_style" id="cap_GOV_month_budget">0</td>
            <td class="number_style" id="cap_GOV_month_variance">0</td>
            <td class="number_style" id="cap_GOV_year">0</td>
            <td class="number_style" id="cap_GOV_year_budget">0</td>
            <td class="number_style" id="cap_GOV_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">FIT</td>
            <td class="number_style" id="cap_FIT_actual">0</td>
            <td class="number_style" id="cap_FIT_month">0</td>
            <td class="number_style" id="cap_FIT_month_budget">0</td>
            <td class="number_style" id="cap_FIT_month_variance">0</td>
            <td class="number_style" id="cap_FIT_year">0</td>
            <td class="number_style" id="cap_FIT_year_budget">0</td>
            <td class="number_style" id="cap_FIT_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">House Use</td>
            <td class="number_style" id="cap_house_use_actual">0</td>
            <td class="number_style" id="cap_house_use_month">0</td>
            <td class="number_style" id="cap_house_use_month_budget">0</td>
            <td class="number_style" id="cap_house_use_month_variance">0</td>
            <td class="number_style" id="cap_house_use_year">0</td>
            <td class="number_style" id="cap_house_use_year_budget">0</td>
            <td class="number_style" id="cap_house_use_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">Mice</td>
            <td class="number_style" id="cap_MICE_actual">0</td>
            <td class="number_style" id="cap_MICE_month">0</td>
            <td class="number_style" id="cap_MICE_month_budget">0</td>
            <td class="number_style" id="cap_MICE_month_variance">0</td>
            <td class="number_style" id="cap_MICE_year">0</td>
            <td class="number_style" id="cap_MICE_year_budget">0</td>
            <td class="number_style" id="cap_MICE_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">OTA</td>
            <td class="number_style" id="cap_OTA_actual">0</td>
            <td class="number_style" id="cap_OTA_month">0</td>
            <td class="number_style" id="cap_OTA_month_budget">0</td>
            <td class="number_style" id="cap_OTA_month_variance">0</td>
            <td class="number_style" id="cap_OTA_year">0</td>
            <td class="number_style" id="cap_OTA_year_budget">0</td>
            <td class="number_style" id="cap_OTA_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">Travel Agent</td>
            <td class="number_style" id="cap_TA_actual">0</td>
            <td class="number_style" id="cap_TA_month">0</td>
            <td class="number_style" id="cap_TA_month_budget">0</td>
            <td class="number_style" id="cap_TA_year">0</td>
            <td class="number_style" id="cap_TA_year">0</td>
            <td class="number_style" id="cap_TA_year_budget">0</td>
            <td class="number_style" id="cap_TA_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">Cancel Reservation</td>
            <td class="number_style" id="cap_cancel_actual">0</td>
            <td class="number_style" id="cap_cancel_month">0</td>
            <td class="number_style" id="cap_cancel_month_budget">0</td>
            <td class="number_style" id="cap_cancel_month_variance">0</td>
            <td class="number_style" id="cap_cancel_year">0</td>
            <td class="number_style" id="cap_cancel_year_budget">0</td>
            <td class="number_style" id="cap_cancel_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">No Show Reservation</td>
            <td class="number_style" id="cap_no_show_actual">0</td>
            <td class="number_style" id="cap_no_show_month">0</td>
            <td class="number_style" id="cap_no_show_month_budget">0</td>
            <td class="number_style" id="cap_no_show_month_variance">0</td>
            <td class="number_style" id="cap_no_show_year">0</td>
            <td class="number_style" id="cap_no_show_year_budget">0</td>
            <td class="number_style" id="cap_no_show_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">Average Room Rate</td>
            <td class="number_style" id="cap_arr_actual">0</td>
            <td class="number_style" id="cap_arr_month">0</td>
            <td class="number_style" id="cap_arr_month_budget">0</td>
            <td class="number_style" id="cap_arr_month_variance">0</td>
            <td class="number_style" id="cap_arr_year">0</td>
            <td class="number_style" id="cap_arr_year_budget">0</td>
            <td class="number_style" id="cap_arr_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">Room Sales</td>
            <td class="number_style" id="cap_room_sales_actual">0</td>
            <td class="number_style" id="cap_room_sales_month">0</td>
            <td class="number_style" id="cap_room_sales_month_budget">0</td>
            <td class="number_style" id="cap_room_sales_month_variance">0</td>
            <td class="number_style" id="cap_room_sales_year">0</td>
            <td class="number_style" id="cap_room_sales_year_budget">0</td>
            <td class="number_style" id="cap_room_sales_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">Extra Bed</td>
            <td class="number_style" id="cap_extra_bed_actual">0</td>
            <td class="number_style" id="cap_extra_bed_month">0</td>
            <td class="number_style" id="cap_extra_bed_month_budget">0</td>
            <td class="number_style" id="cap_extra_bed_month_variance">0</td>
            <td class="number_style" id="cap_extra_bed_year">0</td>
            <td class="number_style" id="cap_extra_bed_year_budget">0</td>
            <td class="number_style" id="cap_extra_bed_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">Other Revenue</td>
            <td class="number_style" id="cap_other_revenue_actual">0</td>
            <td class="number_style" id="cap_other_revenue_month">0</td>
            <td class="number_style" id="cap_other_revenue_month_budget">0</td>
            <td class="number_style" id="cap_other_revenue_month_variance">0</td>
            <td class="number_style" id="cap_other_revenue_year">0</td>
            <td class="number_style" id="cap_other_revenue_year_budget">0</td>
            <td class="number_style" id="cap_other_revenue_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1">Rebate</td>
            <td class="number_style" id="cap_rebate_actual">0</td>
            <td class="number_style" id="cap_rebate_month">0</td>
            <td class="number_style" id="cap_rebate_month_budget">0</td>
            <td class="number_style" id="cap_rebate_month_variance">0</td>
            <td class="number_style" id="cap_rebate_year">0</td>
            <td class="number_style" id="cap_rebate_year_budget">0</td>
            <td class="number_style" id="cap_rebate_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1"><b>Total Room Revenue</b></td>
            <td class="number_style" id="cap_room_revenue_total_actual">0</td>
            <td class="number_style" id="cap_room_revenue_total_month">0</td>
            <td class="number_style" id="cap_room_revenue_total_month_budget">0</td>
            <td class="number_style" id="cap_room_revenue_total_month_variance">0</td>
            <td class="number_style" id="cap_room_revenue_total_year">0</td>
            <td class="number_style" id="cap_room_revenue_total_year_budget">0</td>
            <td class="number_style" id="cap_room_revenue_total_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1"><b>Service</b></td>
            <td class="number_style" id="cap_service_actual">0</td>
            <td class="number_style" id="cap_service_month">0</td>
            <td class="number_style" id="cap_service_month_budget">0</td>
            <td class="number_style" id="cap_service_month_variance">0</td>
            <td class="number_style" id="cap_service_year">0</td>
            <td class="number_style" id="cap_service_year_budget">0</td>
            <td class="number_style" id="cap_service_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1"><b>Tax</b></td>
            <td class="number_style" id="cap_tax_actual">0</td>
            <td class="number_style" id="cap_tax_month">0</td>
            <td class="number_style" id="cap_tax_month_budget">0</td>
            <td class="number_style" id="cap_tax_month_variance">0</td>
            <td class="number_style" id="cap_tax_year">0</td>
            <td class="number_style" id="cap_tax_year_budget">0</td>
            <td class="number_style" id="cap_tax_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1"><b>Room Revenue Nett</b></td>
            <td class="number_style" id="cap_room_revenue_nett_actual">0</td>
            <td class="number_style" id="cap_room_revenue_nett_month">0</td>
            <td class="number_style" id="cap_room_revenue_nett_month_budget">0</td>
            <td class="number_style" id="cap_room_revenue_nett_month_variance">0</td>
            <td class="number_style" id="cap_room_revenue_nett_year">0</td>
            <td class="number_style" id="cap_room_revenue_nett_year_budget">0</td>
            <td class="number_style" id="cap_room_revenue_nett_year_variance">0</td>
          </tr>
        </tbody>
        <tbody id="outlet-container">
          <tr>
            <td colspan="8">
              <b>Outlet</b>
            </td>
          </tr>
        </tbody>
        <tbody>
          <tr>
            <td class="pad_1"><b>Total Outlet Revenue</b></td>
            <td class="number_style" id="cap_outlet_revenue_actual">0</td>
            <td class="number_style" id="cap_outlet_revenue_month">0</td>
            <td class="number_style" id="cap_outlet_revenue_month_budget">0</td>
            <td class="number_style" id="cap_outlet_revenue_month_variance">0</td>
            <td class="number_style" id="cap_outlet_revenue_year">0</td>
            <td class="number_style" id="cap_outlet_revenue_year_budget">0</td>
            <td class="number_style" id="cap_outlet_revenue_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1"><b>Service</b></td>
            <td class="number_style" id="cap_outlet_service_actual">0</td>
            <td class="number_style" id="cap_outlet_service_month">0</td>
            <td class="number_style" id="cap_outlet_service_month_budget">0</td>
            <td class="number_style" id="cap_outlet_service_month_variance">0</td>
            <td class="number_style" id="cap_outlet_service_year">0</td>
            <td class="number_style" id="cap_outlet_service_year_budget">0</td>
            <td class="number_style" id="cap_outlet_service_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1"><b>Tax</b></td>
            <td class="number_style" id="cap_outlet_tax_actual">0</td>
            <td class="number_style" id="cap_outlet_tax_month">0</td>
            <td class="number_style" id="cap_outlet_tax_month_budget">0</td>
            <td class="number_style" id="cap_outlet_tax_month_variance">0</td>
            <td class="number_style" id="cap_outlet_tax_year">0</td>
            <td class="number_style" id="cap_outlet_tax_year_budget">0</td>
            <td class="number_style" id="cap_outlet_tax_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1"><b>Total Outlet Revenue Nett</b></td>
            <td class="number_style" id="cap_outlet_revenue_nett_actual">0</td>
            <td class="number_style" id="cap_outlet_revenue_nett_month">0</td>
            <td class="number_style" id="cap_outlet_revenue_nett_month_budget">0</td>
            <td class="number_style" id="cap_outlet_revenue_nett_month_variance">0</td>
            <td class="number_style" id="cap_outlet_revenue_nett_year">0</td>
            <td class="number_style" id="cap_outlet_revenue_nett_year_budget">0</td>
            <td class="number_style" id="cap_outlet_revenue_nett_year_variance">0</td>
          </tr>
        </tbody>
        <tbody>
          <tr>
            <td colspan="8">
              <b>Payment</b>
            </td>
          </tr>
          <tr>
            <td class="pad_1"><b>Cash Deposite</b></td>
            <td class="number_style" id="cap_cash_deposite_actual">0</td>
            <td class="number_style" id="cap_cash_deposite_month">0</td>
            <td class="number_style" id="cap_cash_deposite_month_budget">0</td>
            <td class="number_style" id="cap_cash_deposite_month_variance">0</td>
            <td class="number_style" id="cap_cash_deposite_year">0</td>
            <td class="number_style" id="cap_cash_deposite_year_budget">0</td>
            <td class="number_style" id="cap_cash_deposite_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1"><b>Cash Receipt</b></td>
            <td class="number_style" id="cap_receipt_actual">0</td>
            <td class="number_style" id="cap_receipt_month">0</td>
            <td class="number_style" id="cap_receipt_month_budget">0</td>
            <td class="number_style" id="cap_receipt_month_variance">0</td>
            <td class="number_style" id="cap_receipt_year">0</td>
            <td class="number_style" id="cap_receipt_year_budget">0</td>
            <td class="number_style" id="cap_receipt_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1"><b>Cash Refund</b></td>
            <td class="number_style" id="cap_refund_actual">0</td>
            <td class="number_style" id="cap_refund_month">0</td>
            <td class="number_style" id="cap_refund_month_budget">0</td>
            <td class="number_style" id="cap_refund_month_variance">0</td>
            <td class="number_style" id="cap_refund_year">0</td>
            <td class="number_style" id="cap_refund_year_budget">0</td>
            <td class="number_style" id="cap_refund_year_variance">0</td>
          </tr>
          <tr>
            <td class="pad_1"><b>City Ledger</b></td>
            <td class="number_style" id="cap_city_ledger_actual">0</td>
            <td class="number_style" id="cap_city_ledger_month">0</td>
            <td class="number_style" id="cap_city_ledger_month_budget">0</td>
            <td class="number_style" id="cap_city_ledger_month_variance">0</td>
            <td class="number_style" id="cap_city_ledger_year">0</td>
            <td class="number_style" id="cap_city_ledger_year_budget">0</td>
            <td class="number_style" id="cap_city_ledger_year_variance">0</td>
          </tr>
        </tbody>
      </table>
      <!-- <table class="table table-bordered" id="table-report">
        <thead class="thead-dark">
          <tr>
            <th>Description</th>
            <th class="wrap_content">Total Actual</th>
            <th class="wrap_content">Actual</th>
            <th class="wrap_content">Month to Date Budget</th>
            <th class="wrap_content">Variance</th>
            <th class="wrap_content">Actual</th>
            <th class="wrap_content">Year to Date Budget</th>
            <th class="wrap_content">Variance</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <b>STATISTIC</b>
            </td>
            <td colspan="7"></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              ROOM AVAILABLE
            </td>
            <td class="number_style text-muted" id="actual_room_available">0</td>
            <td class="number_style text-muted" id="month_room_available">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted" id="year_room_available">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted">0</td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              ROOM SALEABLE
            </td>
            <td class="number_style text-muted" id="actual_room_saleable">0</td>
            <td class="number_style text-muted" id="month_room_saleable">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted" id="year_room_saleable">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted">0</td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              ROOM SOLD
            </td>
            <td class="number_style text-muted" id="actual_room_sold">0</td>
            <td class="number_style text-muted" id="month_room_sold">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted" id="year_room_sold">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted">0</td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              ROOM OCCUPIED
            </td>
            <td class="number_style text-muted" id="actual_room_occupied">0</td>
            <td class="number_style text-muted" id="month_room_occupied">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted" id="year_room_occupied">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted">0</td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              %ROOM OCCUPIED
            </td>
            <td class="number_style text-muted" id="actual_room_occupied_percentage">0</td>
            <td class="number_style text-muted" id="month_room_occupied_percentage">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted" id="year_room_occupied_percentage">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted">0</td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              %ROOM SOLD
            </td>
            <td class="number_style text-muted" id="actual_room_sold_percentage">0</td>
            <td class="number_style text-muted" id="month_room_sold_percentage">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted" id="year_room_sold_percentage">0</td>
            <td class="number_style text-muted">0</td>
            <td class="number_style text-muted">0</td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              OO ROOM
            </td>
            <td id="actual_oo_room"></td>
            <td id="month_oo_room"></td>
            <td></td>
            <td></td>
            <td id="year_oo_room"></td>
            <td></td>
            <td></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              NUMBER OF GUEST
            </td>
            <td id="actual_number_guest"></td>
            <td id="month_number_guest"></td>
            <td></td>
            <td></td>
            <td id="year_number_guest"></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td class="pad_1">
              GUEST TYPE
            </td>
            <td colspan="7"></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              COMPLIMENT
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
        </tbody>
        <tbody id="load_cust_type">

        </tbody>
        <tbody>
          <tr class="num_style">
            <td class="pad_1">
              HOUSE USE
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              MICE
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              OTA
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              TRAVEL AGENT
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="8"><br /></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              CANCEL RESERVATION
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              NO SHOW RESERVATION
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="8"><br /></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              AVERAGE ROOM RATE
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              REV PAR
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="8"><br /></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              <b>ROOM REVENUE</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              <b>ROOM SALES</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              <b>EXTRA BED</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              <b>OTHER REVENUE</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              <b>REBATE REVENUE</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              <b>TOTAL ROOM REVENUE</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              <b>TAX</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              <b>SERVICE</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr class="num_style">
            <td class="pad_1">
              <b>TOTAL ROOM REVENUE NETT</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
        </tbody>
      </table> -->
    </div>
  </div>
</div>