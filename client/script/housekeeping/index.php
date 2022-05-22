<script type="text/javascript">
  $(function() {
    var selectedKamar = "";
    var selectedStatus = "";
    var lostandfound;
    var modeLost = "tambah";
    var selectedIdLost = 0;
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
            return "<a href=\"#\" class=\"detail_kamar\" id=\"detail_" + row.uid + "\"><b class=\"wrap_content\" id=\"kamar_" + row.uid + "\">Kamar #" + row.nomor + "</b></a>";
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

    $("body").on("click", ".detail_kamar", function() {
      var id = $(this).attr("id").split("_");
      id = id[id.length - 1];

      selectedKamar = id;

      $("#txt_lost_kamar").val($("#kamar_" + id).html());
      $("#txt_lost_kamar_tipe").val($("#tipe_" + id).html());

      if (lostandfound === undefined) {
        lostandfound = $("#lostandfound").DataTable({
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
            url: __HOSTAPI__ + "/Kamar",
            type: "POST",
            headers: {
              Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
            },
            data: function(d) {
              d.request = "lostandfound";
              d.kamar = selectedKamar;
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
                return "<span class=\"wrap_content\">" + row.created_at + "</span>";
              }
            },
            {
              "data": null,
              render: function(data, type, row, meta) {
                return "<span class=\"wrap_content\">" + row.nama + "</span>";
              }
            }, {
              "data": null,
              render: function(data, type, row, meta) {
                return row.deskripsi;
              }
            }, {
              "data": null,
              render: function(data, type, row, meta) {
                return "<span class=\"wrap_content\">" + row.guest + "</span>";
              }
            }, {
              "data": null,
              render: function(data, type, row, meta) {
                return "<span class=\"wrap_content\">" + row.delivered_date + "</span>";
              }
            }, {
              "data": null,
              render: function(data, type, row, meta) {
                return "<span class=\"wrap_content\">" + row.delivered_by + "</span>";
              }
            }, {
              "data": null,
              render: function(data, type, row, meta) {
                return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                  "<button id=\"lost_edit_" + row.id + "\" class=\"btn btn-info btn-sm btn-edit-lost\">" +
                  "<span><i class=\"fa fa-eye\"></i> Edit</span>" +
                  "</button>" +
                  "<button id=\"lost_delete_" + row.id + "\" class=\"btn btn-danger btn-sm btn-delete-lost\">" +
                  "<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
                  "</button>" +
                  "</div>";
              }
            }
          ]
        });
      } else {
        lostandfound.ajax.reload();
      }

      $("#detail-kamar").modal("show");
      return false;
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

    $("body").on("click", ".btn-delete-lost", function() {
      var id = $(this).attr("id").split("_");
      id = id[id.length - 1];
      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Kamar/lost/" + id,
        type: "DELETE",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        success: function(response) {
          if (response.response_package.response_result > 0) {
            lostandfound.ajax.reload();
          }
        }
      });
    });

    $("body").on("click", ".btn-edit-lost", function() {
      var id = $(this).attr("id").split("_");
      id = id[id.length - 1];
      selectedIdLost = id;
      modeLost = "edit";
      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Kamar/lost_detail/" + id,
        type: "GET",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        success: function(response) {
          var data = response.response_package.response_data[0];
          $("#form-lost").modal("show");
          $("#txt_lost_guest").val(data.guest);
          $("#txt_lost_lokasi").val(data.lokasi);
          $("#txt_lost_desc").val(data.deskripsi);
          $("#txt_lost_del_date").val(data.delivered_date);
          $("#txt_lost_del_by").val(data.delivered_by);
        }
      });
    });

    $("#btnAddLost").click(function() {
      $("#form-lost").modal("show");
      modeLost = "tambah";
    });

    $("#btnProsesLost").click(function() {
      var guest = $("#txt_lost_guest").val();
      var lokasi = $("#txt_lost_lokasi").val();
      var deliv_date = $("#txt_lost_del_date").val();
      var deliv_by = $("#txt_lost_del_by").val();
      var deskripsi = $("#txt_lost_desc").val();
      if (lokasi !== "" && deskripsi !== "") {
        $.ajax({
          async: false,
          url: __HOSTAPI__ + "/Kamar",
          type: "POST",
          data: {
            request: (modeLost === "tambah") ? 'tambah_lost' : 'edit_lost',
            id: selectedIdLost,
            kamar: selectedKamar,
            guest: guest,
            lokasi: lokasi,
            deliv_by: deliv_by,
            deliv_date: deliv_date,
            deskripsi: deskripsi
          },
          beforeSend: function(request) {
            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
          },
          success: function(response) {
            if (response.response_package.response_result > 0) {
              $("#txt_lost_guest").val("");
              $("#txt_lost_lokasi").val("");
              $("#txt_lost_desc").val("");
              $("#txt_lost_del_date").val("");
              $("#txt_lost_del_by").val("");
              $("#form-lost").modal("hide");
              lostandfound.ajax.reload();
            }
          }
        });
      }
    });
  });
</script>

<div id="detail-kamar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Detail Kamar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-2">
            <div class="col-12 form-group">
              <label for="txt_kamar">Kamar</label>
              <input type="text" autocomplete="off" class="form-control uppercase" id="txt_lost_kamar" readonly>
            </div>
            <div class="col-12 form-group">
              <label for="txt_kamar_tipe">Tipe</label>
              <input type="text" autocomplete="off" class="form-control uppercase" id="txt_lost_kamar_tipe" readonly>
            </div>
          </div>
          <div class="col-10">
            <button class="btn btn-info btn-sm" id="btnAddLost">
              <i class="fa fa-plus"></i> Tambah Lost and Found
            </button>
            <br /><br />
            <table class="table" id="lostandfound">
              <thead class="thead-dark">
                <tr>
                  <th class="wrap_content">Tgl</th>
                  <th class="wrap_content">Ditemukan Oleh</th>
                  <th>Deskripsi</th>
                  <th class="wrap_content">Guest</th>
                  <th class="wrap_content">Deliv. Date</th>
                  <th class="wrap_content">Deliv. By</th>
                  <th class="wrap_content">Aksi</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>

<div id="form-lost" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Lost And Found</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-6 form-group">
            <label for="txt_lost_guest">Guest</label>
            <input type="text" autocomplete="off" class="form-control" id="txt_lost_guest" />
          </div>
          <div class="col-6 form-group">
            <label for="txt_lost_lokasi">Lokasi</label>
            <input type="text" autocomplete="off" class="form-control" id="txt_lost_lokasi" />
          </div>
          <div class="col-6 form-group">
            <label for="txt_lost_del_date">Delivered Date</label>
            <input type="date" autocomplete="off" class="form-control" id="txt_lost_del_date" />
          </div>
          <div class="col-6 form-group">
            <label for="txt_lost_del_by">Delivered By</label>
            <input type="text" autocomplete="off" class="form-control" id="txt_lost_del_by" />
          </div>
          <div class="col-12 form-group">
            <label for="txt_lost_desc">Deskripsi</label>
            <textarea id="txt_lost_desc" style="min-height: 150px;" class="form-control"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
        <button type="button" class="btn btn-success" id="btnProsesLost">Prosess</button>
      </div>
    </div>
  </div>
</div>

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