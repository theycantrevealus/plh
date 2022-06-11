<html>

<head>
  <style type='text/css'>
    @media print {
      @page {
        size: 80mm 10cm;
        margin: 0;
        padding: 0;
      }
    }

    body {
      font-family: "Courier New" !important;
      height: auto;
      margin: auto 0px;
      color: #000;
      font-size: 0.3rem;
      text-align: center;
      padding: 20px;
    }

    .number_style {
      text-align: right;
      font-size: 6pt !important;
      font-family: "Courier New" !important;
    }

    hr {
      border: 1px dashed #F2F2F2;
    }
  </style>
  <script type="text/javascript">
    function myFunction() {
      window.print();
      setTimeout(window.close, 0);
    }
  </script>
</head>

<body>
  <table style='width:100%;border-collapse:collapse; background:#fff;'>
    <tr>
      <td colspan="2" style="border-bottom: dashed 1px #000; text-align: center;"></td>
    </tr>
    <tr>
      <td colspan="2" style="border-bottom: dashed 1px #000; padding: 10px 0; text-align: center;">
        <h3><?php echo $_POST['outlet'] ?></h3>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="padding: 10px 0; text-align: center;">
        <h4>***RECEIPT***</h4>
      </td>
    </tr>
    <tr>
      <td style="text-align: left;">
        <small>CSH:<br /><?php echo $_POST['__ME__']; ?></small>
      </td>
      <td style="text-align: left;">
        <small><?php echo date('d/m/Y'); ?><br /><?php echo date('H:i'); ?></small>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="border-bottom: dashed 1px #000;"></td>
    </tr>
  </table>
  <table style='width:100%;border-collapse:collapse; background:#fff; text-align: left;'>
    <thead>
      <tr>
        <th style="padding: 8px;">Item</th>
        <th style="padding: 8px;">@</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($_POST['item'] as $key => $value) {
      ?>
        <tr>
          <td><?php echo $value['qty'] ?>&times; <?php echo $value['item']; ?></td>
          <td class="number_style"><?php echo $value['sub']; ?></td>
        </tr>
      <?php
      }
      ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2"><br /></td>
      </tr>
      <tr>
        <td style="font-size: .5rem; text-align: right"><b>Tax(10%)</b></td>
        <td class="number_style"><?php echo $_POST['tax']; ?></td>
      </tr>
      <tr>
        <td style="font-size: .5rem; text-align: right"><b>Service(11%)</b></td>
        <td class="number_style"><?php echo $_POST['service']; ?></td>
      </tr>
      <tr>
        <td style="font-size: .5rem; text-align: right"><b>TOTAL AMOUNT</b></td>
        <td class="number_style"><?php echo $_POST['total']; ?></td>
      </tr>
      <?php
      if (isset($_POST['cash'])) {
      ?>
        <tr>
          <td style="font-size: .5rem; text-align: right"><b>CASH</b></td>
          <td class="number_style"><?php echo number_format($_POST['cash'], 2, ".", ","); ?></td>
        </tr>
      <?php
      }
      ?>
      <?php
      if (isset($_POST['change'])) {
      ?>
        <tr>
          <td style="font-size: .5rem; text-align: right"><b>CHANGE</b></td>
          <td class="number_style"><?php echo number_format($_POST['change'], 2, ".", ","); ?></td>
        </tr>
      <?php
      }
      ?>
      <tr>
        <td colspan="2"><br /></td>
      </tr>
      <?php
      if (isset($_POST['bank_card']) && !empty($_POST['bank_card'])) {
      ?>
        <tr>
          <td style="font-size: .5rem;">
            <b>Bank Card</b><br />
            <small><?php echo $_POST['back_name'] ?></small><br />
            <?php echo $_POST['bank_card']; ?>
          </td>
          <td class="number_style">
            <?php echo number_format($_POST['bank_value'], 2, ".", ","); ?>
          </td>
        </tr>
      <?php
      }
      ?>
      <?php
      if (isset($_POST['gl']) && !empty($_POST['gl']) && floatval($_POST['gl_value']) > 0) {
      ?>
        <tr>
          <td style="font-size: .5rem;">
            <b>Guest Ledger</b><br />
            <?php echo $_POST['gl']; ?>
          </td>
          <td class="number_style">
            <?php echo number_format($_POST['gl_value'], 2, ".", ","); ?>
          </td>
        </tr>
      <?php
      }
      ?>
      <?php
      if (isset($_POST['cl']) && !empty($_POST['cl']) && floatval($_POST['cl_value']) > 0) {
      ?>
        <tr>
          <td style="font-size: .5rem;">
            <b>City Ledger</b><br />
            <?php echo $_POST['cl']; ?>
          </td>
          <td class="number_style">
            <?php echo number_format($_POST['cl_value'], 2, ".", ","); ?>
          </td>
        </tr>
      <?php
      }
      ?>
    </tfoot>
  </table>
  <hr />
  <span style="text-align: center;"><b>Terima Kasih. Datang Kembali</b></span>
</body>

</html>