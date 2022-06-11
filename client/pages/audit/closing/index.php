<div class="container-fluid page__heading-container">
  <div class="page__heading d-flex align-items-center">
    <div class="flex">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Room Posting</li>
        </ol>
      </nav>
    </div>
  </div>
</div>

<div class="container-fluid page__container">
  <div class="card">
    <div class="card-header card-header-large bg-white d-flex align-items-center">
      <h5 class="card-header__title flex m-0">Room Posting</h5>
      <button id="btnClosingHarian" class="btn btn-info ml-3 pull-right">
        <i class="fa fa-plus"></i> Closing Harian
      </button>
    </div>
    <div class="card-body">
      <table class="table table-bordered" id="table-closing">
        <thead class="thead-dark">
          <tr>
            <th class="wrap_content">No</th>
            <th class="wrap_content">Waktu Closing</th>
            <th>Pegawai</th>
            <th class="wrap_content">Aksi</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
  </div>
</div>