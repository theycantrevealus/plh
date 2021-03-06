<div class="container-fluid page__heading-container">
  <div class="page__heading d-flex align-items-center">
    <div class="flex">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Reservasi</li>
        </ol>
      </nav>
    </div>
  </div>
</div>

<div class="container-fluid page__container">
  <div class="card">
    <div class="card-header card-header-large bg-white d-flex align-items-center">
      <h5 class="card-header__title flex m-0">Reservasi</h5>
      <button id="btnTambahReservasi" class="btn btn-info ml-3 pull-right">
        <i class="fa fa-plus"></i> Tambah Reservasi
      </button>
    </div>
    <div class="card-body">
      <div class="z-0">
        <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="nav_list">
          <li class="nav-item">
            <a href="#tab-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-1">
              <span class="nav-link__count">
                <i class="fa fa-address-book"></i>
              </span>
              Reservasi
            </a>
          </li>
          <li class="nav-item">
            <a href="#tab-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-2">
              <span class="nav-link__count">
                <i class="fa fa-address-book"></i>
              </span>
              History
            </a>
          </li>
        </ul>
      </div>
      <div class="card card-body tab-content">
        <div class="tab-pane show active fade" id="tab-1">
          <table class="table table-bordered" id="table-tipe">
            <thead class="thead-dark">
              <tr>
                <th class="wrap_content">No</th>
                <th>Guest</th>
                <th class="wrap_content">Check In</th>
                <th class="wrap_content">Check Out</th>
                <th class="wrap_content">Aksi</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
        <div class="tab-pane show fade" id="tab-2">
          <div class="row">
            <div class="col-6"></div>
            <div class="col-6">
              Filter
              <input id="range-reservasi" type="text" class="form-control" placeholder="Flatpickr range example" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $day->format('Y-m-1'); ?> to <?php echo $day->format('Y-m-d'); ?>" />
            </div>

            <div class="col-12">
              <br /><br />
              <table class="table table-bordered" id="table-history">
                <thead class="thead-dark">
                  <tr>
                    <th class="wrap_content">No</th>
                    <th>Guest</th>
                    <th class="wrap_content">Check In</th>
                    <th class="wrap_content">Check Out</th>
                    <th class="wrap_content">Aksi</th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>