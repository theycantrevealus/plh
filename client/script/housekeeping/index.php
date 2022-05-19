<script type="text/javascript">
  $(function() {
    var selectedKamar = "";
    var selectedStatus = "";
    var DT = $("#table-status-kamar").DataTable({
      processing: true,
      serverSide: true,
      sPaginationType: "full_numbers",
      bPaginate: true,
      lengthMenu: [
        [20, 50, -1],
        [20, 50, "All"]
      ],
      serverMethod: "POST",
      "rowCallback": function(row, data, index) {
        if (data.status.trim() === 'VC') {
          $("td", row).addClass("bg-success-custom");
        }
        if (data.status.trim() === 'OC') {
          $("td", row).addClass("bg-info-custom");
        }
        if (data.status.trim() === 'OD' || data.status.trim() === 'OC') {
          $("td .change-status-kamar option[value=\"VC\"]", row).remove();
          $("td .change-status-kamar option[value=\"VD\"]", row).remove();
        }
        if (data.status.trim() === 'VD' || data.status.trim() === 'VC') {
          $("td .change-status-kamar option[value=\"OC\"]", row).remove();
          $("td .change-status-kamar option[value=\"OD\"]", row).remove();
        }
        if (data.status.trim() === 'OD' || data.status.trim() === 'VD') {
          $("td", row).addClass("bg-warning-custom");
        }
        if (data.status.trim() === 'OO') {
          $("td", row).addClass("bg-danger-custom");
        }

        $("td .change-status-kamar option[value=\"" + data.status.trim() + "\"]", row).prop("selected", true);
      },
      "ajax": {
        url: __HOSTAPI__ + "/Kamar",
        type: "POST",
        headers: {
          Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
        },
        data: function(d) {
          d.request = "kamar_list_monitoring";
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
            return "<b class=\"wrap_content\" id=\"kamar_" + row.uid + "\">" + row.nomor + "</b>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span class=\"wrap_content\">" + row.status + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span class=\"wrap_content\">" + row.eaed + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span class=\"wrap_content\" id=\"tipe_" + row.uid + "\">" + row.nama + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<b class=\"wrap_content\">" + row.nama_depan + " " + row.nama_belakang + "</b>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span class=\"wrap_content\">" + row.pax + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span class=\"wrap_content\">" + row.check_in_remark + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<div class=\"wrap_content\" style=\"width: 120px\">" +
              "<select id=\"kamar_status_" + row.uid + "\" old-stat=\"" + row.status.trim() + "\" class=\"form-control change-status-kamar\">" +
              "<option value=\"VC\">Vacant Clean</option>" +
              "<option value=\"VD\">Vacant Dirty</option>" +
              "<option value=\"OC\">Occupied Clean</option>" +
              "<option value=\"OD\">Occupied Dirty</option>" +
              "<option value=\"OO\">Out Of Order</option>" +
              "</select>" +
              "</div>";
          }
        }
      ]
    });

    $("body").on("change", ".change-status-kamar", function() {
      var id = $(this).attr("id").split("_");
      id = id[id.length - 1];
      selectedKamar = id;
      var setVal = $(this).find("option:selected").val();
      var oldVal = $(this).attr("old-stat");
      if (setVal != oldVal) {
        $("#txt_remark").val("");
        selectedStatus = setVal;
        $("#txt_kamar").val($("#kamar_" + id).html());
        $("#txt_kamar_tipe").val($("#tipe_" + id).html());
        $("#change-status").modal("show");
        $("#status-set").html(oldVal + " <i class=\"fa fa-arrow-right\"></i> " + setVal);
      }
    });

    $("#btnProsesStatus").click(function() {
      Swal.fire({
        title: "Ubah Status Kamar?",
        showDenyButton: true,
        confirmButtonText: "Ya",
        denyButtonText: "Belum",
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            async: false,
            url: __HOSTAPI__ + "/Kamar",
            type: "POST",
            data: {
              request: 'ubah_status',
              kamar: selectedKamar,
              status: selectedStatus,
              remark: $("#txt_remark").val()
            },
            beforeSend: function(request) {
              request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response) {
              if (response.response_package.response_result > 0) {
                selectedKamar = "";
                selectedStatus = "";
                $("#change-status").modal("hide");
                DT.ajax.reload();
              }
            }
          });
        }
      });
    });
  });
</script>

<div id="change-status" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Ubah Status Kamar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-6 form-group">
            <label for="txt_kamar">Kamar</label>
            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_kamar" readonly>
          </div>
          <div class="col-6 form-group">
            <label for="txt_kamar_tipe">Tipe</label>
            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_kamar_tipe" readonly>
          </div>
          <div class="col-12 text-center">
            <br />
            <h5 id="status-set"></h5>
          </div>
          <div class="col-12 form-group">
            <label for="txt_remark">Remark</label>
            <textarea id="txt_remark" style="min-height: 150px;" class="form-control"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
        <button type="button" class="btn btn-success" id="btnProsesStatus">Prosess</button>
      </div>
    </div>
  </div>
</div>