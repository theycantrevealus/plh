<?php
require '../config.php';
define('__PAGES__', explode('/', $_GET['pondokcoder_simrs']));
$day = new DateTime('last day of this month');


?>

<script type="text/javascript">
  var currentLoggedInState = localStorage.getItem("currentLoggedInState");
  var __CURRENT_DATE__ = <?php echo json_encode(date('Y-m-d')); ?>;
  var __PC_CUSTOMER__ = <?php echo json_encode(__PC_CUSTOMER__); ?>;
  var __PC_CUSTOMER_GROUP__ = <?php echo json_encode(__PC_CUSTOMER_GROUP__); ?>;
  var __PC_CUSTOMER_ADDRESS_SHORT__ = <?php echo json_encode(__PC_CUSTOMER_ADDRESS_SHORT__); ?>;
  var __PC_CUSTOMER_EMAIL__ = <?php echo json_encode(__PC_CUSTOMER_EMAIL__); ?>;
  var __PC_CUSTOMER_ADDRESS__ = <?php echo json_encode(__PC_CUSTOMER_ADDRESS__); ?>;
  var __PC_CUSTOMER_CONTACT__ = <?php echo json_encode(__PC_CUSTOMER_CONTACT__); ?>;
  var __PC_CUSTOMER_SITE__ = <?php echo json_encode(__PC_CUSTOMER_SITE__); ?>;
  var __SEGMEN_INDIVIDUAL__ = <?php echo json_encode(__SEGMEN_INDIVIDUAL__);  ?>;
  var __TAX_VAL__ = <?php echo json_encode(__TAX_VAL__);  ?>;
  var __SER_VAL__ = <?php echo json_encode(__SER_VAL__);  ?>;
  var __RULE_TRANS_DEPO__ = <?php echo json_encode(__RULE_TRANS_DEPO__);  ?>;
  var __RULE_TRANS_RATE_CHARGE__ = <?php echo json_encode(__RULE_TRANS_RATE_CHARGE__);  ?>;
  var __PC_IDENT__ = <?php echo json_encode(__PC_IDENT__); ?>;
  var __SYNC__ = <?php echo json_encode(__SYNC__); ?>;
  var __SYNC_PORT__ = <?php echo json_encode(__SYNC_PORT__); ?>;
  var __HOSTNAME__ = <?php echo json_encode(__HOSTNAME__); ?>;
  var __HOSTAPI__ = <?php echo json_encode(__HOSTAPI__); ?>;
  var __PAGES__ = <?php echo json_encode(__PAGES__); ?>;
  var __HOST__ = <?php echo json_encode(__HOST__); ?>;
  var __WNI__ = <?php echo json_encode(__WNI__); ?>;

  var __ME__ = <?php echo json_encode($_SESSION['uid']); ?>;
  var __PROFILE_PIC__ = <?php echo json_encode($_SESSION['profile_pic']); ?>;
  var __MY_NAME__ = <?php echo json_encode($_SESSION['nama']); ?>;
  var __MY_PRIVILEGES__ = <?php echo json_encode($_SESSION['jabatan']); ?>;
  var __UNIT__ = <?php echo json_encode($_SESSION['unit']); ?>;
  var __UNIT_MULTI__ = <?php echo json_encode($_SESSION['unit_multi']); ?>;
  var __POLI__ = <?php echo json_encode($_SESSION['poli']); ?>;

  if (__UNIT__ === null) {
    if (__UNIT_MULTI__ !== null) {
      __UNIT__ = __UNIT_MULTI__[0]['response_data'][0];
    }
  }
  var __NURSE_STATION__ = <?php echo json_encode($_SESSION['nurse_station']); ?>;

  var __GUDANG_UTAMA__ = <?php echo json_encode(__GUDANG_UTAMA__); ?>;


  var __UIDADMIN__ = <?php echo json_encode(__UIDADMIN__); ?>;

  var __PRIORITY_HIGH__ = <?php echo json_encode(__PRIORITY_HIGH__); ?>;
  var __STATUS_OPNAME__ = <?php echo json_encode(__STATUS_OPNAME__); ?>;
  var __AMPRAH_OPNAME_IN__ = <?php echo json_encode(__AMPRAH_OPNAME_IN__); ?>;
  var __AMPRAH_OPNAME_OUT__ = <?php echo json_encode(__AMPRAH_OPNAME_OUT__); ?>;
  var __STATUS_BARANG_MASUK_OPNAME__ = <?php echo json_encode(__STATUS_BARANG_MASUK_OPNAME__); ?>;
  var __STATUS_BARANG_KELUAR_OPNAME__ = <?php echo json_encode(__STATUS_BARANG_KELUAR_OPNAME__); ?>;


  var today = new Date();
  var dd = String(today.getDate()).padStart(2, '0');
  var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy = today.getFullYear();
  const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  var __TODAY__ = mm + ' ' + monthNames[today.getMonth()] + ' ' + yyyy;

  var __MAX_UPLOAD_FILE_SIZE__ = parseFloat(<?php echo json_encode(__MAX_UPLOAD_FILE_SIZE__); ?>);
</script>

<?php

if (
  isset($_SESSION['token']) ||
  __PAGES__[0] == 'anjungan' ||
  __PAGES__[0] == 'display' ||
  __PAGES__[0] == 'outlet' ||
  __PAGES__[0] == 'pos' ||
  __PAGES__[0] == 'display_dokter'
) {
  $params = parse_ini_file('../api/app/database.ini');
  $conStr = sprintf(
    "pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
    $params['host'],
    $params['port'],
    $params['database'],
    $params['user'],
    $params['password']
  );
  $pdo = new \PDO($conStr);
  $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  require 'builder.php';
} else {
  require 'pages/system/login.php';
}

?>