<div class="container-fluid page__heading-container">
  <div class="page__heading d-flex align-items-center">
    <div class="flex">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/template/#">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
      </nav>
      <h4 class="m-0">Dashboard</h4>
    </div>
    <!-- <a href="<?php echo __HOSTNAME__; ?>/template/" class="btn btn-success ml-3">New Report</a> -->
  </div>
</div>




<div class="container-fluid page__container">

  <!-- <div class="alert alert-soft-warning d-flex align-items-center card-margin" role="alert">
		<i class="material-icons mr-3">error_outline</i>
		<div class="text-body"><strong>API gateways are now Offline.</strong> Please try the API later. If you want to stay up to date follow our <a href="<?php echo __HOSTNAME__; ?>/template/">Status Page </a></div>
	</div> -->
  <!-- <div class="row card-group-row">
		<div class="col-md-12 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<?php echo json_encode($_SESSION['akses_halaman_link']); ?>
			</div>
		</div>
	</div>

	<div class="row card-group-row">
		<div class="col-md-12 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<?php echo json_encode($_SESSION['akses_halaman']); ?>
			</div>
		</div>
	</div> -->
  <div class="row card-group-row">
    <div class="col-lg-3 col-md-6 card-group-row__col">
      <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
        <div class="flex">
          <div class="card-header__title text-muted mb-2">Room Arrival</div>
          <div class="text-amount"><i class="material-icons">person</i> <span id="room_arrival">0</span></div>
        </div>
        <div><i class="material-icons icon-muted icon-40pt ml-3">confirmation_number</i></div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 card-group-row__col">
      <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
        <div class="flex">
          <div class="card-header__title text-muted mb-2">Room Check Out</div>
          <div class="text-amount"><i class="material-icons">person</i> <span id="room_checkout">0</span></div>
        </div>
        <div><i class="material-icons icon-muted icon-40pt ml-3">local_hospital</i></div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 card-group-row__col">
      <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
        <div class="flex">
          <div class="card-header__title text-muted mb-2">Room Occoupied</div>
          <div class="text-amount"><i class="material-icons">person</i> <span id="room_occupied">0</span></div>
        </div>
        <div><i class="material-icons icon-muted icon-40pt ml-3">local_hospital</i></div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 card-group-row__col">
      <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
        <div class="flex">
          <div class="card-header__title text-muted mb-2">Room Saleable</div>
          <div class="text-amount"><i class="material-icons">person</i> <span id="room_available">0</span></div>
        </div>
        <div><i class="material-icons icon-muted icon-40pt ml-3">check</i></div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 card-group-row__col">
      <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
        <div class="flex">
          <div class="card-header__title text-muted mb-2">Room Vacant Clean</div>
          <div class="text-amount"><i class="material-icons">person</i> <span id="room_vacant">0</span></div>
        </div>
        <div><i class="material-icons icon-muted icon-40pt ml-3">confirmation_number</i></div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 card-group-row__col">
      <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
        <div class="flex">
          <div class="card-header__title text-muted mb-2">Room Vacant Dirty</div>
          <div class="text-amount"><i class="material-icons">person</i> <span id="room_vacant_dirty">0</span></div>
        </div>
        <div><i class="material-icons icon-muted icon-40pt ml-3">confirmation_number</i></div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 card-group-row__col">
      <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
        <div class="flex">
          <div class="card-header__title text-muted mb-2">Room Out of Order</div>
          <div class="text-amount"><i class="material-icons">person</i> <span id="room_oo">0</span></div>
        </div>
        <div><i class="material-icons icon-muted icon-40pt ml-3">local_hospital</i></div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 card-group-row__col">
      <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
        <div class="flex">
          <div class="card-header__title text-muted mb-2">Room Compliment</div>
          <div class="text-amount"><i class="material-icons">person</i> <span id="room_compliment">0</span></div>
        </div>
        <div><i class="material-icons icon-muted icon-40pt ml-3">local_hospital</i></div>
      </div>
    </div>
  </div>



</div>


<div class="container-fluid page__container">
  <div class="row">
    <div class="col-md-12">
      <div class="card-group">
        <div class="card card-body text-center">
          <div class="mb-1"><i class="material-icons icon-muted icon-40pt">security</i></div>
          <div class="text-amount"><b id="arr"></b></div>
          <div class="card-header__title mb-2">Arr</div>
        </div>
        <div class="card card-body text-center">
          <div class="mb-1"><i class="material-icons icon-muted icon-40pt">assessment</i></div>
          <div class="text-amount"><b id="occ"></b></div>
          <div class="card-header__title  mb-2">Occ</div>
        </div>
      </div>
    </div>

  </div>

</div>


<div class="container-fluid page__container">
  <div class="card">
    <div class="card-header card-header-large bg-white">
      <h4 class="card-header__title">Statistik Pendapatan</h4>
    </div>
    <div class="card-body">
      <input id="range_stok" type="text" class="form-control" placeholder="Flatpickr range example" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $day->format('Y-m-1'); ?> to <?php echo $day->format('Y-m-d'); ?>" />
      <div class="chart">
        <canvas id="currentStokGraph" width="400" height="50"></canvas>
      </div>
    </div>
  </div>
</div>