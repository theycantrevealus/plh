<div class="anjungan-container">
  <div class="container-fluid page__container" style="margin-top: 10px;">
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-3">
                <a href="<?php echo __HOSTNAME__; ?>/outlet">
                  <h4>
                    <i class="fa fa-arrow-circle-left"></i> Back
                  </h4>
                </a>
              </div>
              <div class="col-9">
                <h5 class="text-right" id="nama_outlet">Nama Outlet</h5>
              </div>
              <div class="col-12">
                <div class="z-0">
                  <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="nav_list">
                    <li class="nav-item">
                      <a href="#tab-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-1">
                        <span class="nav-link__count">
                          <i class="fa fa-cubes"></i>
                        </span>
                        Menu
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="#tab-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-2">
                        <span class="nav-link__count">
                          <i class="fa fa-copy"></i>
                        </span>
                        Order Active
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="#tab-3" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-3">
                        <span class="nav-link__count">
                          <i class="fa fa-database"></i>
                        </span>
                        Order History
                      </a>
                    </li>
                  </ul>
                </div>
                <div class="card card-body tab-content">
                  <div class="tab-pane show active fade" id="tab-1">
                    <br />
                    <input type="text" class="form-control" id="search" placeholder="Cari Item" />
                    <br />
                    <div class="row" id="loader-item"></div>
                  </div>
                  <div class="tab-pane show fade" id="tab-2">
                    <table class="table largeDataType" id="currentOrder">
                      <thead class="thead-dark">
                        <tr>
                          <th class="wrap_content">No</th>
                          <th>Table</th>
                          <th>Pegawai</th>
                          <th class="wrap_content">Aksi</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                  <div class="tab-pane show fade" id="tab-3">
                    <table class="table largeDataType" id="oldOrder">
                      <thead class="thead-dark">
                        <tr>
                          <th class="wrap_content">No</th>
                          <th class="wrap_content">Tgl</th>
                          <th>Cust. Type</th>
                          <th>Total</th>
                          <th class="wrap_content">Aksi</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body" id="orderContainer" style="min-height: 500px; max-height: 500px; overflow-y: scroll">
            <h4>Order List</h4>
            <br />
            Meja
            <input type="text" id="pick-table" class="form-control" readonly />
            <ol style="list-style-type:none" id="orderList"></ol>
          </div>
        </div>
        <div class="card" id="btnProceedOrder">
          <div class="card-body btn btn-info">
            <h4 style="color: #fff"><i class="fa fa-check"></i>&nbsp;&nbsp;&nbsp;Proceed</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style type="text/css">
  .itemSelector {
    cursor: pointer;
    cursor: hand;
  }

  #orderList li {
    border-bottom: dashed 2px #ccc;
    padding: 10px 5px;
  }
</style>