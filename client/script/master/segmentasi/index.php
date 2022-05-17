<script type="text/javascript">
  $(function() {
    var MODE = "tambah",
      selectedUID;
    var tabletipe = $("#table-tipe").DataTable({
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
        url: __HOSTAPI__ + "/Segmentasi",
        type: "POST",
        headers: {
          Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
        },
        data: function(d) {
          d.request = "segmentasi_list";
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
            return "<span id=\"kode_" + row.uid + "\">" + row.msscode + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span id=\"nama_" + row.uid + "\">" + row.deskripsi + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
              "<button id=\"tipe_edit_" + row.uid + "\" class=\"btn btn-info btn-sm btn-edit-tipe\">" +
              "<span><i class=\"fa fa-pencil-alt\"></i> Edit</span>" +
              "</button>" +
              "<button id=\"tipe_delete_" + row.uid + "\" class=\"btn btn-danger btn-sm btn-delete-tipe\">" +
              "<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
              "</button>" +
              "</div>";
          }
        }
      ]
    });

    $("body").on("click", ".btn-delete-tipe", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];

      Swal.fire({
        title: "Hapus tipe?",
        showDenyButton: true,
        confirmButtonText: `Ya`,
        denyButtonText: `Tidak`,
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: __HOSTAPI__ + "/Segmentasi/master/" + uid,
            beforeSend: function(request) {
              request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type: "DELETE",
            success: function(response) {
              if (response.response_package.response_result > 0) {
                tabletipe.ajax.reload();
              }
            },
            error: function(response) {
              console.log(response);
            }
          });
        }
      });
    });

    $("#btnSubmit").click(function() {
      var kode = $("#txt_kode").val();
      var nama = $("#txt_nama").val();
      if (kode !== "" && nama !== "") {
        $.ajax({
          url: __HOSTAPI__ + "/Segmentasi",
          beforeSend: function(request) {
            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
          },
          type: "POST",
          data: {
            request: (MODE === 'tambah') ? 'tambah_segmentasi' : 'edit_segmentasi',
            uid: selectedUID,
            kode: kode,
            nama: nama
          },
          success: function(response) {
            console.log(response);
            if (response.response_package.response_result > 0) {
              $("#form-tambah").modal("hide");
              $("#txt_kode").val("");
              $("#txt_nama").val("");
              tabletipe.ajax.reload();
            }
          },
          error: function(response) {
            console.log(response);
          }
        });
      }
    });

    $("#btnTambahtipe").click(function() {
      $("#form-tambah").modal("show");
      MODE = 'tambah';
    });

    $("body").on("click", ".btn-edit-tipe", function() {
      $("#form-tambah").modal("show");
      MODE = 'edit';

      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];

      selectedUID = uid;

      $("#txt_kode").val($("#kode_" + uid).html());
      $("#txt_nama").val($("#nama_" + uid).html());
    });
  });
</script>

<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Form Segmentasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group col-md-12">
          <label for="txt_kode">Kode:</label>
          <input type="text" class="form-control" id="txt_kode" />
        </div>
        <div class="form-group col-md-12">
          <label for="txt_nama">Nama:</label>
          <input type="text" class="form-control" id="txt_nama" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
        <button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
      </div>
    </div>
  </div>
</div>


<div id="view-detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md bg-danger" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Tindakan dari : <span id="title-tindakan"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered" id="table-view-tindakan">
          <thead>

          </thead>

          <tbody>

          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>