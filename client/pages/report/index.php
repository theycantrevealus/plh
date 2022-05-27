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
      <h5 class="card-header__title flex m-0">Report</h5>
    </div>
    <div class="card-body">
      <table class="table table-bordered" id="table-report">
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
      </table>
    </div>
  </div>
</div>