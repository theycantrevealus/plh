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

    .pad_1 {
      padding-left: 50px !important;
    }

    .pad_2 {
      padding-left: 100px !important;
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
            <h4 class="text-center">Daily Sales Report</h4>
          </td>
        </tr>
      </table>
    </div>
    <div class="report_content">
      <div>
        <div class="row">
          <div class="col-12">
            <?php echo $_POST['report']; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>