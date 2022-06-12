<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
  $(function() {
    var id = <?php echo json_encode($_GET['id']); ?>;
    var selectedOutlet = id;
    var nama = <?php echo json_encode($_GET['nama']); ?>;
    var grouperItem = {};
    var selectedTable = "";
    var selectedOrder = "";
    $("#nama_outlet").html(nama);
    $("#search").keyup(function() {
      reload_item(id);
    });

    var hisOrder = $("#oldOrder").DataTable({
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
          d.request = "outlet_order_history";
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
            return "<b class=\"wrap_content\">" + row.nomor + "</b>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<b class=\"wrap_content\">" + row.created_at + "</b>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            var typer = {
              gl: "Guest Ledger",
              cl: "City Ledger",
              cc: "Credit Card",
              cash: "Cash"
            };
            var setter = row.charge_method.split(",");
            var setCap = "";
            for (var ax in setter) {
              //setCap += "<span style=\"display: block\">" + typer[setter[ax]] + "<b class=\"pull-right\">" + number_format(row[setter[ax] + '_pay'], 2, ".", ",") + "</b></span>";
              setCap += "<span style=\"display: block\">" + typer[setter[ax]] + "</span>";
            }
            return "<span class=\"wrap_content\">" + setCap + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<h6 class=\"text-right number_style\" style=\"text-align: right !important;\">" + number_format(row.total, 2, ".", ",") + "</h6>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
              "<button id=\"old_order_preview_" + row.uid + "\" isHIs=\"1\" class=\"btn btn-info btn-sm btn-preview-order\">" +
              "<span><i class=\"fa fa-eye\"></i> Preview</span>" +
              "</button>" +
              "</div>";
          }
        }
      ]
    });

    var curOrder = $("#currentOrder").DataTable({
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
          d.request = "outlet_order_current";
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
            return "<h6 class=\"wrap_content\">" + row.nomor + "</h6>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<h4>" + ((row.meja !== null) ? row.meja.kode : "-") + "</h4>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<h6>" + row.nama + "</h6> ";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
              "<button id=\"order_preview_" + row.uid + "\" class=\"btn btn-info btn-sm btn-preview-order\">" +
              "<span><i class=\"fa fa-eye\"></i> Preview</span>" +
              "</button>" +
              "<button id=\"order_delete_" + row.uid + "\" class=\"btn btn-info btn-sm btn-delete-order\">" +
              "<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
              "</button>" +
              "</div>";
          }
        }
      ]
    });

    $("body").on("click", ".btn-preview-order", function() {
      var isHIs = $(this).attr("isHIs");
      var checkIsHis = true;
      if (typeof isHIs !== 'undefined' && isHIs !== false) {
        checkIsHis = true;
        $("#btnCheckOut").hide();
        $(".orderEditTool").hide();
      } else {
        checkIsHis = false;
        $("#btnCheckOut").show();
        $(".orderEditTool").show();
      }

      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];
      selectedOrder = uid;
      reload_detail_order_list({}, uid);
    });

    function reload_detail_order_list(grouperItem, uid) {
      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Outlet/order_detail/" + uid,
        type: "GET",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        success: function(response) {
          var data = response.response_package.response_data[0];
          $("#orderDetailPreview tbody tr").remove();
          $("#previewBill").modal("show");
          $("#cap_order_num").html("<b class=\"wrap_content\">" + data.nomor + "</b>");
          $("#cap_table").html(data.meja.kode);
          $("#cap_waitress").html(data.nama);
          var detail = data.detail;
          var autonum = 1;
          var total = 0;
          for (var a in detail) {
            if (grouperItem[detail[a].item] === undefined) {
              grouperItem[detail[a].item] = {
                nama: detail[a].nama,
                price: detail[a].price,
                qty: 0,
                subtotal: 0
              }
            }

            grouperItem[detail[a].item].qty += parseFloat(detail[a].qty);
            grouperItem[detail[a].item].subtotal += parseFloat(detail[a].subtotal);
          }
          for (var a in grouperItem) {
            total += parseFloat(grouperItem[a].subtotal);
            $("#orderDetailPreview tbody").append("<tr class=\"item_get_set\">" +
              "<td class=\"autonum\">" + autonum + "</td>" +
              "<td><h6>" + grouperItem[a].nama + "</h6></td > " +
              "<td class=\"number_style\">" + grouperItem[a].qty + "</td>" +
              "<td class=\"number_style\">" + number_format(grouperItem[a].price, 2, ".", ",") + "</td>" +
              "<td setter=\"" + grouperItem[a].subtotal + "\" class=\"number_style\">" + number_format(grouperItem[a].subtotal, 2, ".", ",") + "</td>" +
              "</tr>");
            autonum++;
          }
          var tax = 10 * total / 100;
          var service = 11 * total / 100;
          $("#cap_tax_table").html("<h6 class=\"text-danger\">" + number_format(tax, 2, ".", ",") + "</h6>");
          $("#cap_service_table").html("<h6 class=\"text-danger\">" + number_format(service, 2, ".", ",") + "</h6>");
          $("#cap_total_table").html("<h5 class=\"text-info\">" + number_format((total + tax + service), 2, ".", ",") + "</h5>");
          $("#cap_total").html(number_format(total, 2, ".", ","));
          // $(".txt_co_price").each(function() {
          //   $(this).inputmask("setvalue", (total + tax + service));
          // });
          $("#txt_co_price_total").inputmask("setvalue", (total + tax + service));
          $("#txt_cash_charge").inputmask("setvalue", (total + tax + service));
        },
        error: function(response) {
          console.log(response);
        }
      });
    }

    var expiryMask = function() {
      var inputChar = String.fromCharCode(event.keyCode);
      var code = event.keyCode;
      var allowedKeys = [8];
      if (allowedKeys.indexOf(code) !== -1) {
        return;
      }

      event.target.value = event.target.value.replace(
        /^([1-9]\/|[2-9])$/g, '0$1/'
      ).replace(
        /^(0[1-9]|1[0-2])$/g, '$1/'
      ).replace(
        /^([0-1])([3-9])$/g, '0$1/$2'
      ).replace(
        /^(0?[1-9]|1[0-2])([0-9]{2})$/g, '$1/$2'
      ).replace(
        /^([0]+)\/|[0]+$/g, '0'
      ).replace(
        /[^\d\/]|^[\/]*$/g, ''
      ).replace(
        /\/\//g, '/'
      );
    }
    var splitDate = function($domobj, value) {
      var regExp = /(1[0-2]|0[1-9]|\d)\/(20\d{2}|19\d{2}|0(?!0)\d|[1-9]\d)/;
      var matches = regExp.exec(value);
      $domobj.siblings('input[name$="expiryMonth"]').val(matches[1]);
      $domobj.siblings('input[name$="expiryYear"]').val(matches[2]);
    }

    $('#txt_cc_valid').on('keyup', function() {
      expiryMask();
    });

    $('#txt_cc_valid').on('focusout', function() {
      splitDate($(this), $(this).val());
    });

    $("#txt_cash_charge").inputmask({
      alias: 'decimal',
      rightAlign: true,
      placeholder: "0.00",
      prefix: "",
      groupSeparator: ".",
      autoGroup: false,
      digitsOptional: true
    });

    $("#txt_cash_pay").inputmask({
      alias: 'decimal',
      rightAlign: true,
      placeholder: "0.00",
      prefix: "",
      groupSeparator: ".",
      autoGroup: false,
      digitsOptional: true,
      min: 0
    });

    $("#txt_cash_pay_cc").inputmask({
      alias: 'decimal',
      rightAlign: true,
      placeholder: "0.00",
      prefix: "",
      groupSeparator: ".",
      autoGroup: false,
      digitsOptional: true,
      min: 0
    });

    $("#txt_cash_pay_gl").inputmask({
      alias: 'decimal',
      rightAlign: true,
      placeholder: "0.00",
      prefix: "",
      groupSeparator: ".",
      autoGroup: false,
      digitsOptional: true,
      min: 0
    });

    $("#txt_cash_pay_cl").inputmask({
      alias: 'decimal',
      rightAlign: true,
      placeholder: "0.00",
      prefix: "",
      groupSeparator: ".",
      autoGroup: false,
      digitsOptional: true,
      min: 0
    });

    function calculate_check_out(total, pay_cash = 0, pay_cc = 0, pay_gl = 0, pay_cl = 0) {
      var price_cash = parseFloat($("#txt_co_price_1").inputmask("unmaskedvalue"));
      var price_cc = parseFloat($("#txt_co_price_2").inputmask("unmaskedvalue"));
      var price_gl = parseFloat($("#txt_co_price_3").inputmask("unmaskedvalue"));
      var price_cl = parseFloat($("#txt_co_price_4").inputmask("unmaskedvalue"));
      pay_cash = isNaN(pay_cash) ? 0 : pay_cash;
      pay_cc = isNaN(pay_cc) ? 0 : pay_cc;
      pay_gl = isNaN(pay_gl) ? 0 : pay_gl;
      pay_cl = isNaN(pay_cl) ? 0 : pay_cl;

      var sisaPay = total - (pay_cash + pay_cc + pay_gl + pay_cl);
      if (sisaPay > 0) {
        $("#txt_cash_charge").inputmask("setvalue", Math.abs(sisaPay));
      } else {
        $("#txt_cash_charge").inputmask("setvalue", sisaPay);
      }
    }

    $("#txt_cash_pay").on("keyup", function() {
      var total = parseFloat($("#txt_co_price_total").inputmask("unmaskedvalue"));
      var pay_cash = parseFloat($("#txt_cash_pay").inputmask("unmaskedvalue"));
      var pay_cc = parseFloat($("#txt_cash_pay_cc").inputmask("unmaskedvalue"));
      var pay_gl = parseFloat($("#txt_cash_pay_gl").inputmask("unmaskedvalue"));
      var pay_cl = parseFloat($("#txt_cash_pay_cl").inputmask("unmaskedvalue"));
      calculate_check_out(total, pay_cash, pay_cc, pay_gl, pay_cl);
    });

    $("#txt_cash_pay_cc").on("keyup", function() {
      var total = parseFloat($("#txt_co_price_total").inputmask("unmaskedvalue"));
      var pay_cash = parseFloat($("#txt_cash_pay").inputmask("unmaskedvalue"));
      var pay_cc = parseFloat($("#txt_cash_pay_cc").inputmask("unmaskedvalue"));
      var pay_gl = parseFloat($("#txt_cash_pay_gl").inputmask("unmaskedvalue"));
      var pay_cl = parseFloat($("#txt_cash_pay_cl").inputmask("unmaskedvalue"));
      calculate_check_out(total, pay_cash, pay_cc, pay_gl, pay_cl);
    });

    $("#txt_cash_pay_gl").on("keyup", function() {
      var total = parseFloat($("#txt_co_price_total").inputmask("unmaskedvalue"));
      var pay_cash = parseFloat($("#txt_cash_pay").inputmask("unmaskedvalue"));
      var pay_cc = parseFloat($("#txt_cash_pay_cc").inputmask("unmaskedvalue"));
      var pay_gl = parseFloat($("#txt_cash_pay_gl").inputmask("unmaskedvalue"));
      var pay_cl = parseFloat($("#txt_cash_pay_cl").inputmask("unmaskedvalue"));
      calculate_check_out(total, pay_cash, pay_cc, pay_gl, pay_cl);
    });

    $("#txt_cash_pay_cl").on("keyup", function() {
      var total = parseFloat($("#txt_co_price_total").inputmask("unmaskedvalue"));
      var pay_cash = parseFloat($("#txt_cash_pay").inputmask("unmaskedvalue"));
      var pay_cc = parseFloat($("#txt_cash_pay_cc").inputmask("unmaskedvalue"));
      var pay_gl = parseFloat($("#txt_cash_pay_gl").inputmask("unmaskedvalue"));
      var pay_cl = parseFloat($("#txt_cash_pay_cl").inputmask("unmaskedvalue"));
      calculate_check_out(total, pay_cash, pay_cc, pay_gl, pay_cl);
    });

    $(".txt_co_price").inputmask({
      alias: 'decimal',
      rightAlign: true,
      placeholder: "0.00",
      prefix: "",
      groupSeparator: ".",
      autoGroup: false,
      digitsOptional: true
    });

    reload_item(id);
    reload_table(id);

    function reload_table(id) {
      $("#table-loader").html("");
      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Outlet",
        type: "POST",
        data: {
          request: "load_table_outlet",
          outlet: id
        },
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        success: function(response) {
          var data = response.response_package.response_data;
          for (var a in data) {
            $("#table-loader").append("<li style=\"border-bottom: dashed 2px #ccc;\"><h4><b id=\"kode_meja_" + data[a].uid + "\">" + data[a].kode + "</b><button class=\"pull-right btn btn-info pick_table_set\" id=\"table_" + data[a].uid + "\">Pick</button></h4></li>");
          }
        },
        error: function(response) {
          console.log(response);
        }
      });
    }

    $("#btnProceedOrder").click(function() {
      if (selectedTable !== "") {
        Swal.fire({
          title: "Proses Order?",
          showDenyButton: true,
          confirmButtonText: "Ya",
          denyButtonText: "Tidak",
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              async: false,
              url: __HOSTAPI__ + "/Outlet",
              type: "POST",
              data: {
                request: "add_order",
                table: selectedTable,
                outlet: id,
                item: selectedItem
              },
              beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
              },
              success: function(response) {
                console.log(response);
                if (response.response_package.response_result > 0) {
                  $("#orderList li").remove();
                  selectedItem = {};
                  selectedTable = "";
                  curOrder.ajax.reload();
                  $("#pick-table").val("");
                }
              },
              error: function(response) {
                console.log(response);
              }
            });
          }
        });
      }
    });

    $("body").on("click", ".pick_table_set", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];

      selectedTable = uid;
      $("#pick-table").val($("#kode_meja_" + uid).html());
      $("#pickTable").modal("hide");
    });

    var selectedItem = {};

    function reload_item(id) {
      $("#loader-item").html("");
      var search = $("#search").val();
      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Outlet",
        type: "POST",
        data: {
          request: "load_item_per_outlet",
          search: search,
          outlet: id
        },
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        success: function(response) {
          var data = response.response_package.response_data;
          for (var a in data) {
            $("#loader-item").append("<div style=\"padding: 0 10px\" class=\"col-4 itemSelector\" item=\"" + data[a].uid + "\" nama-item=\"" + data[a].nama + "\" price=\"" + data[a].price + "\">" +
              "<div class=\"card card-body\">" +
              "<h5 style=\"color: #000\">" + data[a].nama + "</h5>" +
              "<h6 class=\"text-right\">" + number_format(data[a].price, 2, ".", ",") + "</h6>" +
              "</div>" +
              "</div>");
          }
        },
        error: function(response) {
          console.log(response);
        }
      });
    }

    $("body").on("click", ".itemSelector", function() {
      var uid = $(this).attr("item");
      var nama = $(this).attr("nama-item");
      var price = $(this).attr("price");

      if (selectedItem[uid] === undefined) {
        selectedItem[uid] = {
          name: nama,
          price: price,
          qty: 0
        };
      }

      selectedItem[uid].qty += 1;

      reparse_order(selectedItem);
      $("#orderContainer").scrollTop($("#orderList").height());
    });

    function reparse_order(selectedItem) {
      $("#orderList li").remove();
      for (var c in selectedItem) {
        $("#orderList").append("<li cur=\"" + c + "\"><b style=\"font-size: 14pt; display:block\"><span class=\"qty\">" + selectedItem[c].qty + "</span>&times; <span class=\"item_name_list\">" + selectedItem[c].name + "</span></b><span style=\"display: block\" class=\"item_price_list text-right\">" + number_format(selectedItem[c].price * selectedItem[c].qty, 2, ".", ",") + "</span></li>");
      }
    }

    var selectedCur = "";

    $("#txt_gl_room").select2({
      minimumInputLength: 1,
      "language": {
        "noResults": function() {
          return "Kamar tidak ditemukan";
        }
      },
      placeholder: "Cari Kamar",
      dropdownParent: $("#orderCheckOut"),
      ajax: {
        dataType: "json",
        headers: {
          "Authorization": "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
          "Content-Type": "application/json",
        },
        url: __HOSTAPI__ + "/Reservasi/search_reserv",
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
                text: "#Kamar - " + item.nomor,
                id: item.uid,
                nama_depan: item.nama_depan,
                nama_belakang: item.nama_belakang,
                panggilan: item.nama_panggilan,
                phone: item.phone,
                email: item.email
              }
            })
          };
        }
      }
    }).addClass("form-control").on("select2:select", function(e) {
      var data = e.params.data;
      $("#preveiewGL").fadeIn();
      $("#txt_gl_room").attr({
        "reservasi-set": data.id
      });
      $("#gl_nama").html(data.panggilan + " " + data.nama_depan + " " + data.nama_belakang);
      $("#gl_email").html(data.email);
      $("#gl_phone").html(data.phone);
    });

    $("#preveiewGL").hide();
    $("#previewCL").hide();

    $("#txt_cl_company").select2({
      minimumInputLength: 2,
      "language": {
        "noResults": function() {
          return "Perusahaan tidak ditemukan";
        }
      },
      placeholder: "Cari Perusahaan",
      dropdownParent: $("#orderCheckOut"),
      ajax: {
        dataType: "json",
        headers: {
          "Authorization": "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
          "Content-Type": "application/json",
        },
        url: __HOSTAPI__ + "/Company/list_company",
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
                text: item.kode + " - " + item.nama,
                phone: item.phone,
                email: item.email,
                id: item.uid
              }
            })
          };
        }
      }
    }).addClass("form-control").on("select2:select", function(e) {
      var data = e.params.data;
      $("#previewCL").fadeIn();
      $("#cl_nama").html(data.text);
      $("#cl_email").html(data.email);
      $("#cl_phone").html(data.phone);
    });

    $("#txt_cc_bank").select2({
      minimumInputLength: 2,
      "language": {
        "noResults": function() {
          return "Bank tidak ditemukan";
        }
      },
      placeholder: "Cari Bank",
      dropdownParent: $("#orderCheckOut"),
      ajax: {
        dataType: "json",
        headers: {
          "Authorization": "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
          "Content-Type": "application/json",
        },
        url: __HOSTAPI__ + "/Company/list_company",
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
                text: item.kode + " - " + item.nama,
                id: item.uid
              }
            })
          };
        }
      }
    }).addClass("form-control").on("select2:select", function(e) {
      var data = e.params.data;
    });

    $("body").on("click", "#orderList li", function() {
      $("#orderItem").modal("show");
      selectedCur = $(this).attr("cur");
      var qty = $(this).find("span.qty").html();
      $("#setQty").html(qty);
      var name = $(this).find("span.item_name_list").html();
      var price = $(this).find("span.item_price_list").html();
      $("#item_name_set").html(name);
      $("#item_price_set").html(price);
    });

    $("#btnIncrease").click(function() {
      if ((selectedItem[selectedCur].qty === -1)) {
        selectedItem[selectedCur].qty = 1;
      } else {
        selectedItem[selectedCur].qty += 1;
      }

      reparse_order(selectedItem);
      $("#setQty").html(selectedItem[selectedCur].qty);
      $("#item_price_set").html(number_format(selectedItem[selectedCur].qty * selectedItem[selectedCur].price));
    });

    $("#pick-table").click(function() {
      $("#pickTable").modal("show");
    });

    $("#btnDecrease").click(function() {
      // if (selectedItem[selectedCur].qty <= 1) {
      //   Swal.fire({
      //     title: "Hapus item?",
      //     showDenyButton: true,
      //     confirmButtonText: "Ya",
      //     denyButtonText: "Tidak",
      //   }).then((result) => {
      //     if (result.isConfirmed) {
      //       $("#orderList li[cur=\"" + selectedCur + "\"]").remove();
      //       delete selectedItem[selectedCur];
      //       $("#orderItem").modal("hide");
      //     }
      //   });
      // } else {
      //   selectedItem[selectedCur].qty -= 1;
      //   $("#setQty").html(selectedItem[selectedCur].qty);
      //   $("#item_price_set").html(number_format(selectedItem[selectedCur].qty * selectedItem[selectedCur].price));
      // }
      if ((selectedItem[selectedCur].qty === 1)) {
        selectedItem[selectedCur].qty = -1;
      } else {
        selectedItem[selectedCur].qty -= 1;
      }

      $("#setQty").html(selectedItem[selectedCur].qty);
      $("#item_price_set").html(number_format(selectedItem[selectedCur].qty * selectedItem[selectedCur].price));
      reparse_order(selectedItem);
    });

    $("#btnPreposting").click(function() {
      var itemSetPrint = [];
      var total = 0;
      $(".item_get_set").each(function() {
        var item = $(this).find("td:eq(1)").html();
        var qty = $(this).find("td:eq(2)").html();
        var sub = $(this).find("td:eq(4)").html();
        var subSet = $(this).find("td:eq(4)").attr("setter");
        itemSetPrint.push({
          item: item,
          qty: qty,
          sub: sub
        });
        total += parseFloat(subSet);
      });

      $.ajax({
        async: false,
        url: __HOST__ + "miscellaneous/print_template/bill_outlet.php",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        type: "POST",
        data: {
          __PC_CUSTOMER__: __PC_CUSTOMER__.toUpperCase(),
          __PC_CUSTOMER_GROUP__: __PC_CUSTOMER_GROUP__.toUpperCase(),
          __PC_CUSTOMER_ADDRESS__: __PC_CUSTOMER_ADDRESS__,
          __PC_CUSTOMER_CONTACT__: __PC_CUSTOMER_CONTACT__,
          __PC_CUSTOMER_SITE__: __PC_CUSTOMER_SITE__,
          __PC_IDENT__: __PC_IDENT__,
          __PC_CUSTOMER_EMAIL__: __PC_CUSTOMER_EMAIL__,
          __PC_CUSTOMER_ADDRESS_SHORT__: __PC_CUSTOMER_ADDRESS_SHORT__.toUpperCase(),
          pretotal: $("#cap_total").html(),
          outlet: nama,
          item: itemSetPrint,
          total: $("#cap_total_table h5").html(),
          tax: $("#cap_tax_table h6").html(),
          service: $("#cap_service_table h6").html(),
          __ME__: __MY_NAME__
        },
        success: function(response) {
          var containerItem = document.createElement("DIV");

          $(containerItem).html(response);
          $(containerItem).printThis({
            header: null,
            footer: null,
            pageTitle: "OUTLET BILL",
            afterPrint: function() {
              location.reload();
            }
          });
        },
        error: function(response) {
          //
        }
      });
    });

    $("#btnCheckOut").click(function() {
      $("#orderCheckOut").modal("show");
    });

    $("#btnProceedCheckOut").click(function() {
      var selectedPay = $("#nav_list_method li a.active").attr("method-set");
      var price = $("#txt_co_price_total").inputmask("unmaskedvalue");
      var cc_bank = $("#txt_cc_bank").val();
      var cc_bank_name = $("#txt_cc_bank option:selected").html();
      var cc_num = $("#txt_cc").val();
      var cc_valid = $("#txt_cc_valid").inputmask("unmaskedvalue");
      var gl_room = $("#txt_gl_room").attr("reservasi-set");
      var cl_com = $("#txt_cl_company").val();

      var allowPay = false;

      var cash_pay = parseFloat($("#txt_cash_pay").inputmask("unmaskedvalue"));
      var cc_pay = parseFloat($("#txt_cash_pay_cc").inputmask("unmaskedvalue"));
      var gl_pay = parseFloat($("#txt_cash_pay_gl").inputmask("unmaskedvalue"));
      var cl_pay = parseFloat($("#txt_cash_pay_cl").inputmask("unmaskedvalue"));

      if (cash_pay > 0) {
        allowPay = (parseFloat($("#txt_cash_pay").inputmask("unmaskedvalue")) > 0);
      }

      if (cc_pay > 0) {
        allowPay = (
          cc_bank !== null && cc_bank !== "" && cc_bank !== undefined &&
          cc_num !== null && cc_num !== "" && cc_num !== undefined &&
          cc_valid !== null && cc_valid !== "" && cc_valid !== undefined
        );
      }

      if (gl_pay > 0) {
        allowPay = (
          gl_room !== null && gl_room !== "" && gl_room !== undefined
        );
      }

      if (cl_pay > 0) {
        allowPay = (
          cl_com !== null && cl_com !== "" && cl_com !== undefined
        );
      }

      var chargeValue = parseFloat($("#txt_cash_charge").inputmask("unmaskedvalue"));


      allowPay = (allowPay && chargeValue <= 0);

      var remark = $("#txt_co_remark").val();

      if (allowPay) {
        Swal.fire({
          title: "Proses Check Out?",
          showDenyButton: true,
          confirmButtonText: "Ya",
          denyButtonText: "Tidak",
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              async: false,
              url: __HOSTAPI__ + "/Outlet",
              type: "POST",
              data: {
                request: "check_out_payment",
                order: selectedOrder,
                outlet: selectedOutlet,
                pay: selectedPay,
                price: price,
                cc_bank: cc_bank,
                cc_num: cc_num,
                cc_valid: cc_valid,
                gl_room: gl_room,
                cl_com: cl_com,
                cash_pay: (isNaN(cash_pay)) ? 0 : cash_pay,
                cc_pay: (isNaN(cc_pay)) ? 0 : cc_pay,
                gl_pay: (isNaN(gl_pay)) ? 0 : gl_pay,
                cl_pay: (isNaN(cl_pay)) ? 0 : cl_pay,
                remark: remark
              },
              beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
              },
              success: function(response) {
                console.log(response);
                if (response.response_package.response_result > 0) {
                  curOrder.ajax.reload();
                  hisOrder.ajax.reload();
                  selectedOrder = "";
                  $("#orderCheckOut").modal("hide");
                  $("#previewBill").modal("hide");

                  $("#txt_co_price_total").inputmask("setvalue", 0);
                  $("#txt_cc_bank").empty().trigger("change");
                  $("#txt_cc").val("");
                  $("#txt_cc_valid").inputmask("setvalue", "");
                  $("#txt_cl_company").empty().trigger("change");
                  $("#txt_cash_pay").inputmask("setvalue", 0);
                  $("#txt_cash_pay_cc").inputmask("setvalue", 0);
                  $("#txt_cash_pay_gl").inputmask("setvalue", 0);
                  $("#txt_cash_pay_cl").inputmask("setvalue", 0);
                  $("#txt_cash_charge").inputmask("setvalue", 0);
                  $("#txt_co_remark").val("");
                  $("#preveiewGL").hide();
                  $("#previewCL").hide();



                  var itemSetPrint = [];
                  var total = 0;
                  $(".item_get_set").each(function() {
                    var item = $(this).find("td:eq(1)").html();
                    var qty = $(this).find("td:eq(2)").html();
                    var sub = $(this).find("td:eq(4)").html();
                    var subSet = $(this).find("td:eq(4)").attr("setter");
                    itemSetPrint.push({
                      item: item,
                      qty: qty,
                      sub: sub
                    });
                    total += parseFloat(subSet);
                  });

                  $.ajax({
                    async: false,
                    url: __HOST__ + "miscellaneous/print_template/bill_outlet.php",
                    beforeSend: function(request) {
                      request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    type: "POST",
                    data: {
                      __PC_CUSTOMER__: __PC_CUSTOMER__.toUpperCase(),
                      __PC_CUSTOMER_GROUP__: __PC_CUSTOMER_GROUP__.toUpperCase(),
                      __PC_CUSTOMER_ADDRESS__: __PC_CUSTOMER_ADDRESS__,
                      __PC_CUSTOMER_CONTACT__: __PC_CUSTOMER_CONTACT__,
                      __PC_CUSTOMER_SITE__: __PC_CUSTOMER_SITE__,
                      __PC_IDENT__: __PC_IDENT__,
                      __PC_CUSTOMER_EMAIL__: __PC_CUSTOMER_EMAIL__,
                      __PC_CUSTOMER_ADDRESS_SHORT__: __PC_CUSTOMER_ADDRESS_SHORT__.toUpperCase(),
                      outlet: nama,
                      item: itemSetPrint,
                      pretotal: $("#cap_total").html(),
                      total: $("#cap_total_table h5").html(),
                      tax: $("#cap_tax_table h6").html(),
                      service: $("#cap_service_table h6").html(),
                      cash: cash_pay,
                      bank_card: cc_num,
                      back_name: cc_bank_name,
                      bank_value: cc_pay,
                      gl: $("#txt_gl_room option:selected").html(),
                      gl_value: gl_pay,
                      cl_value: cl_pay,
                      cl: $("#cl_nama").html(),
                      change: Math.abs(chargeValue),
                      __ME__: __MY_NAME__
                    },
                    success: function(response) {
                      var containerItem = document.createElement("DIV");

                      $(containerItem).html(response);
                      $(containerItem).printThis({
                        header: null,
                        footer: null,
                        pageTitle: "OUTLET BILL",
                        afterPrint: function() {
                          location.reload();
                        }
                      });
                    },
                    error: function(response) {
                      //
                    }
                  });
                }
              },
              error: function(response) {
                console.log(response);
              }
            });
          }
        });
      } else {
        Swal.fire(
          "Check Out",
          "Kriteria pengisian tidak memenuhi syarat",
          "warning"
        ).then((result) => {
          console.clear();
          console.log({
            pay: selectedPay,
            price: price,
            cc_bank: cc_bank,
            cc_num: cc_num,
            cc_valid: cc_valid,
            gl_room: gl_room,
            cl_com: cl_com
          });
        });
      }
    });
  });
</script>
<div id="pickTable" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Pilih Meja</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ol style="list-style-type: none;" id="table-loader"></ol>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>

<div id="previewBill" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Preview Order</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table form-mode">
          <tr>
            <td>Order</td>
            <td class="wrap_content">:</td>
            <td id="cap_order_num"></td>
            <td>Waitress</td>
            <td class="wrap_content">:</td>
            <td id="cap_waitress"></td>
          </tr>
          <tr>
            <td>Table</td>
            <td class="wrap_content">:</td>
            <td id="cap_table"></td>
            <td>Total</td>
            <td class="wrap_content">:</td>
            <td id="cap_total"></td>
          </tr>
        </table>
        <hr />
        <table class="table" id="orderDetailPreview">
          <thead class="thead-dark">
            <tr>
              <th class="wrap_content">No</th>
              <th>Item</th>
              <th class="wrap_content">Qty</th>
              <th class="wrap_content">Price</th>
              <th class="wrap_content">Subtotal</th>
            </tr>
          </thead>
          <tbody></tbody>
          <tfoot>
            <tr>
              <td colspan="4" class="text-right"><b>Tax (10%)</b></td>
              <td class="number_style" id="cap_tax_table"></td>
            </tr>
            <tr>
              <td colspan="4" class="text-right"><b>Service (11%)</b></td>
              <td class="number_style" id="cap_service_table"></td>
            </tr>
            <tr>
              <td colspan="4" class="text-right"><b>TOTAL</b></td>
              <td class="number_style" id="cap_total_table"></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">
          <i class="fa fa-arrow-left"></i> Kembali
        </button>
        <button type="button" class="btn btn-info" id="btnPreposting">
          <i class="fa fa-print"></i> Preposting
        </button>
        <button type="button" class="btn btn-success" id="btnCheckOut">
          <i class="fa fa-check"></i> Check Out
        </button>
      </div>
    </div>
  </div>
</div>

<div id="orderItem" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Edit Order</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table form-mode">
          <tr>
            <td>
              <h2 class="text-center" id="btnIncrease">
                <i class="fa fa-caret-up"></i>
              </h2>
            </td>
            <td rowspan="3">
              <h4 id="item_name_set">---------</h4>
              <h3 id="item_price_set" style="color: #ccc">---------</h3>
            </td>
          </tr>
          <tr>
            <td id="qtySet">
              <h4 class="text-center" id="setQty">0</h4>
            </td>
          </tr>
          <tr>
            <td>
              <h2 class="text-center" id="btnDecrease">
                <i class="fa fa-caret-down"></i>
              </h2>
            </td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>


<div id="orderCheckOut" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Check Out</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-12 form-group">
          <label for="txt_co_price_total">Price</label>
          <input style="font-size: 1.2rem !important;" type="text" autocomplete="off" class="form-control txt_co_price" id="txt_co_price_total" readonly />
        </div>
        <div class="col-12 form-group">
          <label for="txt_cash_charge">Sisa Bayar</label>
          <input style="font-size: 1.2rem !important;" type="text" autocomplete="off" class="form-control" id="txt_cash_charge" readonly />
        </div>
        <div class="z-0">
          <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="nav_list_method">
            <li class="nav-item">
              <a href="#tab-11" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-11" method-set="cash">
                <span class="nav-link__count">
                  <i class="fa fa-money-bill"></i>
                </span>
                Cash
              </a>
            </li>
            <li class="nav-item">
              <a href="#tab-22" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-22" method-set="cc">
                <span class="nav-link__count">
                  <i class="fa fa-credit-card"></i>
                </span>
                Credit Card
              </a>
            </li>
            <li class="nav-item">
              <a href="#tab-33" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-33" method-set="gl">
                <span class="nav-link__count">
                  <i class="fa fa-user"></i>
                </span>
                Guest Ledger
              </a>
            </li>
            <li class="nav-item">
              <a href="#tab-44" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-44" method-set="cl">
                <span class="nav-link__count">
                  <i class="fa fa-handshake"></i>
                </span>
                City Ledger
              </a>
            </li>
          </ul>
        </div>
        <div class="card card-body tab-content">
          <div class="tab-pane show active fade" id="tab-11">
            <div class="row">
              <div class="col-12 form-group">
                <label for="txt_cash_pay">Pay</label>
                <input value="0" style="font-size: 2rem !important;" type="text" autocomplete="off" class="form-control" id="txt_cash_pay" />
              </div>
            </div>
          </div>
          <div class="tab-pane show fade" id="tab-22">
            <div class="row">
              <div class="col-12 form-group">
                <label for="txt_cash_pay_cc">Pay</label>
                <input value="0" style="font-size: 2rem !important;" type="text" autocomplete="off" class="form-control" id="txt_cash_pay_cc" />
              </div>
              <div class="col-12 form-group">
                <label for="txt_cc_bank">Bank</label>
                <select class="form-control" id="txt_cc_bank"></select>
              </div>
              <div class="col-9 form-group">
                <label for="txt_cc">Credit Card Number</label>
                <input type="text" autocomplete="off" class="form-control" id="txt_cc" />
              </div>
              <div class="col-3 form-group">
                <label for="txt_cc_valid">Valid</label>
                <input type="text" autocomplete="off" class="form-control" id="txt_cc_valid" />
              </div>
            </div>
          </div>
          <div class="tab-pane show fade" id="tab-33">
            <div class="row">
              <div class="col-12 form-group">
                <label for="txt_cash_pay_gl">Pay</label>
                <input value="0" style="font-size: 2rem !important;" type="text" autocomplete="off" class="form-control" id="txt_cash_pay_gl" />
              </div>
              <div class="col-12 form-group">
                <label for="txt_gl_room">Kamar</label>
                <select class="form-control" id="txt_gl_room"></select>
              </div>
              <div class="col-12" id="preveiewGL">
                <h5 id="gl_nama"></h5>
                <h6><i class="fa fa-envelope"></i> <b id="gl_email"></b></h6>
                <h6><i class="fa fa-phone"></i> <b id="gl_phone"></b></h6>
              </div>
            </div>
          </div>
          <div class="tab-pane show fade" id="tab-44">
            <div class="row">
              <div class="col-12 form-group">
                <label for="txt_cash_pay_cl">Pay</label>
                <input value="0" style="font-size: 2rem !important;" type="text" autocomplete="off" class="form-control" id="txt_cash_pay_cl" />
              </div>
              <div class="col-12 form-group">
                <label for="txt_cl_company">Company</label>
                <select class="form-control" id="txt_cl_company"></select>
              </div>
              <div class="col-12" id="previewCL">
                <h5 id="cl_nama"></h5>
                <h6><i class="fa fa-envelope"></i> <b id="cl_email"></b></h6>
                <h6><i class="fa fa-phone"></i> <b id="cl_phone"></b></h6>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="txt_co_remark">Remark</label>
          <textarea class="form-control" id="txt_co_remark" style="min-height: 100px"></textarea>
        </div>
        <div class="alert alert-soft-warning d-flex align-items-center card-margin" role="alert">
          <i class="material-icons mr-3">error_outline</i>
          <div class="text-body">Pastikan data sudah <strong>terverifikasi dengan benar</strong>.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">
          <i class="fa fa-times"></i> Kembali
        </button>
        <button type="button" class="btn btn-success" id="btnProceedCheckOut">
          <i class="fa fa-check"></i> Proceed Check Out
        </button>
      </div>
    </div>
  </div>
</div>