<script type="text/javascript">
  $(function() {
    var MODE = "tambah",
      selectedUID;
    var itemMODE = "tambah";
    var selectedOutlet;
    var selectedItem = "";
    var tabletipe = $("#table-outlet").DataTable({
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
        url: __HOSTAPI__ + "/Outlet",
        type: "POST",
        headers: {
          Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
        },
        data: function(d) {
          d.request = "outlet_list";
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
            return "<span id=\"kode_" + row.uid + "\">" + row.kode + "</span>";
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

    var OEm = $("#outletPegawai").DataTable({
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
        url: __HOSTAPI__ + "/Outlet",
        type: "POST",
        headers: {
          Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
        },
        data: function(d) {
          d.request = "outlet_employee_list";
          d.outlet = selectedOutlet;
        },
        dataSrc: function(response) {
          var returnData = [];
          var rawData = [];
          if (
            selectedOutlet === undefined ||
            response === undefined ||
            response === null ||
            response.response_package === undefined ||
            response.response_package === null
          ) {
            rawData = [];
            returnData = [];
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
            return "<span id=\"nama_" + row.id + "\">" + row.nama + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
              "<button id=\"outlet_empl_delete_" + row.id + "\" class=\"btn btn-danger btn-sm btn-delete-empl\">" +
              "<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
              "</button>" +
              "</div>";
          }
        }
      ]
    });

    var OTab = $("#outletMeja").DataTable({
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
        url: __HOSTAPI__ + "/Outlet",
        type: "POST",
        headers: {
          Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
        },
        data: function(d) {
          d.request = "outlet_table_list";
          d.outlet = selectedOutlet;
        },
        dataSrc: function(response) {
          var returnData = [];
          var rawData = [];
          if (
            selectedOutlet === undefined ||
            response === undefined ||
            response === null ||
            response.response_package === undefined ||
            response.response_package === null
          ) {
            rawData = [];
            returnData = [];
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
            return "<span id=\"nama_" + row.uid + "\">" + row.kode + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
              "<button id=\"outlet_table_delete_" + row.uid + "\" class=\"btn btn-danger btn-sm btn-delete-table\">" +
              "<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
              "</button>" +
              "</div>";
          }
        }
      ]
    });

    var OIt = $("#outletItem").DataTable({
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
        url: __HOSTAPI__ + "/Outlet",
        type: "POST",
        headers: {
          Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
        },
        data: function(d) {
          d.request = "outlet_item_list";
          d.outlet = selectedOutlet;
        },
        dataSrc: function(response) {
          var returnData = [];
          var rawData = [];
          if (
            selectedOutlet === undefined ||
            response === undefined ||
            response === null ||
            response.response_package === undefined ||
            response.response_package === null
          ) {
            rawData = [];
            returnData = [];
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
            return "<span id=\"nama_" + row.uid + "\">" + row.nama + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<h5 class=\"number_style\" harga-set=\"" + row.price + "\" id=\"harga_" + row.uid + "\">" + number_format(row.price, 2, ".", ",") + "</h5>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
              "<button id=\"outlet_item_edit_" + row.uid + "\" class=\"btn btn-info btn-sm btn-edit-item\">" +
              "<span><i class=\"fa fa-pencil-alt\"></i> Edit</span>" +
              "</button>" +
              "<button id=\"outlet_item_delete_" + row.uid + "\" class=\"btn btn-danger btn-sm btn-delete-item\">" +
              "<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
              "</button>" +
              "</div>";
          }
        }
      ]
    });

    $("body").on("click", ".btn-edit-item", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];
      selectedItem = uid;
      itemMODE = "edit";
      $("#form-item").modal("show");
      $("#txt_item_nama").val($("#nama_" + uid).html());
      $("#txt_item_harga").inputmask("setvalue", $("#harga_" + uid).attr("harga-set"));
    });

    $("body").on("click", ".btn-delete-table", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];
      Swal.fire({
        title: "Hapus meja?",
        showDenyButton: true,
        confirmButtonText: `Ya`,
        denyButtonText: `Tidak`,
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: __HOSTAPI__ + "/Outlet/meja/" + uid,
            beforeSend: function(request) {
              request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type: "DELETE",
            success: function(response) {
              if (response.response_package.response_result > 0) {
                OTab.ajax.reload();
              }
            },
            error: function(response) {
              console.log(response);
            }
          });
        }
      });
    });

    $("body").on("click", ".btn-delete-item", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];

      Swal.fire({
        title: "Hapus item?",
        showDenyButton: true,
        confirmButtonText: `Ya`,
        denyButtonText: `Tidak`,
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: __HOSTAPI__ + "/Outlet/item/" + uid,
            beforeSend: function(request) {
              request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type: "DELETE",
            success: function(response) {
              if (response.response_package.response_result > 0) {
                OIt.ajax.reload();
              }
            },
            error: function(response) {
              console.log(response);
            }
          });
        }
      });
    });

    $("body").on("click", ".btn-delete-empl", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];

      Swal.fire({
        title: "Hapus pegawai?",
        showDenyButton: true,
        confirmButtonText: `Ya`,
        denyButtonText: `Tidak`,
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: __HOSTAPI__ + "/Outlet/pegawai/" + uid,
            beforeSend: function(request) {
              request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type: "DELETE",
            success: function(response) {
              if (response.response_package.response_result > 0) {
                OEm.ajax.reload();
              }
            },
            error: function(response) {
              console.log(response);
            }
          });
        }
      });
    });

    $("body").on("click", ".btn-delete-tipe", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];

      Swal.fire({
        title: "Hapus outlet?",
        showDenyButton: true,
        confirmButtonText: `Ya`,
        denyButtonText: `Tidak`,
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: __HOSTAPI__ + "/Outlet/outlet/" + uid,
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
          url: __HOSTAPI__ + "/Outlet",
          beforeSend: function(request) {
            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
          },
          type: "POST",
          data: {
            request: (MODE === 'tambah') ? 'tambah_outlet' : 'edit_outlet',
            uid: selectedUID,
            kode: kode,
            nama: nama
          },
          success: function(response) {
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
      selectedOutlet = uid;
      refresh_category(selectedOutlet);
      OIt.ajax.reload();
      OEm.ajax.reload();
      OTab.ajax.reload();

      $("#txt_kode").val($("#kode_" + uid).html());
      $("#txt_nama").val($("#nama_" + uid).html());
    });

    $("#addItemOutlet").click(function() {
      itemMODE = "tambah";
      $("#form-item").modal("show");
    });


    $("#txt_pegawai").select2({
      minimumInputLength: 2,
      "language": {
        "noResults": function() {
          return "Pegawai tidak ditemukan";
        }
      },
      placeholder: "Cari Pegawai",
      dropdownParent: $("#form-tambah"),
      ajax: {
        dataType: "json",
        headers: {
          "Authorization": "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
          "Content-Type": "application/json",
        },
        url: __HOSTAPI__ + "/Pegawai/get_pegawai_select2",
        type: "GET",
        data: function(term) {
          return {
            search: term.term
          };
        },
        cache: true,
        processResults: function(response) {
          var data = response.response_package.response_data;
          return {
            results: $.map(data, function(item) {
              return {
                text: item.nama,
                id: item.uid
              }
            })
          };
        }
      }
    }).addClass("form-control").on("select2:select", function(e) {
      var data = e.params.data;
    });

    $("#txt_item_harga").inputmask({
      alias: 'decimal',
      rightAlign: true,
      placeholder: "0.00",
      prefix: "",
      groupSeparator: ".",
      autoGroup: false,
      digitsOptional: true
    });

    $("#btnSubmitItem").click(function() {
      var harga = $("#txt_item_harga").inputmask("unmaskedvalue");
      var nama = $("#txt_item_nama").val();
      var kategori = $("#txt_item_kategori").val();
      if (harga !== "" && nama !== "") {
        $.ajax({
          url: __HOSTAPI__ + "/Outlet",
          beforeSend: function(request) {
            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
          },
          type: "POST",
          data: {
            request: (itemMODE === 'tambah') ? 'tambah_item' : 'edit_item',
            uid: selectedItem,
            outlet: selectedOutlet,
            harga: harga,
            kategori: kategori,
            nama: nama
          },
          success: function(response) {
            if (response.response_package.response_result > 0) {
              $("#form-item").modal("hide");
              $("#txt_item_harga").inputmask("setvalue", 0);
              $("#txt_item_nama").val("");
              OIt.ajax.reload();
            }
          },
          error: function(response) {
            console.log(response);
          }
        });
      }
    });

    $("#addTableOutlet").click(function() {
      var tableCode = $("#txt_kode_meja").val();
      if (tableCode !== "") {
        $.ajax({
          url: __HOSTAPI__ + "/Outlet",
          beforeSend: function(request) {
            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
          },
          type: "POST",
          data: {
            request: 'tambah_meja',
            uid: selectedItem,
            outlet: selectedOutlet,
            tableCode: tableCode
          },
          success: function(response) {
            console.log(response);
            if (response.response_package.response_result > 0) {
              OTab.ajax.reload();
              $("#txt_kode_meja").val("");

            }
          },
          error: function(response) {
            console.log(response);
          }
        });
      }
    });

    $("#cari-kategori").keyup(function() {
      refresh_category(selectedOutlet);
    });

    $("body").on("click", ".delete_kategori", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];

      $.ajax({
        url: __HOSTAPI__ + "/Outlet/category/" + uid,
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        type: "DELETE",
        success: function(response) {
          if (response.response_package.response_result > 0) {
            refresh_category(selectedOutlet);
          }
        },
        error: function(response) {
          console.log(response);
        }
      });
    });

    $("#btnAddCategory").click(function() {
      $.ajax({
        url: __HOSTAPI__ + "/Outlet",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        type: "POST",
        data: {
          request: 'tambah_category',
          nama: $("#cari-kategori").val(),
          outlet: selectedOutlet
        },
        success: function(response) {
          refresh_category(selectedOutlet);
        },
        error: function(response) {
          console.log(response);
        }
      });
    });

    function refresh_category(selectedOutlet) {
      var filterS = $("#cari-kategori").val();
      $.ajax({
        url: __HOSTAPI__ + "/Outlet",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        type: "POST",
        data: {
          request: 'load_category',
          search: filterS,
          outlet: selectedOutlet
        },
        success: function(response) {
          var data = response.response_package.response_data;
          $("#load-category").html("");
          if (data.length > 0) {
            $("#btnAddCategory").hide();
            $("#txt_item_kategori option").remove();
            for (var a in data) {
              $("#txt_item_kategori").append("<option value=\"" + data[a].uid + "\">" + data[a].nama + "</option>");
              $("#load-category").append(
                "<div class=\"list-group-item d-flex media align-items-center\">" +
                "<div class=\"media-body\">" +
                "<p class=\"m-0\">" +
                "<span href=\"#\" class=\"text-body\"><a href=\"#\"class=\"delete_kategori text-danger\" id=\"del_cat_" + data[a].uid + "\">Hapus</a> | <strong>" + data[a].nama + "</strong></span>" +
                "</p>" +
                "</div>" +
                "</div>"
              );
            }
          } else {
            $("#btnAddCategory").fadeIn();
          }
        },
        error: function(response) {
          console.log(response);
        }
      });
    }

    $("#addItemEmployee").click(function() {
      var pegawai = $("#txt_pegawai").val();

      if (pegawai !== "") {
        $.ajax({
          url: __HOSTAPI__ + "/Outlet",
          beforeSend: function(request) {
            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
          },
          type: "POST",
          data: {
            request: 'tambah_pegawai',
            uid: selectedItem,
            outlet: selectedOutlet,
            pegawai: pegawai
          },
          success: function(response) {
            if (response.response_package.response_result > 0) {
              // $(newObat).select2("data", {id: setter.obat, text: setter.obat_detail.nama});
              OEm.ajax.reload();
              $("#txt_pegawai").select2("data", {});
              $("#txt_pegawai option").remove();
              $("#txt_pegawai").trigger("change");

            }
          },
          error: function(response) {
            console.log(response);
          }
        });
      }
    });
  });
</script>

<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Outlet Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="form-group col-md-6">
            <label for="txt_kode">Kode:</label>
            <input type="text" class="form-control" id="txt_kode" />
          </div>
          <div class="form-group col-md-6">
            <label for="txt_nama">Nama:</label>
            <input type="text" class="form-control" id="txt_nama" />
          </div>
          <div class="col-md-12">
            <div class="z-0">
              <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="nav_list">
                <li class="nav-item">
                  <a href="#tab-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-1">
                    <span class="nav-link__count">
                      <i class="fa fa-cubes"></i>
                    </span>
                    Item
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#tab-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-2">
                    <span class="nav-link__count">
                      <i class="fa fa-user"></i>
                    </span>
                    Pegawai
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#tab-3" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-3">
                    <span class="nav-link__count">
                      <i class="fa fa-th"></i>
                    </span>
                    Table
                  </a>
                </li>
              </ul>
            </div>
            <div class="card card-body tab-content">
              <div class="tab-pane show active fade" id="tab-1">
                <div class="row">
                  <div class="col-8">
                    <button class="btn btn-info btn-sm pull-right" id="addItemOutlet">
                      <i class="fa fa-plus"></i> Tambah Item
                    </button>
                    <br /><br />
                    <table class="table largeDataType" id="outletItem">
                      <thead class="thead-dark">
                        <tr>
                          <th class="wrap_content">No</th>
                          <th>Nama</th>
                          <th class="wrap_content">Harga + (Tax/Service)</th>
                          <th class="wrap_content">Aksi</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                  <div class="col-4">
                    <div class="bg-white border-left d-flex flex-column">
                      <div class="form-group px-3">
                        <div class="input-group input-group-merge input-group-rounded">
                          <input type="text" class="form-control form-control-prepended" id="cari-kategori" placeholder="Filter kategori">
                          <div class="input-group-prepend">
                            <div class="input-group-text">
                              <span class="material-icons">filter_list</span>
                            </div>
                          </div>
                          <div class="input-group-append">
                            <button class="btn btn-info" id="btnAddCategory"><i class="fa fa-plus"></i> Add</button>
                          </div>
                        </div>
                      </div>
                      <div class="flex d-flex flex-column">
                        <div data-simplebar="init" class="h-100">
                          <div class="simplebar-wrapper" style="margin: 0px;">
                            <div class="simplebar-height-auto-observer-wrapper">
                              <div class="simplebar-height-auto-observer"></div>
                            </div>
                            <div class="simplebar-mask">
                              <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                <div class="simplebar-content" style="padding: 0px; height: 100%; overflow: hidden;">
                                  <div class="list-group list-group-flush" style="position: relative; z-index: 0; max-height: 200px; overflow-y:scroll" id="load-category">


                                    <!-- <div class="list-group-item d-flex media align-items-center">
                                      <a href="#" class="avatar avatar-sm media-left mr-3">
                                        <img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_rsz_1andy-lee-642320-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
                                      </a>
                                      <div class="media-body">
                                        <p class="m-0">
                                          <a href="#" class="text-body"><strong>Jenell D. Matney</strong></a><br>
                                          <span class="text-muted">Founder and CEO</span>
                                        </p>
                                      </div>
                                    </div> -->



                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="simplebar-placeholder" style="width: 354px; height: 536px;"></div>
                          </div>
                          <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                            <div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); visibility: hidden;"></div>
                          </div>
                          <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                            <div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); visibility: hidden;"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane show fade" id="tab-2">
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="txt_nama">Nama:</label>
                    <select class="form-control" id="txt_pegawai"></select>
                  </div>
                  <div class="col-md-6">
                    <br />
                    <button class="btn btn-info btn-sm pull-right" id="addItemEmployee">
                      <i class="fa fa-plus"></i> Tambah Pegawai
                    </button>
                  </div>
                  <br /><br />
                </div>
                <table class="table largeDataType" id="outletPegawai">
                  <thead class="thead-dark">
                    <tr>
                      <th class="wrap_content">No</th>
                      <th>Nama</th>
                      <th class="wrap_content">Aksi</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
              <div class="tab-pane show fade" id="tab-3">
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="txt_nama">Kode Meja:</label>
                    <input type="text" class="form-control" id="txt_kode_meja" />
                  </div>
                  <div class="col-6">
                    <button class="btn btn-info btn-sm pull-right" id="addTableOutlet">
                      <i class="fa fa-plus"></i> Tambah Meja
                    </button>
                  </div>
                  <div class="col-12">
                    <table class="table largeDataType" id="outletMeja">
                      <thead class="thead-dark">
                        <tr>
                          <th class="wrap_content">No</th>
                          <th>Kode</th>
                          <th class="wrap_content">Aksi</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
          <button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <div id="form-item" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-large-title">Form Item</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-8">
              <label for="txt_item_nama">Nama:</label>
              <input type="text" class="form-control" id="txt_item_nama" />
            </div>
            <div class="form-group col-md-8">
              <label for="txt_item_kategori">Kategori:</label>
              <select class="form-control" id="txt_item_kategori"></select>
            </div>
            <div class="form-group col-md-6">
              <label for="txt_item_harga">Harga + (Tax / Service):</label>
              <input type="text" class="form-control" id="txt_item_harga" />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
          <button type="button" class="btn btn-primary" id="btnSubmitItem">Submit</button>
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