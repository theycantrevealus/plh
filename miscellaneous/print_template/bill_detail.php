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
      font-size: 12pt !important;
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

    .largeDataType tbody tr:nth-child(even) td {
      background: #f2f2f2;
    }

    .largeDataType thead tr th {
      border-bottom: solid 1px #000;
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
          <td style="position: relative; width: 50%;">
            <img src="http://<?php echo $_SERVER['SERVER_ADDR'] ?>/hotel/client/template/assets/images/clients/logo-text-black-<?php echo $_POST['__PC_IDENT__']; ?>.png" class="logo" />
          </td>
          <td>
            <span class="text-right" style="display: block">
              <?php echo (isset($_POST['__PC_CUSTOMER__'])) ? $_POST['__PC_CUSTOMER__'] : 'CUSTOMER COMPANY FULL NAME'; ?>
              <?php echo (isset($_POST['__PC_CUSTOMER_ADDRESS__'])) ? $_POST['__PC_CUSTOMER_ADDRESS__'] : 'CUSTOMER ADDRESS'; ?><br />
              Phone: <?php echo (isset($_POST['__PC_CUSTOMER_CONTACT__'])) ? $_POST['__PC_CUSTOMER_CONTACT__'] : '085261510202'; ?><br />
              Email : <?php echo $_POST['__PC_CUSTOMER_EMAIL__']; ?><br />
              Website: <?php echo $_POST['__PC_CUSTOMER_SITE__']; ?><br />
            </span>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="text-center">
            <h4 class="text-center">Guest Billing</h4>
          </td>
        </tr>
        <tr>
          <td>
            <span><?php echo rePrint($_POST['guest']); ?></span><br />
            <span><?php echo rePrint($_POST['address']); ?></span>
          </td>
          <td>
            <table>
              <tr>
                <td>Invoice</td>
                <td>
                  <span><?php echo rePrint($_POST['folio']); ?></span>
                </td>
              </tr>
              <tr>
                <td>Room</td>
                <td>
                  <span><?php echo rePrint($_POST['kamar']); ?></span>
                </td>
              </tr>
              <tr>
                <td>Arrival</td>
                <td>
                  <span><?php echo rePrint($_POST['check_in']); ?></span>
                </td>
              </tr>
              <tr>
                <td>Departure</td>
                <td>
                  <span><?php echo rePrint($_POST['check_out']); ?></span>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
    <div class="report_content">
      <div>
        <div class="row">
          <div class="col-12">
            <table class="table largeDataType">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Code</th>
                  <th style="width: 40%;">Deskripsi</th>
                  <th>Debit</th>
                  <th>Credit</th>
                  <th>Balance</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $dataSet = $_POST['finalData'];
                $balance = 0;
                foreach ($dataSet as $key => $value) {
                  $kredit = floatval($value['credit']);
                  $debet = floatval($value['charge']);

                  if ($value['tax'] === 'Y' && $value['service'] === 'Y') {
                    $kCap = $kredit / 1.21;
                    $dCap = $debet / 1.21;
                    $fTkCap = $kredit / 1.21 * 11 / 100;
                    $fTdCap = $debet / 1.21 * 11 / 100;

                    $fSkCap = $kredit / 1.21 * 10 / 100;
                    $fSdCap = $debet / 1.21 * 10 / 100;
                  } else if ($value['tax'] === 'N' && $value['service'] === 'Y') {
                    $kCap = $kredit / 1.1;
                    $dCap = $debet / 1.1;
                    $fTkCap = 0;
                    $fTdCap = 0;
                    $fSkCap = $kredit / 1.1 * 10 / 100;
                    $fSdCap = $debet / 1.1 * 10 / 100;
                  } else if ($value['tax'] === 'Y' && $value['service'] === 'N') {
                    $kCap = $kredit / 1.1;
                    $dCap = $debet / 1.1;
                    $fTkCap = $kredit / 1.1 * 10 / 100;
                    $fTdCap = $debet / 1.1 * 10 / 100;
                    $fSkCap = 0;
                    $fSdCap = 0;
                  } else {
                    $kCap = $kredit;
                    $dCap = $debet;
                    $fTkCap = 0;
                    $fTdCap = 0;
                    $fSkCap = 0;
                    $fSdCap = 0;
                  }

                  $div = abs((floatval($kCap) > 0 || floatval($kCap) < 0) ? $kCap : $dCap);
                  if ($key < 1) {
                    $balance = $div;
                  } else {
                    if ((floatval($kCap) > 0 || floatval($kCap) < 0)) {
                      $balance -= $div;
                    } else {
                      $balance -= $div;
                    }
                  }

                ?>
                  <tr>
                    <td><?php echo $value['date']; ?></td>
                    <td><?php echo $value['trans']; ?></td>
                    <td><?php echo $value['desc']; ?></td>
                    <td class="number_style"><?php echo (floatval($dCap) !== 0) ? (($dCap >= 0) ? number_format($dCap, 2, ".", ",") : '(' . number_format(abs($dCap), 2, ".", ",") . ')') : ''; ?></td>
                    <td class="number_style"><?php echo (floatval($kCap) !== 0) ? (($kCap >= 0) ? number_format($kCap, 2, ".", ",") : '(' .  number_format(abs($kCap), 2, ".", ",") . ')') : ''; ?></td>
                    <td class="number_style"><?php echo ($balance > 0) ? number_format($balance, 2, ".", ",") : '(' . number_format(abs($balance), 2, ".", ",") . ')'; ?></td>
                  </tr>
                  <?php
                  $div = (floatval($fTdCap) > 0 || floatval($fTdCap) < 0) ? $fTdCap : $fTkCap;
                  $balance -= abs($div)
                  ?>
                  <tr>
                    <td></td>
                    <td>Tax</td>
                    <td></td>
                    <td class="number_style"><?php echo (floatval($fTdCap) !== 0) ? (($fTdCap >= 0) ? number_format($fTdCap, 2, ".", ",") : '(' . number_format(abs($fTdCap), 2, ".", ",") . ')') : ''; ?></td>
                    <td class="number_style"><?php echo (floatval($fTkCap) !== 0) ? (($fTkCap >= 0) ? number_format($fTkCap, 2, ".", ",") : '(' . number_format(abs($fTkCap), 2, ".", ",") . ')') : ''; ?></td>
                    <td class="number_style"><?php echo ($balance > 0) ? number_format($balance, 2, ".", ",") : '(' . number_format(abs($balance), 2, ".", ",") . ')'; ?></td>
                  </tr>
                  <?php
                  $div = (floatval($fSdCap) > 0 || floatval($fSdCap) < 0) ? $fSdCap : $fSkCap;
                  $balance -= abs($div)
                  ?>
                  <tr>
                    <td></td>
                    <td>Service</td>
                    <td></td>
                    <td class="number_style"><?php echo (floatval($fSdCap) !== 0) ? (($fSdCap >= 0) ? number_format($fSdCap, 2, ".", ",") : '(' . number_format(abs($fSdCap), 2, ".", ",") . ')') : ''; ?></td>
                    <td class="number_style"><?php echo (floatval($fSkCap) !== 0) ? (($fSkCap >= 0) ? number_format($fSkCap, 2, ".", ",") : '(' . number_format(abs($fSkCap), 2, ".", ",") . ')') : ''; ?></td>
                    <td class="number_style"><?php echo ($balance > 0) ? number_format($balance, 2, ".", ",") : '(' . number_format(abs($balance), 2, ".", ",") . ')'; ?></td>
                  </tr>
                <?php
                  $balance = abs($balance);
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="5" class="text-right">
                    Total Charges
                  </td>
                  <td class="number_style"><?php echo number_format(abs($_POST['total_charges']), 2, ".", ",") ?></td>
                </tr>
                <tr>
                  <td colspan="5" class="text-right">
                    Advance Deposites Received
                  </td>
                  <td class="number_style"><?php echo number_format(abs($_POST['adv_dep']), 2, ".", ",") ?></td>
                </tr>
                <tr>
                  <td colspan="5" class="text-right">
                    Return Deposites
                  </td>
                  <td class="number_style"><?php echo (floatval($_POST['ret_dep']) > 0) ? number_format(abs($_POST['ret_dep']), 2, ".", ",") : '(' . number_format(abs($_POST['ret_dep']), 2, ".", ",") . ')'; ?></td>
                </tr>
                <tr>
                  <td colspan="5" class="text-right">
                    Payment Received
                  </td>
                  <td class="number_style"><?php echo number_format(abs($_POST['pay_received']), 2, ".", ","); ?></td>
                </tr>
                <tr>
                  <td colspan="5" class="text-right">
                    Net Balance Due
                  </td>
                  <td class="number_style"><?php echo number_format(abs($_POST['net_balance']), 2, ".", ","); ?></td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class="col-12">
            <span>Printed by : <?php echo rePrint($_POST['__ME__']); ?> (<?php echo date('d F Y, H:i'); ?>)</span>
            <p class="text-center">"I AGREE THAT I AM PERSONALLY LIABLE FOR THE PAYMENT OF THE FOLLOWING STATEMENT AND IF THE<br />
              PERSON, COMPANY OR ASSOCIATION INDICATED BY ME AS BEING RESPONSIBLE FOR PAYMENT OF THE<br />
              SAME DOES NOT DO SO. THAT MY LIABILITY FOR SUCH PAYMENT SHALL BE JOINT AND SEVERAL WITH SUCH<br />
              PERSON, COMPANY OR ASSOCIATION"</p>
            <br />
            <br />
            <h5 class="text-center">
              Signature ______________________<br />
              <br />
              <br />
              Thank you for staying with us<br />
              <?php echo (isset($_POST['__PC_CUSTOMER__'])) ? $_POST['__PC_CUSTOMER__'] : 'CUSTOMER COMPANY FULL NAME'; ?>
            </h5>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>