<div class="container-fluid page__heading-container">
  <div class="page__heading d-flex align-items-center">
    <div class="flex">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
          <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/master/rate">Rate Code</a></li>
          <li class="breadcrumb-item active" aria-current="page" id="mode_item">Tambah</li>
        </ol>
      </nav>
      <h4>Tambah Rate Code</h4>
    </div>
  </div>
</div>


<div class="container-fluid page__container">
  <div class="row">
    <div class="col-lg">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="txt_kode">Kode:</label>
                <input type="text" class="form-control uppercase" id="txt_kode" placeholder="Kode Rate" required>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label for="txt_harga">Harga:</label>
                <input type="text" class="form-control uppercase" id="txt_harga" placeholder="Harga Paket" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="txt_keterangan">Keterangan:</label>
                <textarea id="txt_keterangan" class="form-control"></textarea>
              </div>
            </div>
            <div class="col-md-12">
              <table class="table" id="add_code">
                <thead class="thead-dark">
                  <tr>
                    <th class="wrap_content">No</th>
                    <th>Additional Item</th>
                    <th class="wrap_content">Harga Satuan</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
            <div class="col-md-12">
              <a href="<?php echo __HOSTNAME__; ?>/master/rate" class="btn btn-danger">
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