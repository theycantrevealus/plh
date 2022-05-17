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
      <div class="row card-group-row">
        <div class="col-lg-12 col-md-12 card-group-row__col">
          <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
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
        </div>
      </div>
    </div>
  </div>
</div>