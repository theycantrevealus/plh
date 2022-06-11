<script type="text/javascript">
  $(function() {
    var DT = $("#table-closing").DataTable({
      processing: true,
      serverSide: true,
      sPaginationType: "full_numbers",
      bPaginate: true,
      lengthMenu: [
        [20, 50, -1],
        [20, 50, "All"]
      ],
      serverMethod: "POST",
      "ajax": {
        url: __HOSTAPI__ + "/Closing",
        type: "POST",
        headers: {
          Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
        },
        data: function(d) {
          d.request = "list_closing";
        },
        dataSrc: function(response) {
          var returnData = [];
          var rawData = [];
          if (
            response === undefined ||
            response === null ||
            response.response_package === undefined ||
            response.response_package === null) {
            rawData = [];
            response.draw = 1;
            response.recordsTotal = 0;
            response.recordsFiltered = 0;
          } else {
            rawData = response.response_package.response_data;
            for (var polKey in rawData) {
              returnData.push(rawData[polKey]);
            }

            response.draw = parseInt(response.response_package.response_draw);
            response.recordsTotal = response.response_package.recordsTotal;
            response.recordsFiltered = response.response_package.recordsFiltered;
          }
          return returnData;
        }
      },
      autoWidth: false,
      aaSorting: [
        [0, "asc"]
      ],
      "columnDefs": [{
        "targets": 0,
        "className": "dt-body-left"
      }],
      "columns": [{
          "data": null,
          render: function(data, type, row, meta) {
            return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span class=\"wrap_content\" id=\"waktu_" + row.uid + "\">" + row.created_at + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span id=\"nama_" + row.uid + "\">" + row.nama + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
              "<button id=\"detail_" + row.uid + "\" class=\"btn btn-info btn-sm btn-detail\">" +
              "<span><i class=\"fa fa-eye\"></i> View</span>" +
              "</button>" +
              "</div>";
          }
        }
      ]
    });

    $("body").on("click", ".btn-detail", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];
      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Closing/closing_detail/" + uid,
        type: "GET",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        success: function(response) {
          var data = response.response_package.response_data[0];
          var metaData = JSON.parse(data.meta_data);
          $("#modal-closing").modal("show");
          parse_closing(metaData);
        },
        error: function(response) {}
      });
    });

    function parse_closing(data) {
      var outletNett = 0;
      var outlet = data.outlet;
      for (var b in outlet) {
        var categories = outlet[b]['categories'];
        if (!Array.isArray(categories)) {
          $("#outlet-container").append("<tr><td class=\"pad_1\">" + outlet[b].kode + "-" + outlet[b].nama + "</td><td></td></tr>");

          for (var c in categories) {
            outletNett += categories[c].total;
            $("#outlet-container").append("<tr><td class=\"pad_2\">" + categories[c].nama + "</td><td class=\"number_style\">" + number_format(categories[c].total, 2, ".", ",") + "</td></tr>");
          }
        }
      }
      for (var a in data) {
        if (data[a] === undefined) {
          if (parseFloat(data[a]) > 0) {
            $("#cap_" + a).attr({
              valset: 0
            }).html(0).addClass("text-info").removeClass("text-muted");
          } else {
            $("#cap_" + a).attr({
              valset: 0
            }).html(0).addClass("text-muted").removeClass("text-info");
          }
        } else {
          if (parseFloat(data[a]) > 0) {
            $("#cap_" + a).attr({
              valset: data[a]
            }).html(number_format(data[a], 2, ".", ",")).addClass("text-info").removeClass("text-muted");
          } else {
            if (parseFloat(data[a]) < 0) {
              $("#cap_" + a).attr({
                valset: data[a]
              }).html("(" + number_format(Math.abs(data[a]), 2, ".", ",") + ")").addClass("text-danger").removeClass("text-info");
            } else {
              $("#cap_" + a).attr({
                valset: data[a]
              }).html(number_format(data[a], 2, ".", ",")).addClass("text-muted").removeClass("text-info");
            }
          }
        }
      }

      var outNett = outletNett / 1.21;
      $("#cap_outlet_revenue").html(number_format(outletNett, 2, ".", ","));
      $("#cap_outlet_tax").html(number_format(outletNett, 2, ".", ","));
      $("#cap_outlet_service").html(number_format(outNett * 10 / 100, 2, ".", ","));
      $("#cap_outlet_tax").html(number_format(outNett * 11 / 100, 2, ".", ","));
      $("#cap_outlet_revenue_nett").html(number_format(outNett, 2, ".", ","));
    }

    $("#btnClosingHarian").click(function() {
      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Closing",
        type: "POST",
        data: {
          request: 'calculate'
        },
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        success: function(response) {
          $("#modal-closing").modal("show");
          var data = response.response_package;
          parse_closing(data);
        }
      });
    });

    $("#btnProsesClosing").click(function() {
      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Closing",
        type: "POST",
        data: {
          request: 'proceed_close'
        },
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        success: function(response) {
          if (response.response_package.response_result > 0) {
            $("#modal-closing").modal("hide");
            DT.ajax.reload();
          } else {
            Swal.fire(
              "Closing",
              response.response_package.response_message,
              "warning"
            ).then((result) => {
              $("#modal-closing").modal("hide");
            });
          }
        },
        error: function(response) {}
      });
    });
  });
</script>
<div id="modal-closing" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Closing Harian</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-striped table-report">
          <thead class="thead-dark">
            <tr>
              <th>Parameter</th>
              <th class="wrap_content">Nilai</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="2">
                <b>Room</b>
              </td>
            </tr>
            <tr>
              <td class="pad_1">Room Available</td>
              <td class="number_style" id="cap_room_available">0</td>
            </tr>
            <tr>
              <td class="pad_1">Room Saleable</td>
              <td class="number_style" id="cap_room_saleable">0</td>
            </tr>
            <tr>
              <td class="pad_1">Room Sold</td>
              <td class="number_style" id="cap_room_sold">0</td>
            </tr>
            <tr>
              <td class="pad_1">Room Occupied</td>
              <td class="number_style" id="cap_room_occupied">0</td>
            </tr>
            <tr>
              <td class="pad_1">%Room Occupied</td>
              <td class="number_style" id="cap_room_occupied_per">0</td>
            </tr>
            <tr>
              <td class="pad_1">%Room Sold</td>
              <td class="number_style" id="cap_room_sold_per">0</td>
            </tr>
            <tr>
              <td class="pad_1">OO Room</td>
              <td class="number_style" id="cap_room_oo">0</td>
            </tr>
            <tr>
              <td class="pad_1">Number of Guest</td>
              <td class="number_style" id="cap_number_guest">0</td>
            </tr>
            <tr>
              <td class="pad_1">Compliment</td>
              <td class="number_style" id="cap_compliment">0</td>
            </tr>
            <tr>
              <td class="pad_1">Coorporate</td>
              <td class="number_style" id="cap_CPY">0</td>
            </tr>
            <tr>
              <td class="pad_1">Government</td>
              <td class="number_style" id="cap_GOV">0</td>
            </tr>
            <tr>
              <td class="pad_1">FIT</td>
              <td class="number_style" id="cap_FIT">0</td>
            </tr>
            <tr>
              <td class="pad_1">House Use</td>
              <td class="number_style" id="cap_house_use">0</td>
            </tr>
            <tr>
              <td class="pad_1">Mice</td>
              <td class="number_style" id="cap_MICE">0</td>
            </tr>
            <tr>
              <td class="pad_1">OTA</td>
              <td class="number_style" id="cap_OTA">0</td>
            </tr>
            <tr>
              <td class="pad_1">Travel Agent</td>
              <td class="number_style" id="cap_TA">0</td>
            </tr>
            <tr>
              <td class="pad_1">Cancel Reservation</td>
              <td class="number_style" id="cap_cancel">0</td>
            </tr>
            <tr>
              <td class="pad_1">No Show Reservation</td>
              <td class="number_style" id="cap_no_show">0</td>
            </tr>
            <tr>
              <td class="pad_1">Average Room Rate</td>
              <td class="number_style" id="cap_arr">0</td>
            </tr>
            <tr>
              <td class="pad_1">Room Sales</td>
              <td class="number_style" id="cap_room_sales">0</td>
            </tr>
            <tr>
              <td class="pad_1">Extra Bed</td>
              <td class="number_style" id="cap_extra_bed">0</td>
            </tr>
            <tr>
              <td class="pad_1">Other Revenue</td>
              <td class="number_style" id="cap_other_revenue">0</td>
            </tr>
            <tr>
              <td class="pad_1">Rebate</td>
              <td class="number_style" id="cap_rebate">0</td>
            </tr>
            <tr>
              <td class="pad_1"><b>Total Room Revenue</b></td>
              <td class="number_style" id="cap_room_revenue_total">0</td>
            </tr>
            <tr>
              <td class="pad_1"><b>Service</b></td>
              <td class="number_style" id="cap_service">0</td>
            </tr>
            <tr>
              <td class="pad_1"><b>Tax</b></td>
              <td class="number_style" id="cap_tax">0</td>
            </tr>
            <tr>
              <td class="pad_1"><b>Room Revenue Nett</b></td>
              <td class="number_style" id="cap_room_revenue_nett">0</td>
            </tr>
          </tbody>
          <tbody id="outlet-container">
            <tr>
              <td colspan="2">
                <b>Outlet</b>
              </td>
            </tr>
          </tbody>
          <tbody>
            <tr>
              <td class="pad_1"><b>Total Outlet Revenue</b></td>
              <td class="number_style" id="cap_outlet_revenue">0</td>
            </tr>
            <tr>
              <td class="pad_1"><b>Service</b></td>
              <td class="number_style" id="cap_outlet_service">0</td>
            </tr>
            <tr>
              <td class="pad_1"><b>Tax</b></td>
              <td class="number_style" id="cap_outlet_tax">0</td>
            </tr>
            <tr>
              <td class="pad_1"><b>Total Outlet Revenue Nett</b></td>
              <td class="number_style" id="cap_outlet_revenue_nett">0</td>
            </tr>
          </tbody>
          <tbody>
            <tr>
              <td colspan="2">
                <b>Payment</b>
              </td>
            </tr>
            <tr>
              <td class="pad_1"><b>Cash Deposite</b></td>
              <td class="number_style" id="cap_cash_deposite">0</td>
            </tr>
            <tr>
              <td class="pad_1"><b>Cash Receipt</b></td>
              <td class="number_style" id="cap_receipt">0</td>
            </tr>
            <tr>
              <td class="pad_1"><b>Cash Refund</b></td>
              <td class="number_style" id="cap_refund">0</td>
            </tr>
            <tr>
              <td class="pad_1"><b>City Ledger</b></td>
              <td class="number_style" id="cap_city_ledger">0</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
        <button type="button" class="btn btn-success" id="btnProsesClosing">Proses</button>
      </div>
    </div>
  </div>
</div>