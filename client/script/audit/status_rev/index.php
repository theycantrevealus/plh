<script type="text/javascript">
  $(function() {
    var selectedRate = "";
    var selectedPost = "";
    var selectedFolio = "";
    var DT = $("#table-reservasi-status").DataTable({
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
        url: __HOSTAPI__ + "/Reservasi",
        type: "POST",
        headers: {
          Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
        },
        data: function(d) {
          d.request = "reservasi_list";
          d.paramSet = "today";
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
      "rowCallback": function(row, data, index) {
        console.log(data.status_rev);
        $("td .edit-status", row).val(data.status_rev);
      },
      "columns": [{
          "data": null,
          render: function(data, type, row, meta) {
            return "<span class=\"autonum\">" + row.autonum + " " + ((row.vip === "Y") ? "<i class=\"pull-right fa fa-star text-warning\"></i>" : "") + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            var company = "<span class=\"text-info\">" + ((row.company !== undefined && row.company !== null) ? row.company.kode + " - " + row.company.nama : "") + "</span>";
            return "<b class=\"wrap_content\">" + row.nama_depan + " " + row.nama_belakang + "</b><br />" + company;
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span class=\"wrap_content\">" + row.check_in + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span class=\"wrap_content\">" + row.check_out + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
              "<select id=\"status_" + row.uid + "\" class=\"form-control edit-status\" style=\"width: 200px;\">" +
              "<option value=\"\">-- Pilih Status --</option>" +
              "<option value=\"C\">Cancel</option>" +
              "<option value=\"N\">NO Show</option>" +
              "</select>" +
              "</div>";
          }
        }
      ]
    });

    $("body").on("change", ".edit-status", function() {
      var id = $(this).attr("id").split("_");
      id = id[id.length - 1];
      var status = $(this).val();
      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Reservasi",
        type: "POST",
        data: {
          request: "change_status",
          uid: id,
          status: status
        },
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        success: function(response) {
          if (response.response_package.response_result > 0) {
            DT.ajax.reload();
          }
        }
      });
    });

    $("#txt_rate_value").inputmask({
      alias: 'decimal',
      rightAlign: true,
      placeholder: "0.00",
      prefix: "",
      groupSeparator: ".",
      autoGroup: false,
      digitsOptional: true
    });

    $("body").on("click", ".btn-post", function() {
      var id = $(this).attr("id").split("_");
      id = id[id.length - 1];

      selectedPost = id;

      var rate_code = $(this).attr("rate-code");
      var rate_value = $(this).attr("rate-value");
      var rate_uid = $(this).attr("rate-uid");
      var folio = $(this).attr("folio");
      selectedRate = rate_uid;
      selectedFolio = folio;

      $("#txt_rate_code").val(rate_code);
      $("#txt_rate_value").inputmask("setvalue", rate_value);
      $("#txt_folio").val($("#folio_" + id).html());
      $("#txt_guest").val($("#guest_" + id).html());
      var arrival = $("#check_in_" + id).val().split(":");
      var departure = $("#check_out_" + id).val().split(":");

      $("#txt_arrival").val(arrival[0] + ":" + arrival[1]);
      $("#txt_departure").val(departure[0] + ":" + departure[1]);

      $("#form-roomposting").modal("show");
    });

    $("#txt_rate_code").focus(function() {
      $("#pick-rate").modal("show");
    });

    $("#txt_rate_code").click(function() {
      $("#pick-rate").modal("show");
    });

    $("#table-ratez").DataTable({
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
        url: __HOSTAPI__ + "/Code",
        type: "POST",
        headers: {
          Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
        },
        data: function(d) {
          d.request = "rate_code_list";
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
      "rowCallback": function(row, data, index) {
        var harga = parseFloat(data.harga);
        for (const a in data.detail) {
          harga += parseFloat(data.detail[a].harga);
        }

        $("td .harga_set", row).html(number_format(harga, 2, ".", ",")).attr("id", "harga_rate_" + data.uid);
      },
      "columns": [{
          "data": null,
          render: function(data, type, row, meta) {
            return "<a href=\"#\" class=\"pilih_rate\" id=\"kode_" + row.uid + "\">" + row.kode + "</a>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            var additional = "";
            for (const a in row.detail) {
              if (parseFloat(row.detail[a].harga) > 0) {
                additional += "<div style=\"margin: 1px;\" class=\"badge badge-outline-info badge-custom-caption\">" + row.detail[a].kode_add + " - " + row.detail[a].nama_add + "</div>";
              }
            }
            return additional;
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span class=\"harga_set\"></span>";
          }
        },
      ]
    });

    $("body").on("click", ".pilih_rate", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];

      selectedRate = uid;

      $("#txt_rate_code").val($(this).html());
      $("#txt_rate_value").val($("#harga_rate_" + uid).html());

      $("#pick-rate").modal("hide");
    });

    $("#btnProsesPost").click(function() {
      var rate_value = $("#txt_rate_value").inputmask("unmaskedvalue");
      var remark = $("#txt_remark").val();
      Swal.fire({
        title: "Proses Posting?",
        showDenyButton: true,
        confirmButtonText: "Ya",
        denyButtonText: "Batal",
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            async: false,
            url: __HOSTAPI__ + "/RPost",
            type: "POST",
            data: {
              request: "add_posting",
              uid: selectedPost,
              rate_code: selectedRate,
              rate_value: rate_value,
              folio: selectedFolio,
              remark: remark
            },
            beforeSend: function(request) {
              request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response) {
              console.log(response);
              if (response.response_package.response_result > 0) {
                DT.ajax.reload();
                selectedPost = "";
                selectedRate = "";
                $("#txt_rate_code").val("");
                $("#txt_rate_value").val("");
                $("#form-roomposting").modal("hide");
              }
            }
          });
        }
      });
    });
  });
</script>
<div id="form-roomposting" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Room Posting</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-4 form-group">
            <label for="txt_folio">Folio</label>
            <input type="text" class="form-control" id="txt_folio" readonly />
          </div>
          <div class="col-8 form-group">
            <label for="txt_guest">Guest</label>
            <input type="text" class="form-control" id="txt_guest" readonly />
          </div>
          <div class="col-6 form-group">
            <label for="txt_arrival">Arrival</label>
            <input type="text" class="form-control" id="txt_arrival" readonly />
          </div>
          <div class="col-6 form-group">
            <label for="txt_departure">Departure</label>
            <input type="text" class="form-control" id="txt_departure" readonly />
          </div>
          <div class="col-6 form-group">
            <label for="txt_rate_code">Rate Code</label>
            <input type="text" class="form-control" id="txt_rate_code" />
          </div>
          <div class="col-6 form-group">
            <label for="txt_rate_value">Rate value</label>
            <input type="text" class="form-control" id="txt_rate_value" />
          </div>
          <div class="col-12 form-group">
            <label for="txt_remark">Remark</label>
            <textarea class="form-control" id="txt_remark"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
        <button type="button" class="btn btn-info" id="btnProsesPost">Posting</button>
      </div>
    </div>
  </div>
</div>



<div id="pick-rate" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Pilih Rate</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="table-ratez" class="table">
          <thead class="thead-dark">
            <tr>
              <th class="wrap_content">Rate</th>
              <th class="wrap_content">Harga</th>
              <th>Additional Code</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>