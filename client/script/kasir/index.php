<script type="text/javascript">
  $(function() {
    var selectedTransCode = "";
    var selectedDBCR = "";
    var selectedKeterangan = "";
    var selectedFolio = "";
    var selectedIsTax = "N";
    var selectedIsService = "N";
    var DTH = $("#table-history").DataTable({
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
        url: __HOSTAPI__ + "/Folio",
        type: "POST",
        headers: {
          Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
        },
        data: function(d) {
          d.request = "list_folio_history";
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
            return "<span class=\"wrap_content\">" + row.no_reservasi + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span class=\"wrap_content\">" + row.no_folio + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span>" + row.nama_depan + " " + row.nama_belakang + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span class=\"wrap_content\">" + row.nomor_kamar + "</span>";
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
            return "<span class=\"wrap_content\">" + number_format(row.balance, 2, ".", ",") + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
              "<button id=\"tipe_edit_" + row.uid + "\" class=\"btn btn-info btn-sm btn-edit-reservasi\">" +
              "<span><i class=\"fa fa-eye\"></i> Detail</span>" +
              "</button>" +
              "<button id=\"kwitansi_" + row.uid + "\" class=\"btn btn-info btn-sm btn-cetak-kwitansi\">" +
              "<span><i class=\"fa fa-eye\"></i> Detail</span>" +
              "</button>" +
              "</div>";
          }
        }
      ]
    });

    $("body").on("click", ".btn-cetak-kwitansi", function() {
      //
    });

    var DT = $("#table-folio").DataTable({
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
        url: __HOSTAPI__ + "/Folio",
        type: "POST",
        headers: {
          Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
        },
        data: function(d) {
          d.request = "list_folio";
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
            return "<span class=\"wrap_content\">" + row.no_reservasi + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span class=\"wrap_content\">" + row.no_folio + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span>" + row.nama_depan + " " + row.nama_belakang + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<span class=\"wrap_content\">" + row.nomor_kamar + "</span>";
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
            return "<span class=\"wrap_content\">" + number_format(row.balance, 2, ".", ",") + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
              "<button id=\"tipe_edit_" + row.uid + "\" class=\"btn btn-info btn-sm btn-edit-reservasi\">" +
              "<span><i class=\"fa fa-eye\"></i> Edit</span>" +
              "</button>" +
              "<button id=\"tipe_entry_" + row.uid + "\" class=\"btn btn-success btn-sm btn-entry\">" +
              "<span><i class=\"fa fa-pencil-alt\"></i> Entry</span>" +
              "</button>" +
              "</div>";
          }
        }
      ]
    });

    $("body").on("click", ".btn-entry", function() {
      var id = $(this).attr("id").split("_");
      id = id[id.length - 1];
      selectedFolio = id;
      refresh_folio_detaik(id);
    });

    function refresh_folio_detaik(id) {
      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Folio/folio_detail/" + id,
        type: "GET",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        success: function(response) {
          var data = response.response_package.response_data[0];
          $("#cap_balance h4").html(number_format(data.balance, 2, ".", ",")).attr("setter-balance", data.balance);
          $("#cap_room_number").html(data.nomor_kamar);
          $("#cap_room_type").html(data.kode_tipe + " - " + data.nama_tipe);
          $("#cap_rate").html(data.kode_rate);
          $("#cap_rate_value").html(number_format(data.rate_value, 2, ".", ","));
          $("#cap_folio").html(data.no_folio);
          $("#cap_guest").html(data.nama_depan + " " + data.nama_belakang);
          $("#cap_payment").html(data.kode_payment + " - " + data.nama_payment);
          $("#cap_status").html((data.status === 'T') ? "Tentative" : "Guarantee");
          $("#cap_card_num").html(data.card_number);
          $("#cap_card_valid").html(data.card_valid_until);
          $("#cap_pax").html(data.pax);
          if (data.kode_company === undefined && data.nama_company === undefined) {
            $("#cap_company").html("-");
          } else {
            $("#cap_company").html(data.kode_company + " - " + data.nama_company);
          }
          $("#modal-folio").modal("show");

          refresh_translist(data.uid);
        }
      });
    }


    $("#btnAddTrans").click(function() {
      $("#form-transcode").modal("show");
    });

    function refresh_translist(folio) {
      $("#entry-trans tbody tr").remove();
      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Folio/folio_trans/" + folio,
        type: "GET",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        success: function(response) {
          var balance = 0;
          var deb = 0;
          var kre = 0;
          var data = response.response_package.response_data;
          for (var a in data) {
            var priceSet = (parseFloat(data[a].final_price) > 0) ? data[a].final_price : data[a].price;
            deb += parseFloat((data[a].transcode.dbcr === "D") ? priceSet : 0);
            kre += parseFloat((data[a].transcode.dbcr === "K") ? priceSet : 0);

            var price = number_format(priceSet, 2, ".", ",");
            $("#entry-trans tbody").append("<tr>" +
              "<td><span class=\"wrap_content\">" + data[a].created_at + "</span></td>" +
              "<td class=\"text-right\"><b class=\"wrap_content\">" + data[a].transcode.kode + "</b></td>" +
              "<td>" + ((data[a].deskripsi === undefined || data[a].deskripsi === null) ? data[a].transcode.keterangan : data[a].deskripsi) + "</td>" +
              "<td>" + data[a].remark + "</td>" +
              "<td><span class=\"wrap_content\">" + ((data[a].transcode.dbcr === "D") ? price : "") + "</span></td>" +
              "<td><span class=\"wrap_content\">" + ((data[a].transcode.dbcr === "K") ? price : "") + "</span></td>" +
              "<td><span class=\"wrap_content\">FO</span></td>" +
              "<td><span class=\"wrap_content\">" + data[a].add_by.nama + "</span></td>" +
              "</tr>");

          }
          balance = deb - kre;
          $("#cap_balance h4").html(number_format(balance, 2, ".", ",")).attr("setter-balance", balance);
        }
      });
    }

    $("#btnAddTransSet").click(function() {
      Swal.fire({
        title: "Proses Transakssi?",
        showDenyButton: true,
        confirmButtonText: "Ya",
        denyButtonText: "Batal",
      }).then((result) => {
        if (result.isConfirmed) {
          var deskripsi = $("#txt_trans_desc").val();
          var transval = $("#txt_trans_value").inputmask("unmaskedvalue");
          var transval_final = $("#txt_trans_value_applied").inputmask("unmaskedvalue");
          var remark = $("#txt_trans_remark").val();

          $.ajax({
            async: false,
            url: __HOSTAPI__ + "/Folio",
            type: "POST",
            data: {
              request: "add_trans",
              folio: selectedFolio,
              transcode: selectedTransCode,
              deskripsi: deskripsi,
              transval: transval,
              transval_final: transval_final,
              remark: remark
            },
            beforeSend: function(request) {
              request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response) {
              if (response.response_package.response_result > 0) {
                refresh_folio_detaik(selectedFolio);
                $("#txt_trans_desc").val("");
                $("#txt_trans_value").inputmask("setvalue", 0);
                $("#txt_trans_value_applied").inputmask("setvalue", 0);
                $("#txt_trans_remark").val("");
                DT.ajax.reload();
                $("#form-transcode").modal("hide");
              }
            }
          });
        }
      });
    });

    $("#txt_transcode").select2({
      minimumInputLength: 2,
      "language": {
        "noResults": function() {
          return "Transaksi tidak ditemukan";
        }
      },
      placeholder: "Cari Transaksi",
      dropdownParent: $("#form-transcode"),
      ajax: {
        dataType: "json",
        headers: {
          "Authorization": "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
          "Content-Type": "application/json",
        },
        url: __HOSTAPI__ + "/Accounting/get_transcode",
        type: "GET",
        data: function(term) {
          return {
            search: term.term
          };
        },
        cache: true,
        processResults: function(response) {
          console.clear();
          var data = response.response_package.response_data;
          console.log(data);

          return {
            results: $.map(data, function(item) {
              return {
                text: item.kode,
                dbcr: item.dbcr,
                keterangan: item.keterangan,
                apply_tax: item.apply_tax,
                apply_service: item.apply_service,
                id: item.uid
              }
            })
          };
        }
      }
    }).addClass("form-control").on("select2:select", function(e) {
      var data = e.params.data;
      selectedDBCR = data.dbcr;
      selectedTransCode = data.id;
      selectedKeterangan = data.keterangan;
      selectedIsTax = data.apply_tax;
      selectedIsService = data.apply_service;
      if (data.apply_tax === "Y") {
        $("#isTax").removeClass("fa-times text-danger").addClass("fa-check text-success");
      } else {
        $("#isTax").addClass("fa-times text-danger").removeClass("fa-check text-success");
      }

      if (data.apply_service === "Y") {
        $("#isService").removeClass("fa-times text-danger").addClass("fa-check text-success");
      } else {
        $("#isService").addClass("fa-times text-danger").removeClass("fa-check text-success");
      }

      $("#txt_trans_desc").val(data.keterangan);

      $("#txt_trans_value").focus();
    });

    $("#txt_trans_value_applied").inputmask({
      alias: 'decimal',
      rightAlign: true,
      placeholder: "0.00",
      prefix: "",
      allowMinus: true,
      groupSeparator: ".",
      autoGroup: false,
      digitsOptional: true
    });

    $("#txt_trans_value").inputmask({
      alias: 'decimal',
      rightAlign: true,
      placeholder: "0.00",
      prefix: "",
      allowMinus: true,
      groupSeparator: ".",
      autoGroup: false,
      digitsOptional: true
    }).keyup(function() {
      var getVal = $(this).inputmask("unmaskedvalue");
      var finalPrice = parseFloat(getVal);
      if (selectedIsTax === 'Y') {
        finalPrice += parseFloat(getVal * __TAX_VAL__ / 100);
      }

      if (selectedIsService === 'Y') {
        finalPrice += parseFloat(getVal * __SER_VAL__ / 100);
      }
      $("#txt_trans_value_applied").inputmask("setvalue", number_format(finalPrice, 2, ".", ","));
    });

    $("#btnCheckOut").click(function() {
      var balance = parseFloat($("#cap_balance h4").attr("setter-balance"));
      if (balance !== 0) {
        Swal.fire(
          "Check Out",
          "Balance harus 0. Mohon selesaikan semua transaksi yang tertinggal",
          "warning"
        ).then((result) => {
          $("#form-transcode").modal("show");
        });
      } else {
        Swal.fire({
          title: "Proses Check Out?",
          showDenyButton: true,
          confirmButtonText: "Ya",
          denyButtonText: "Batal",
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              async: false,
              url: __HOSTAPI__ + "/Folio",
              type: "POST",
              data: {
                request: "check_out",
                folio: selectedFolio
              },
              beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
              },
              success: function(response) {
                if (response.response_package.response_result > 0) {
                  DT.ajax.reload();
                  $("#modal-folio").modal("hide");
                }
              }
            });
          }
        });
      }
    });

  });
</script>

<div id="modal-folio" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Folio</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-12">
          <table class="table form-mode">
            <tr>
              <td class="wrap_content"><span class="wrap_content">Room Number</span></td>
              <td class="wrap_content">:</td>
              <td id="cap_room_number"></td>
              <td class="wrap_content"><span class="wrap_content">Room Type</span></td>
              <td class="wrap_content">:</td>
              <td id="cap_room_type"></td>
              <td class="wrap_content"><span class="wrap_content">Rate</span></td>
              <td class="wrap_content">:</td>
              <td id="cap_rate"></td>
            </tr>
            <tr>
              <td><span class="wrap_content">No Folio</span></td>
              <td class="wrap_content">:</td>
              <td id="cap_folio"></td>
              <td><span class="wrap_content">Payment Method</span></td>
              <td class="wrap_content">:</td>
              <td id="cap_payment"></td>
              <td><span class="wrap_content">Rate Value</span></td>
              <td class="wrap_content">:</td>
              <td id="cap_rate_value"></td>
            </tr>
            <tr>
              <td><span class="wrap_content">Guest</span></td>
              <td class="wrap_content">:</td>
              <td id="cap_guest"></td>
              <td><span class="wrap_content">Pax</span></td>
              <td class="wrap_content">:</td>
              <td id="cap_pax"></td>
              <td><span class="wrap_content">Status</span></td>
              <td class="wrap_content">:</td>
              <td id="cap_status"></td>
            </tr>
            <tr>
              <td><span class="wrap_content">Company</span></td>
              <td class="wrap_content">:</td>
              <td id="cap_company"></td>
              <td><span class="wrap_content">Card Number</span></td>
              <td class="wrap_content">:</td>
              <td id="cap_card_num"></td>
              <td><span class="wrap_content">Card Valid</span></td>
              <td class="wrap_content">:</td>
              <td id="cap_card_valid"></td>
            </tr>
            <tr>
              <td><span class="wrap_content">Balance</span></td>
              <td class="wrap_content">:</td>
              <td id="cap_balance">
                <h4></h4>
              </td>
              <td colspan="6"></td>
            </tr>
          </table>
        </div>
        <div class="col-12">
          <br /><br />
          <button class="pull-right btn btn-sm btn-info" id="btnAddTrans"><i class="fa fa-plus"></i> Tambah Transaksi</button>
        </div>
        <div class="col-12 table-responsive" style="min-height: 300px; height: 300px; overflow-y: scroll;">
          <br />
          <table class="table" id="entry-trans">
            <thead class="thead-dark">
              <tr>
                <th class="wrap_content">Date</th>
                <th class="wrap_content">Trans Code</th>
                <th>Deskripsi</th>
                <th>Remark</th>
                <th class="wrap_content">Charge</th>
                <th class="wrap_content">Credit</th>
                <th class="wrap_content">Source</th>
                <th class="wrap_content">Created By</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
        <button type="button" class="btn btn-warning" id="btnCheckOut"><i class="fa fa-sign-out-alt"></i> Check Out</button>
      </div>
    </div>
  </div>
</div>

<div id="modal-reservasi" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Reservasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row form-build">
          <div class="col-4">
            <div class="form-group">
              <label for="">Arrival</label>
              <input type="text" autocomplete="off" class="form-control txt_tanggal" id="txt_tanggal_arrival">
            </div>
          </div>
          <div class="col-2">
            <div class="form-group">
              <label for="">C/I Time</label>
              <input type="text" autocomplete="off" class="form-control timeset timepicker" id="txt_check_in">
            </div>
          </div>
          <div class="col-2">
            <div class="form-group">
              <label for="">Nights</label>
              <input type="text" autocomplete="off" class="form-control" id="txt_night">
            </div>
          </div>
          <div class="col-2">
            <div class="form-group">
              <label for="">Room Type</label>
              <input type="text" autocomplete="off" class="form-control" id="txt_tipe" readonly />
            </div>
          </div>
          <div class="col-2">
            <div class="form-group">
              <label for="">Block</label>
              <br />
              <input type="checkbox" class="form-control" id="txt_block" />
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="">Departure</label>
              <input type="text" autocomplete="off" class="form-control txt_tanggal" id="txt_tangal_departure" />
            </div>
          </div>
          <div class="col-2">
            <div class="form-group">
              <label for="">C/O Time</label>
              <input type="text" autocomplete="off" class="form-control timeset" id="txt_check_out" />
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="">Actual C/I</label>
              <input type="text" autocomplete="off" class="form-control datetimeset" id="txt_check_in_actual" readonly>
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="">Actual C/O</label>
              <input type="text" autocomplete="off" class="form-control datetimeset" id="txt_actual_check_out" readonly>
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="">Room</label>
              <input type="text" autocomplete="off" class="form-control uppercase" id="txt_kamar" readonly>
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="">Status</label>
              <select class="form-control uppercase" id="txt_status">
                <option value="T">Tentative</option>
                <option value="G">Guarantee</option>
              </select>
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="txt_pax">Pax</label>
              <input type="text" autocomplete="off" class="form-control uppercase" id="txt_pax" />
            </div>
          </div>
          <div class="col-6">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="">ID Number</label>
                  <input type="text" autocomplete="off" class="form-control uppercase" id="txt_identity_number" />
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="">Title</label>
                  <select class="form-control uppercase" id="panggilan"></select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="">First Name</label>
                  <input type="text" autocomplete="off" class="form-control" id="txt_firstname" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="">Last Name</label>
                  <input type="text" autocomplete="off" class="form-control" id="txt_last_name" />
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="">VIP</label>
                  <input type="checkbox" autocomplete="off" class="form-control uppercase" id="txt_vip">
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label for="">Tanggal Lahir</label>
                  <input type="date" autocomplete="off" class="form-control" id="txt_tanggal_lahir">
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label for="">Contact</label>
                  <input type="text" autocomplete="off" class="form-control" id="txt_contact" />
                </div>
                <div class="form-group">
                  <label for="">Email</label>
                  <input type="text" autocomplete="off" class="form-control" id="txt_email" />
                </div>
                <div class="form-group">
                  <label for="">Address</label>
                  <textarea autocomplete="off" class="form-control" id="txt_address"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="row">
              <div class="col-12 form-group">
                <label for="">Segmentation</label>
                <select class="form-control uppercase" id="txt_segmentasi"></select>
              </div>
              <div class="col-12 form-group segmentation_non_individual">
                <label for="">Company</label>
                <select class="form-control uppercase" id="txt_company"></select>
              </div>
              <div class="col-6 form-group">
                <label for="">Payment Method</label>
                <select class="form-control uppercase" id="txt_payment_method"></select>
              </div>
              <div class="col-6 form-group">
                <label for="">Deposit</label>
                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_deposit" />
              </div>
              <div class="col-9 form-group">
                <label for="">Card No.</label>
                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_card_number" />
              </div>
              <div class="col-3 form-group">
                <label for="">Card Valid Until</label>
                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_card_expiry" />
                <input type="hidden" name="SecureCard-expiryMonth">
                <input type="hidden" name="SecureCard-expiryYear">
              </div>
              <div class="col-6 form-group">
                <label for="">Nationality</label>
                <select class="form-control uppercase" id="txt_nationality"></select>
              </div>
              <div class="col-6 form-group">
                <label for="">Origin</label>
                <select class="form-control uppercase" id="txt_kabupaten"></select>
              </div>
              <div class="col-6 form-group">
                <label for="txt_rate_code">Rate Code</label>
                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_rate_code" readonly>
              </div>
              <div class="col-6 form-group">
                <label for="txt_rate_price">Rate Value</label>
                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_rate_price" />
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">Check In Remark</label>
              <textarea autocomplete="off" class="form-control" id="txt_check_in_remark"></textarea>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">Cashier Remark</label>
              <textarea autocomplete="off" readonly class="form-control uppercase" id="txt_cashier_remark"></textarea>
            </div>
          </div>
          <div class="col-12" id="changeLog">
            <h5>History Perubahan Data</h5>
            <table class="table">
              <thead class="thead-dark">
                <tr>
                  <th class="wrap_content">Waktu</th>
                  <th class="wrap_content">Pegawai</th>
                  <th>Alasan Ubah</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btnProsesCheckIn"><i class="fa fa-check"></i> Check In</button>
        <button type="button" class="btn btn-info" id="btnSimpan"><i class="fa fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<div id="form-transcode" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Pilih Transaksi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-4 form-group">
            <label for="txt_transcode">Transcode</label>
            <select class="form-control uppercase" id="txt_transcode"></select>
          </div>
          <div class="col-8 form-group">
            <label for="txt_trans_desc">Deskripsi</label>
            <input type="text" class="form-control" id="txt_trans_desc" />
          </div>
          <div class="col-4 form-group">
            <label for="txt_trans_value">Nilai</label>
            <input type="text" class="form-control" id="txt_trans_value" />
          </div>
          <div class="col-2">
            <label>Tax</label>
            <h4><i id="isTax" class="text-danger fa fa-times"></i></h4>
          </div>
          <div class="col-2">
            <label>Service</label>
            <h4><i id="isService" class="text-danger fa fa-times"></i></h4>
          </div>
          <div class="col-4 form-group">
            <label for="txt_trans_value_applied">After Tax/Service</label>
            <input type="text" class="form-control" id="txt_trans_value_applied" readonly />
          </div>
          <div class="col-12 form-group">
            <label for="txt_trans_remark">Remark</label>
            <textarea class="form-control" id="txt_trans_remark"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
        <button type="button" class="btn btn-info" id="btnAddTransSet">Tambah Transaksi</button>
      </div>
    </div>
  </div>
</div>