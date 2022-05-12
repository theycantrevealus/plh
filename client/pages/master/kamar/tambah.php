<div class="container-fluid page__heading-container">
  <div class="page__heading d-flex align-items-center">
    <div class="flex">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
          <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/master/kamar">Kamar</a></li>
          <li class="breadcrumb-item active" aria-current="page" id="mode_item">Tambah</li>
        </ol>
      </nav>
      <h4>Tambah Kamar</h4>
    </div>
  </div>
</div>


<div class="container-fluid page__container">
  <div class="row">
    <div class="col-lg">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="txt_nama">Nomor Kamar:</label>
                <input type="text" class="form-control uppercase" id="txt_nama" placeholder="Nomor Kamar" required>
              </div>
            </div>
            <div class="col-md-12">
              <a href="<?php echo __HOSTNAME__; ?>/master/kamar" class="btn btn-danger">
                <i class="fa fa-ban"></i> Kembali
              </a>
              <button type="button" class="btn btn-success pull-right" id="btnSelesai">
                <i class="fa fa-check-circle"></i> Proses
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>