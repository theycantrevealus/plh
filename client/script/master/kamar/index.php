<script type="text/javascript">
  $(function() {
    var MODE = "tambah",
      selectedUID;
    var tablekamar = $("#table-kamar").DataTable({
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
          d.request = "kamar_list";
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
            return "<span id=\"nama_" + row.uid + "\">" + row.nomor + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span id=\"kode_" + row.uid + "\">" + row.kode + "-" + row.nama + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
              "<button id=\"kamar_edit_" + row.uid + "\" class=\"btn btn-info btn-sm btn-edit-kamar\">" +
              "<span><i class=\"fa fa-pencil-alt\"></i> Edit</span>" +
              "</button>" +
              "<button id=\"kamar_delete_" + row.uid + "\" class=\"btn btn-danger btn-sm btn-delete-kamar\">" +
              "<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
              "</button>" +
              "</div>";
          }
        }
      ]
    });

    reload_tipe("#txt_tipe");


    function reload_tipe(target) {
      $(target).find("option").remove();
      $.ajax({
        url: __HOSTAPI__ + "/Kamar/tipe",
        async: false,
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        type: "GET",
        success: function(response) {
          data = response.response_package.response_data;
          for (var a in data) {
            $(target).append("<option value=\"" + data[a].uid + "\">" + data[a].kode + " - " + data[a].nama + "</option>");
          }

          $("#txt_tipe").select2();
        },
        error: function(response) {
          console.log(response);
        }
      });
    }


    $("body").on("click", ".btn-delete-kamar", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];

      Swal.fire({
        title: "Hapus kamar?",
        showDenyButton: true,
        confirmButtonText: `Ya`,
        denyButtonText: `Tidak`,
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: __HOSTAPI__ + "/Kamar/kamar/" + uid,
            beforeSend: function(request) {
              request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type: "DELETE",
            success: function(response) {
              if (response.response_package.response_result > 0) {
                tablekamar.ajax.reload();
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
      var nomor = $("#txt_nomor").val();
      var tipe = $("#txt_tipe").val();
      var keterangan = $("#txt_keterangan").val();
      if (nomor !== "" && tipe !== "") {
        $.ajax({
          url: __HOSTAPI__ + "/Kamar",
          beforeSend: function(request) {
            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
          },
          type: "POST",
          data: {
            request: (MODE === 'tambah') ? 'tambah_kamar' : 'edit_kamar',
            uid: selectedUID,
            nomor: nomor,
            tipe: tipe,
            keterangan: keterangan
          },
          success: function(response) {
            console.log(response)
            if (response.response_package.response_result > 0) {
              $("#form-tambah").modal("hide");
              $("#txt_kode").val("");
              $("#txt_nama").val("");
              tablekamar.ajax.reload();
            }
          },
          error: function(response) {
            console.log(response);
          }
        });
      }
    });

    $("#btnTambahkamar").click(function() {
      $("#form-tambah").modal("show");
      MODE = 'tambah';
    });

    $("body").on("click", ".btn-edit-kamar", function() {
      $("#form-tambah").modal("show");
      MODE = 'edit';

      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];

      selectedUID = uid;

      $.ajax({
        url: __HOSTAPI__ + "/Kamar/detail/" + uid,
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        type: "GET",
        success: function(response) {
          var data = response.response_package.response_data[0];
          $("#txt_nomor").val(data.nomor);
          $("#txt_keterangan").val(data.keterangan);
          $("#txt_tipe").append("<option value=\"" + data.tipe + "\">" + data.kode + "-" + data.nama + "</option>");
          $("#txt_tipe").select2("data", {
            id: data.tipe,
            text: data.kode + "-" + data.nama
          });
          $("#txt_tipe").trigger("change");
        },
        error: function(response) {
          console.log(response);
        }
      });
    });
  });


  /*========== FUNC FOR LOAD PENJAMIN ==========*/
  function loadPenjamin() {
    var dataPenjamin;

    $.ajax({
      async: false,
      url: __HOSTAPI__ + "/Penjamin/penjamin",
      type: "GET",
      beforeSend: function(request) {
        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
      },
      success: function(response) {
        var MetaData = dataPenjamin = response.response_package.response_data;
      },
      error: function(response) {
        console.log(response);
      }
    });


    if (dataPenjamin.length > 0) {
      return dataPenjamin;
    } else {
      return null;
    }
  }
  /*--------------------------------------*/
</script>

<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Tambah kamar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group col-md-12">
          <label for="txt_nama">Tipe:</label>
          <select class="form-control" id="txt_tipe"></select>
        </div>
        <div class="form-group col-md-12">
          <label for="txt_kode">Nomor:</label>
          <input type="text" class="form-control" id="txt_nomor" />
        </div>
        <div class="form-group col-md-12">
          <label for="txt_nama">Deskripsi:</label>
          <textarea type="text" class="form-control" id="txt_keterangan"></textarea>
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