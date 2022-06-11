<?php
if (!isset($_POST['__HOSTNAME__'])) {
  require '../../config.php';
  $__HOSTNAME__ = __HOSTNAME__;
} else {
  $__HOSTNAME__ = $_POST['__HOSTNAME__'];
}
?>
<html>

<head>
  <style type='text/css'>
    @font-face {
      font-family: "GreyCLiff";
      src: url("<?php echo $__HOSTNAME__ . '/template/assets/fonts/FontsFree-Net-greycliff-cf-bold.ttf'; ?>");
    }

    @font-face {
      font-family: "Arial";
      src: url("<?php echo $__HOSTNAME__ . '/template/assets/fonts/ArialCEMTBlack.ttf'; ?>");
    }

    @font-face {
      font-family: "ArialCE";
      src: url("<?php echo $__HOSTNAME__ . '/template/assets/fonts/ArialCE.ttf'; ?>");
    }

    @page {
      size: auto;
    }

    @media print {
      @page {
        size: A4;
      }

      html,
      body {
        padding: 0 !important;
        background: #fff !important;
      }

      .content {
        margin: 0 !important;
        border: none;
      }

      table.constructor thead {
        display: table-header-group !important;
      }

      table.constructor tfoot {
        display: table-footer-group !important;
      }


    }

    html,
    body {
      position: relative;
      width: 99%;
      padding: 0 .5%;
      font-size: 6pt !important;
      background: #fff !important;
    }

    .card,
    .card-body,
    .card-header {
      border: none !important;
    }

    table.table {
      page-break-inside: auto
    }

    table.table tr {
      page-break-inside: avoid;
      page-break-after: auto
    }

    table.table thead {
      display: table-row-group;
    }

    table.table tfoot {
      display: table-row-group;
    }



    .grey-up {
      background: #ccc;
      font-weight: bolder;
    }

    table.constructor {
      width: 100%;
    }

    table.constructor tbody td {
      padding: 0cm 1cm;
    }

    table.form-mode tbody tr td {
      padding: 0 10px !important;
    }

    .content {
      background: #fff;
      margin: -1px !important;
    }

    .content .tl {
      position: fixed;
      top: -5px;
      left: -5px;
      width: 250px;
      height: 250px;
      opacity: .1;
    }

    .header {
      left: 1cm;
      top: 1cm;
      right: 1cm;
      text-align: left;
      font-family: GreyCLiff;
    }

    .header-space {
      height: 210px;
    }

    h1.title-name {
      font-size: 6pt;
      text-align: center;
      letter-spacing: 2px;
    }

    .header h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      color: #000 !important;
      margin: 0;
    }

    .header h1 {
      font-family: Arial;
      letter-spacing: 2px;
    }

    .header h1 small {
      color: #0e8900;
      letter-spacing: 0;
    }

    .header table {
      width: 100%;
      padding-bottom: .4cm;
    }

    .header table tr td {
      vertical-align: top;
    }

    .header .header-information {
      font-family: ArialCE !important;
      font-size: 12pt;
      color: #000;
    }

    .logo-container {
      position: relative;
      width: 10%;
    }

    img.logo {
      margin: -20px auto 0cm auto;
    }

    img.logo {
      width: 2cm;
      height: 2cm;
    }

    img.logo2 {
      width: 2.5cm;
      height: 3cm;
    }

    .print-date {
      width: 100%;
      text-align: right;
    }



    .report_content {
      font-size: 8pt;
      margin-top: -10px;
      page-break-after: always;
    }

    .row {
      display: block;
      width: 100%;
    }

    .row div {
      position: relative;
      float: left;
    }

    .row div.col-4 {
      width: 33.33%;
    }

    .row div.col-8 {
      width: 66.66%;
    }

    .row div.col-3 {
      width: 25%;
    }

    .row div.col-6 {
      width: 25%;
    }

    .row div.col-12 {
      width: 100%;
    }

    .boxes-optional {
      display: block;
    }

    .boxes-optional div {
      display: flex;
      align-items: center;
    }

    .boxes {
      width: 10px;
      height: 10px;
      border: solid 1px #000;
      margin: 5px;
    }

    span,
    b,
    p {
      font-size: 8pt;
    }

    .wrap_content {
      white-space: nowrap;
    }

    table.table-form tr td {
      vertical-align: top;
      font-family: GreyCLiff;
      border: solid 1px #000;
      padding: .1cm;
      font-size: 8pt;
    }

    table.table-form tr td b {
      color: #0093B6;
      font-size: 8pt;
    }

    table.table {
      width: 99%;
      margin: .5%;
      border-collapse: collapse;
      border: solid 1px #000 !important;
      border-width: 1px !important;
    }

    table.table.table-bordered tbody tr td {
      border-bottom: solid 1px #000 !important;
      padding: 5px !important;
      border-width: 1px !important;
    }

    table.table.table-bordered-full tbody tr td {
      padding: 5px;
      border: solid 1px #000 !important;
      border-width: 1px !important;
    }

    table.table.table-bordered tbody tr td[colspan] {
      color: #0199f0 !important;
      padding: 20px 20px 0 20px;
      border-bottom: solid 1px #000 !important;
    }

    table.table.table-bordered tbody tr td[colspan].text-mode {
      color: #000 !important;
    }

    table.table thead.thead-dark tr th {
      padding: 5px !important;
      color: #fff;
      border-bottom: solid 1px #000 !important;
      font-size: 10pt !important;
      text-align: left !important;
    }

    table.table tfoot tr td {
      padding: 5px !important;
      border: solid 1px #000 !important;
    }

    td.special-type-padding {
      padding-bottom: 20px !important;
    }

    .number_style {
      text-align: right;
      font-family: "Courier New" !important;
    }

    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center !important;
    }

    .largeDataType tbody tr td {
      vertical-align: top !important;
      font-size: 10pt;
    }

    #qrcodeImage img {
      width: 128px;
      height: 128px;
      margin: 0 auto;
      text-align: center;
      background: #ccc;
    }

    .signing-panel {
      height: 100px !important;
      min-height: 100px !important;
      border-bottom: solid 1px #000 !important;
    }

    i.as {
      color: #808080 !important;
    }

    .rule-list {
      margin-left: -30px;
    }

    .rule-list-indo {
      margin-left: -30px;
    }

    .rule-list li {
      font-size: 6pt;
      color: #595959;
    }

    .rule-list-indo li {
      font-size: 6pt;
      color: #595959;
    }
  </style>

</head>

<?php
function rePrint($target)
{
  if (isset($target) && !empty($target)) {
    return $target;
  } else {
    return '-';
  }
}
?>

<body>
  <div class="content">
    <div class="header">
      <table>
        <tr>
          <td style="position: relative;" class="text-center">
            <img src="http://<?php echo $_SERVER['SERVER_ADDR'] ?>/hotel/client/template/assets/images/clients/logo-text-black-<?php echo $_POST['__PC_IDENT__']; ?>.png" class="logo" />
          </td>
        </tr>
        <!-- <tr>
          <td style="padding-top: 0cm !important;">
            <h1 class="text-center" style="font-size: 14pt !important;">
              <?php echo (isset($_POST['__PC_CUSTOMER_GROUP__'])) ? $_POST['__PC_CUSTOMER_GROUP__'] : 'CUSTOMER GROUP NAME'; ?>
            </h1>
            <h1 class="text-center" style="font-size: 18pt !important; letter-spacing: -1px">
              <?php echo (isset($_POST['__PC_CUSTOMER__'])) ? $_POST['__PC_CUSTOMER__'] : 'CUSTOMER COMPANY FULL NAME'; ?>
            </h1>
            <small class="header-information text-center">
              <center>
                <?php echo (isset($_POST['__PC_CUSTOMER_ADDRESS__'])) ? $_POST['__PC_CUSTOMER_ADDRESS__'] : 'CUSTOMER ADDRESS'; ?> Telp. <?php echo (isset($_POST['__PC_CUSTOMER_CONTACT__'])) ? $_POST['__PC_CUSTOMER_CONTACT__'] : '085261510202'; ?>
                <br />
                Email: <?php echo $_POST['__PC_CUSTOMER_EMAIL__']; ?><br />
                <b><?php echo $_POST['__PC_CUSTOMER_ADDRESS_SHORT__']; ?></b>
              </center>
            </small>
          </td>
        </tr> -->
      </table>
    </div>
    <div class="report_content">
      <div>
        <h4 class="text-center">Registration Form</h4>
        <h6 class="text-center">Formulir Pendaftaran</h6>
        <div class="row">
          <div class="col-12">
            <table class="table table-form">
              <tr>
                <td colspan="2" style="width: 25%;">
                  Arrival Date / <i class="as">Tgl. Kedatangan</i>
                  <br />
                  <b><?php echo rePrint($_POST['final_arrival_date']); ?></b>
                </td>
                <td style="width: 25%;">
                  Time Check In / <i class="as">Jam Masuk</i>
                  <br />
                  <b><?php echo rePrint($_POST['final_checkin']); ?></b>
                </td>
                <td colspan="2" style="width: 25%;">
                  Departure Date / <i class="as">Tgl. Keberangkatan</i>
                  <br />
                  <b><?php echo rePrint($_POST['final_departure_date']); ?></b>
                </td>
                <td style="width: 25%;">
                  Length Of Stay / <i class="as">Lama Menginap</i>
                  <br />
                  <b><?php echo rePrint($_POST['final_night']); ?></b>
                </td>
              </tr>


              <tr>
                <td colspan="2">
                  No. of Guests / <i class="as">Jumlah Tamu</i>
                  <br />
                  <b><?php echo rePrint($_POST['final_pax']); ?></b>
                </td>
                <td>
                  Room Type / <i class="as">Jenis Kamar</i>
                  <br />
                  <b><?php echo rePrint($_POST['final_tipe']); ?></b>
                </td>
                <td colspan="2">
                  Room / <i class="as">No. Kamar</i>
                  <br />
                  <b>#<?php echo rePrint($_POST['final_kamar']); ?></b>
                </td>
                <td>
                  Reservation No. / <i class="as">No. Reservasi</i>
                  <br />
                  <b><?php echo rePrint($_POST['final_res_no']); ?></b>
                </td>
              </tr>

              <tr>
                <td style="width: 12.5%;">
                  Name / <i class="as">Nama</i>
                </td>
                <td style="width: 12.5%;">
                  Surname<br /><i class="as">Nama Keluarga</i>
                </td>
                <td colspan="2">
                  First and Middle Name<br /><i class="as">Nama Depan dan Tengah</i>
                </td>
                <td colspan="2">
                  Deposite / <i class="as">Deposit</i>
                  <br />
                  <b><?php echo rePrint($_POST['final_deposit']); ?></b>
                </td>
              </tr>

              <tr>
                <td>
                  <b><?php echo rePrint($_POST['final_panggilan']); ?></b>
                </td>
                <td colspan="3">
                  <b><?php echo rePrint($_POST['final_nama_guest']); ?></b>
                </td>
                <td colspan="2">
                  Room Rate / <i class="as">Tarif Kamar</i>
                  <br />
                  <b><?php echo rePrint($_POST['final_rate']); ?></b>
                </td>
              </tr>
              <tr>
                <td rowspan="2">
                  Address / <i class="as">Alamat</i>
                </td>
                <td colspan="3" rowspan="2">
                  Residetial / <i class="as">Rumah</i>
                </td>
                <td colspan="2" rowspan="2">
                  Payment Method / <i class="as">Pembayaran</i>
                  <br />
                  <b><?php echo rePrint($_POST['final_payment']); ?></b>
                </td>
              </tr>

              <tr>
                <td colspan="3"></td>
                <td colspan="2">

                </td>
              </tr>

              <tr>
                <td colspan="2">
                  Nationality / <i class="as">Kebangsaan</i>
                  <br />
                  <b><?php echo rePrint($_POST['final_nationality']); ?></b>
                </td>
                <td colspan="2">
                  ID No. / <i class="as">No KTP</i> / <i class="as">SIM</i>
                  <br />
                  <b><?php echo rePrint($_POST['final_ktp']); ?></b>
                </td>
                <td colspan="2">
                  No. Card
                  <br />
                  <b><?php echo rePrint($_POST['final_card']); ?>(<b><?php echo rePrint($_POST['final_card_valid']); ?></b>)</b>
                </td>
              </tr>

              <tr>
                <td colspan="2">
                  Telephone
                  <br />
                  <b><?php echo rePrint($_POST['final_phone']); ?></b>
                </td>
                <td colspan="2">
                  Email / <i class="as">Surel</i>
                  <br />
                  <b><?php echo rePrint($_POST['final_email']); ?></b>
                </td>
                <td colspan="2">
                  State / <i class="as">Kedaerahan</i>
                  <br />
                  <b><?php echo rePrint($_POST['final_kabupaten']); ?></b>
                </td>
              </tr>

              <tr>
                <td colspan="2">
                  VIP
                  <br />
                  <b><?php echo (rePrint($_POST['final_vip']) === 'Y') ? "Yes" : "No"; ?></b>
                </td>
                <td colspan="2">
                  Date of Birth / <i class="as">Tgl. Lahir</i>
                  <br />
                  <b><?php echo rePrint($_POST['final_tgl_lahir']); ?></b>
                </td>
                <td colspan="2">
                  Company / <i class="as">Perusahaan</i>
                  <br />
                  <b><?php echo rePrint($_POST['final_company']); ?></b>
                </td>
              </tr>

              <tr>
                <td colspan="6">
                </td>
              </tr>

              <tr>
                <td colspan="6">
                  Mode of Payment / <i class="as"> Pembayaran</i>
                  <br />
                  <div class="row">
                    <div class="col-3 boxes-optional">
                      <div>
                        <div class="boxes"></div> Cash / <i class="as">Tunai</i>
                      </div>
                    </div>
                    <div class="col-3 boxes-optional">
                      <div>
                        <div class="boxes"></div> American Express
                      </div>
                    </div>
                    <div class="col-3 boxes-optional">
                      <div>
                        <div class="boxes"></div> Travellers Cheque
                      </div>
                    </div>
                    <div class="col-3 boxes-optional">
                      <div>
                        <div class="boxes"></div>Others (Please Specify) / <i class="as">Lain-lain</i>
                      </div>
                      <div style="position: absolute; top: 20px; height: 40px; width: 100%; border-bottom: solid 1px #000;"></div>
                    </div>
                    <div class="row">
                      <div class="col-3 boxes-optional">
                        <div>
                          <div class="boxes"></div> Visa Card
                        </div>
                      </div>
                      <div class="col-3 boxes-optional">
                        <div>
                          <div class="boxes"></div> BCA Card
                        </div>
                      </div>
                      <div class="col-3 boxes-optional">
                        <div>
                          <div class="boxes"></div> Voucher
                        </div>
                      </div>
                      <div class="col-3 boxes-optional">
                        <div>
                          <div style="visibility: hidden" class="boxes"></div>
                        </div>
                      </div>
                      <div class="col-3 boxes-optional">
                        <div>
                          <div class="boxes"></div> Master Card
                        </div>
                      </div>
                      <div class="col-3 boxes-optional">
                        <div>
                          <div class="boxes"></div> JCB Card
                        </div>
                      </div>
                      <div class="col-3 boxes-optional">
                        <div>
                          <div class="boxes"></div> Company Acct. / <i class="as">Perusahaan</i>
                        </div>
                      </div>
                    </div>
                </td>
              </tr>
              <tr>
                <td colspan="6">
                  <h5>Dear Guest, Please note the following terms and condition :</h5>
                  <ul class="rule-list">
                    <li>Check-In time starts at 2pm and Check-Out time is 12 noon.</li>
                    <li>Hotel provide a safety box in your room to keep money, jewels or any other valuables.</li>
                    <li>Room rate are subject to 21% service charge and prevailing goverment tax.</li>
                    <li>Smoking is prohibited in all non-smoking floors;penalty of IDR 1.000,000 will be applied on your room folio if smoking evident found.</li>
                    <li>You agree to forfeit your deposit if smoking in non-smoking room, some damages found, there are some missing room items and/or Hotel will hold back deposit until at a later time after check-out.</li>
                    <li>Hotel cannot be sued legally for accidents/injury caused by guest’s negligence. The hotel will provide assistance in accordance with the SOP in force at the hotel.</li>
                    <li>Hotel is guaranteed for a noise-free from Hotel’s activities in your room, otherwise, Hotel will inform Guest in advance.</li>
                    <li>Cash Guest Deposit can only be collected at check-out and can only be requested by registered Guest(s); NO exceptions.</li>
                    <li>My signatures is an authorization for the hotel to use a non-cash method for the payment of my account.</li>
                  </ul>

                  <h5>Tamu kami terhormat, harap perhatikan syarat dan ketentuan di bawah ini</h5>
                  <ul class="rule-list-indo">
                    <li><i>Waktu masuk hotel mulai jam 2 siang dan waktu keluar hotel jam 12 siang.</i></li>
                    <li><i>Hotel menyediakan brankas di dalam kamar anda untuk menyimpan uang, perhiasan atau barang berharga lainnya.</i></li>
                    <li><i>Tarif kamar belum termasuk 21% biaya pelayanan dan pajak pemerintah.</i></li>
                    <li><i>Merokok di dalam kamar bebas-rokok tidak diperkenakan; denda sebesar Rp 1,000,000 akan dibebankan kedalam tagihan kamar anda apabila terbukti ditemukan.</i></li>
                    <li><i>Anda telah setuju untuk pengurangan deposit dan/atau menunda pengembalian deposit apabila ditemukan merokok di kamar bebas-rokok, kerusakan kehilangan barang kamar.</i></li>
                    <li><i>Hotel tidak dapat di tuntut secara hukum untuk kecelakaan/cidera yang bukan disebabkan oleh kesalahan pihak hotel. Pihak hotel akan berikan asistensi sesuai SOP yang berlaku di hotel.</i></li>
                    <li><i>Hotel di jamin bebas kebisingan dari semua kegiatan hotel, apabila ada kegiatan, Hotel akan memberikan informasi ke tamu terlebih dahulu.</i></li>
                    <li><i>Uang deposit hanya dapat di ambil pada saat pendaftaran keluar dan hanyatamu terdaftar yang berhak mengambil.Tidak ada pengecualian.</i></li>
                    <li><i>Tanda tangan saya adalah otorisasi bagi hotel pada saat pembayaran tagihan dengan menggunakan metode pembayaran non-tunai.</i></li>
                  </ul>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-4">
            <table class="table table-form">
              <tr>
                <td>
                  Hotel Use Only / <i class="as">Hanya diisi oleh petugas hotel</i>
                </td>
              </tr>
              <tr>
                <td style="height: 50px;">
                  Remarks / <i class="as">Keterangan</i>
                </td>
              </tr>
              <tr>
                <td>
                  Front Officer / <i class="as">Petugas Hotel</i>
                  <br />
                  <b><?php echo rePrint($_POST['__ME__']); ?></b>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-6">
            <br />
            <div style="margin: 0 20px;">
              <span style="border-bottom: solid 1px #000;">Guest Signature for Registration</span>
              <i class="as">Tanda Tangan Tamu Pendaftar</i>
            </div>
          </div>
          <div class="col-6">
            <br />
            <div style="margin: 0 10px;">
              <span style="border-bottom: solid 1px #000;">I Agree for penalty Rp. 1.000.000 for smoking/durian/pets</span>
              <i class="as">Saya setuju denda Rp. 1.000.000 apabila<br />Merokok/membawa durian & hewan</i>
            </div>
          </div>
          <div class="col-12" style="margin-bottom: 10px; border-bottom: solid 1px #000;">
            <br />
            <span>Regardless of charge instructions, I hereby acknowledge to be Personally responsible for the payment of accounts</span>
            <i class="as">
              Dengan memahami instruksi penagihan yang ada, saya mengetahui bahwa saya pribadi akan bertanggungjawab atas seluruh pembayaran
            </i>
          </div>
          <div class="col-12 text-center">
            <span class="text-center" style="font-size: 6pt !important; letter-spacing: -1px">
              <?php echo (isset($_POST['__PC_CUSTOMER__'])) ? $_POST['__PC_CUSTOMER__'] : 'CUSTOMER COMPANY FULL NAME'; ?>
            </span>
            <small class="header-information text-center">
              <center>
                T.No. <?php echo (isset($_POST['__PC_CUSTOMER_CONTACT__'])) ? $_POST['__PC_CUSTOMER_CONTACT__'] : '-'; ?> | E: <?php echo $_POST['__PC_CUSTOMER_EMAIL__']; ?> | W: <?php echo $_POST['__PC_CUSTOMER_SITE__']; ?>
              </center>
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>