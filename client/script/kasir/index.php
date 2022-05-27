<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
  $(function() {
    var selectedTransCode = "";
    var selectedDBCR = "";
    var selectedKeterangan = "";
    var selectedFolio = "";
    var selectedIsTax = "N";
    var selectedIsService = "N";

    var selectedUID = "";
    var selectedRate = "";
    var selectedTipe = "";
    var selectedKamar = "";

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
              "<button id=\"tipe_edit_" + row.reservasi + "\" class=\"btn btn-info btn-sm btn-edit-reservasi\">" +
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
            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
              "<button id=\"tipe_edit_" + row.reservasi + "\" class=\"btn btn-info btn-sm btn-edit-reservasi\">" +
              "<span><i class=\"fa fa-eye\"></i> Edit</span>" +
              "</button>" +
              "<button id=\"tipe_cetak_rc_" + row.reservasi + "\" class=\"btn btn-info btn-sm btn-cetak-rc-tipe\">" +
              "<span><i class=\"fa fa-print\"></i> Reg. Card</span>" +
              "</button>" +
              "<button id=\"tipe_entry_" + row.uid + "\" class=\"btn btn-success btn-sm btn-entry\">" +
              "<span><i class=\"fa fa-pencil-alt\"></i> Entry</span>" +
              "</button>" +
              "</div>";
          }
        }, {
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
        }
      ]
    });

    $("#table-tipez").DataTable({
      processing: true,
      serverSide: true,
      sPaginationType: "full_numbers",
      bPaginate: true,
      lengthMenu: [
        [-1],
        ["All"]
      ],
      paging: false,
      serverMethod: "POST",
      "ajax": {
        url: __HOSTAPI__ + "/Kamar",
        type: "POST",
        headers: {
          Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
        },
        data: function(d) {
          d.request = "tipe_list";
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
          return "<a href=\"#\" class=\"pilih_tipe\" id=\"tipe_" + row.uid + "\">" + row.kode + " - " + row.nama + "</a>";
        }
      }]
    });

    $('.timeset').timepicker({
      timeFormat: 'HH:mm',
      dynamic: false,
      dropdown: true,
      scrollbar: true,
      zindex: 9999999
    });

    $("#txt_card_number").inputmask({
      alias: 'decimal',
      rightAlign: false,
      prefix: "",
      autoGroup: false,
      digitsOptional: true
    });

    $("#txt_deposit").inputmask({
      alias: 'decimal',
      rightAlign: true,
      placeholder: "0.00",
      prefix: "",
      groupSeparator: ".",
      autoGroup: false,
      digitsOptional: true
    });

    $("#txt_pax").inputmask({
      alias: 'decimal',
      rightAlign: true,
      placeholder: "0.00",
      prefix: "",
      groupSeparator: ".",
      autoGroup: false,
      digitsOptional: true
    });

    $("#txt_tanggal_arrival").datepicker('setDate', new Date());

    $("#txt_rate_price").inputmask({
      alias: 'decimal',
      rightAlign: true,
      placeholder: "0.00",
      prefix: "",
      groupSeparator: ".",
      autoGroup: false,
      digitsOptional: true
    });

    $("#txt_night").inputmask({
      alias: 'decimal',
      rightAlign: true,
      placeholder: "0.00",
      prefix: "",
      groupSeparator: ".",
      autoGroup: false,
      digitsOptional: true
    }).keyup(function() {
      var first_date = $("#txt_tanggal_arrival").datepicker("getDate");
      var first_date_parsed = $.datepicker.formatDate("dd-mm-yy", first_date);

      var last_date = $("#txt_tangal_departure").datepicker("getDate");
      var last_date_parsed = $.datepicker.formatDate("dd-mm-yy", last_date);

      var currentSet = $(this).inputmask('unmaskedvalue');

      if (first_date_parsed === "" && last_date_parsed !== "") {
        $("#txt_tanggal_arrival").val("");
        $("#txt_tanggal_arrival").datepicker('setDate', new Date(last_date_parsed).getDate() - currentSet);
      }

      if (first_date_parsed !== "" && last_date_parsed === "") {
        $("#txt_tangal_departure").val("");
        $("#txt_tangal_departure").datepicker('setDate', new Date(first_date_parsed).getDate() + currentSet);
      }
    });

    function calculateDate(dateStart, dateEnd) {
      const date1 = new Date(dateStart);
      const date2 = new Date(dateEnd);
      const diffTime = Math.abs(date2 - date1);
      return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
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

    $('#txt_card_expiry').on('keyup', function() {
      expiryMask();
    });

    $('#txt_card_expiry').on('focusout', function() {
      splitDate($(this), $(this).val());
    });

    $("#txt_kamar").click(function() {
      $("#pick-kamar").modal("show");
    });

    $("#txt_tipe").click(function() {
      $("#pick-tipe").modal("show");
    });

    $("#txt_tipe").focus(function() {
      $("#pick-tipe").modal("show");
    });

    $("#txt_identity_number").click(function() {
      // $("#pick-customer").modal("show");
    });

    $("#txt_rate_code").focus(function() {
      $("#pick-rate").modal("show");
    });

    $("#txt_rate_code").click(function() {
      $("#pick-rate").modal("show");
    });

    $("body").on("click", ".pilih_kamar", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];

      selectedKamar = uid;

      $("#txt_kamar").val($(this).html());

      $("#pick-kamar").modal("hide");
    });

    $("body").on("click", ".pilih_tipe", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];

      selectedTipe = uid;

      $("#txt_tipe").val($(this).html());

      $("#pick-tipe").modal("hide");
    });

    $("body").on("click", ".pilih_rate", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];

      selectedRate = uid;

      $("#txt_rate_code").val($(this).html());
      $("#txt_rate_price").val($("#harga_rate_" + uid).html());

      $("#pick-rate").modal("hide");
    });

    $(document).on('hidden.bs.modal', '.modal',
      () => $('.modal:visible').length && $(document.body).addClass('modal-open'));

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

    var selectorKamar = $("#table-kamarz").DataTable({
      processing: true,
      serverSide: true,
      sPaginationType: "full_numbers",
      bPaginate: true,
      lengthMenu: [
        [-1],
        ["All"]
      ],
      paging: false,
      serverMethod: "POST",
      "ajax": {
        url: __HOSTAPI__ + "/Kamar",
        type: "POST",
        headers: {
          Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
        },
        data: function(d) {
          d.request = "kamar_list_status";
          d.tipe = selectedTipe;
          d.status = 'VC';
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
          return "<a href=\"#\" class=\"pilih_kamar\" id=\"kamar_" + row.uid + "\">Kamar : #" + row.nomor + "</a>";
        }
      }, {
        "data": null,
        render: function(data, type, row, meta) {
          return row.status;
        }
      }]
    });

    $("body").on("click", ".btn-edit-reservasi", function() {
      MODE = "edit";
      $("#changeLog").show();
      var id = $(this).attr("id").split("_");
      id = id[id.length - 1];
      selectedUID = id;

      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Reservasi/detail/" + id,
        type: "GET",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        success: function(response) {
          reset_form();
          $("#modal-reservasi").modal("show");
          var log_change = response.response_package.log_change;
          $("#changeLog table tbody tr").remove();
          for (var a in log_change) {

            var dataSet = JSON.parse(log_change[a].old_value);
            if (dataSet !== null) {
              $("#changeLog table tbody").append("<tr><td><span class=\"wrap_content\">" + log_change[a].logged_at + "</span></td><td><span class=\"wrap_content\">" + log_change[a].user.nama + "</span></td><td>" + dataSet.alasan_edit + "</td></tr>");
            }
          }
          var data = response.response_package.response_data[0];

          var arrival = data.check_in.split(" ");
          var departure = data.check_out.split(" ");
          var arrival_date = arrival[0].split("-");
          var departure_date = departure[0].split("-");
          var arrival_time = arrival[1].split(":");
          var departure_time = departure[1].split(":");

          $("#txt_check_in_actual").val(data.check_in_actual);

          $("#txt_tanggal_arrival").datepicker("setDate", new Date(arrival_date[0], (parseInt(arrival_date[1]) - 1), arrival_date[2]));
          $("#txt_tangal_departure").datepicker("setDate", new Date(departure_date[0], (parseInt(departure_date[1]) - 1), departure_date[2]));

          const date1 = new Date(arrival_date[1] + '/' + arrival_date[2] + '/' + arrival_date[0]);
          const date2 = new Date(departure_date[1] + '/' + departure_date[2] + '/' + departure_date[0]);
          const diffTime = Math.abs(date2 - date1);
          const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));



          $("#txt_night").inputmask("setvalue", diffDays);

          $("#txt_tipe").val(data.kode_tipe + " - " + data.nama_tipe);
          selectedTipe = data.tipe_kamar;
          selectedRate = data.rate_code;
          selectorKamar.ajax.reload();
          $("#txt_rate_code").val(data.kode_rate);
          $("#txt_status").val(data.status);
          // $("#txt_segmentasi").select2("data", {
          //   id: data.segmentasi,
          //   text: data.msscode + " - " + data.nama_segmentasi
          // });
          // $("#txt_segmentasi").append("<option value=\"" + setter.obat + "\" penjamin-list=\"" + setter.obat_detail.penjamin.join(",") + "\">" + setter.obat_detail.nama + "</option>");
          //$("#txt_segmentasi").trigger("change");
          refresh_segmentasi("#txt_segmentasi", data.segmentasi);
          if (data.segmentasi === __SEGMEN_INDIVIDUAL__) {
            $(".segmentation_non_individual").hide();
          } else {
            $(".segmentation_non_individual").show();
          }

          $("#txt_company").select2("data", {
            id: data.company,
            text: data.kode_company + " - " + data.nama_company
          });
          $("#txt_company").append("<option value=\"" + data.company + "\">" + data.kode_company + " - " + data.nama_company + "</option>");
          $("#txt_company").trigger("change");

          $("#txt_nationality").select2("data", {
            id: data.nationality,
            text: data.alpha_3_code + " - " + data.nama_nationality
          });
          $("#txt_nationality").append("<option value=\"" + data.nationality + "\">" + data.alpha_3_code + " - " + data.nama_nationality + "</option>");
          $("#txt_nationality").trigger("change");

          $("#txt_kabupaten").select2("data", {
            id: data.state,
            text: data.nama_kabupaten
          });
          $("#txt_kabupaten").append("<option value=\"" + data.state + "\">" + data.nama_kabupaten + "</option>");
          $("#txt_kabupaten").trigger("change");

          refresh_payment_method("#txt_payment_method", data.metode_payment);

          $("#txt_check_in").val(arrival_time[0] + ":" + arrival_time[1]);
          $("#txt_check_out").val(departure_time[0] + ":" + departure_time[1]);
          $("#txt_block").prop("checked", (data.block === "Y"));
          $("#txt_kamar").val("KAMAR : #" + data.kamar.nomor);
          $("#txt_deposit").inputmask("setvalue", data.deposit);
          $("#txt_identity_number").val(data.id_number);
          $("#txt_firstname").val(data.nama_depan);
          $("#txt_last_name").val(data.nama_belakang);
          $("#txt_contact").val(data.phone);
          $("#txt_email").val(data.email);
          $("#txt_address").val(data.address);
          $("#txt_pax").inputmask("setvalue", data.pax);
          $("#txt_vip").prop("checked", (data.vip === "Y"));
          $("#txt_tanggal_lahir").val(data.tanggal_lahir);
          $("#txt_address").val(data.alamat);
          $("#txt_company").val("");
          $("#txt_rate_price").inputmask("setvalue", data.rate_value);
          $("#txt_card_number").inputmask("setvalue", data.card_number);
          $("#txt_card_expiry").inputmask("setvalue", data.card_valid_until);
          $("#txt_check_in_remark").val(data.check_in_remark);
        },
        error: function(response) {
          console.log(response);
        }
      });
    });

    $("#txt_nationality").select2({
      minimumInputLength: 2,
      "language": {
        "noResults": function() {
          return "Barang tidak ditemukan";
        }
      },
      placeholder: "Cari Negara",
      dropdownParent: $("#modal-reservasi"),
      ajax: {
        dataType: "json",
        headers: {
          "Authorization": "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
          "Content-Type": "application/json",
        },
        url: __HOSTAPI__ + "/Wilayah/negara",
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

          return {
            results: $.map(data, function(item) {
              return {
                text: item.alpha_3_code + " - " + item.nationality,
                id: item.id
              }
            })
          };
        }
      }
    }).addClass("form-control").on("select2:select", function(e) {
      var data = e.params.data;
    });

    $("#txt_company").select2({
      minimumInputLength: 2,
      "language": {
        "noResults": function() {
          return "Company tidak ditemukan";
        }
      },
      placeholder: "Cari Company",
      dropdownParent: $("#modal-reservasi"),
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

    $("#txt_kabupaten").select2({
      minimumInputLength: 2,
      "language": {
        "noResults": function() {
          return "Barang tidak ditemukan";
        }
      },
      placeholder: "Cari Kabupaten/Kota",
      dropdownParent: $("#modal-reservasi"),
      ajax: {
        dataType: "json",
        headers: {
          "Authorization": "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
          "Content-Type": "application/json",
        },
        url: __HOSTAPI__ + "/Wilayah/kabupaten_free",
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

          return {
            results: $.map(data, function(item) {
              return {
                text: item.nama,
                id: item.id
              }
            })
          };
        }
      }
    }).addClass("form-control").on("select2:select", function(e) {
      var data = e.params.data;
    });

    function refresh_payment_method(target, selected = "") {
      $.ajax({
        url: __HOSTAPI__ + "/Accounting/get_payment_method",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        type: "GET",
        success: function(response) {
          $(target).find("option").remove();
          var data = response.response_package.response_data;
          for (var a in data) {
            $(target).append("<option " + (selected === data[a].uid ? "selected=\"selected\"" : "") + " value=\"" + data[a].uid + "\">" + data[a].kode + " - " + data[a].keterangan + "</option>");
          }
          $(target).select2();
        },
        error: function(response) {
          console.log(response);
        }
      });
    }

    function loadTermSelectBox(selector, id_term) {
      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Terminologi/terminologi-items/" + id_term,
        type: "GET",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        success: function(response) {
          var MetaData = response.response_package.response_data;

          if (MetaData !== "") {
            if (selector === "warganegara") {
              for (i = 0; i < MetaData.length; i++) {
                var selection = document.createElement("OPTION");
                if (MetaData[i].id === __WNI__) {
                  $(selection).attr({
                    "selected": "selected"
                  });
                }
                $(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);
                $("#" + selector).append(selection);
              }
              //$("#" + selector).val(__WNI__).trigger("change");
            } else {
              for (i = 0; i < MetaData.length; i++) {
                var selection = document.createElement("OPTION");

                $(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);
                $("#" + selector).append(selection);
              }
            }
          }

        },
        error: function(response) {
          console.log(response);
        }
      });
    }

    function reset_form() {
      $("#txt_tanggal_arrival").datepicker("setDate", new Date());
      $("#txt_tangal_departure").val("");

      $("#txt_check_in").val("");
      $("#txt_check_out").val("");
      $("#txt_block").prop("checked", false);
      $("#txt_kamar").val("");
      $("#txt_deposit").inputmask("setvalue", 0);
      $("#txt_identity_number").val("");
      $("#txt_firstname").val("");
      $("#txt_last_name").val("");
      $("#txt_contact").val("");
      $("#txt_email").val("");
      $("#txt_address").val("");
      $("#txt_pax").inputmask("setvalue", 0);
      $("#txt_vip").prop("checked", false);
      $("#txt_tanggal_lahir").val("");
      $("#txt_company").val("");
      $("#txt_rate_price").inputmask("setvalue", 0);
      $("#txt_card_number").inputmask("setvalue", "");
      $("#txt_card_expiry").inputmask("setvalue", "");
      $("#txt_check_in_remark").val("");
    }

    function refresh_segmentasi(target, selected = "") {
      $.ajax({
        url: __HOSTAPI__ + "/Segmentasi/get_segmentasi",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        type: "GET",
        success: function(response) {
          $(target).find("option").remove();
          var data = response.response_package.response_data;
          for (var a in data) {
            $(target).append("<option " + (selected === data[a].uid ? "selected=\"selected\"" : "") + " value=\"" + data[a].uid + "\">" + data[a].msscode + " - " + data[a].deskripsi + "</option>");
          }
          $(target).select2();
        },
        error: function(response) {
          console.log(response);
        }
      });
    }

    $("body").on("click", ".btn-cetak-rc-tipe", function() {
      var id = $(this).attr("id").split("_");
      id = id[id.length - 1];
      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Reservasi/detail/" + id,
        type: "GET",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        success: function(response) {
          console.log(response);
          var log_change = response.response_package.log_change;
          var data = response.response_package.response_data[0];

          var arrival = data.check_in.split(" ");
          var departure = data.check_out.split(" ");
          var arrival_date = arrival[0].split("-");
          var departure_date = departure[0].split("-");
          var arrival_time = arrival[1].split(":");
          var departure_time = departure[1].split(":");
          var final_arrival = new Date(arrival_date[0], (parseInt(arrival_date[1]) - 1), arrival_date[2]);
          var final_departure = new Date(departure_date[0], (parseInt(departure_date[1]) - 1), departure_date[2]);

          const date1 = new Date(arrival_date[1] + '/' + arrival_date[2] + '/' + arrival_date[0]);
          const date2 = new Date(departure_date[1] + '/' + departure_date[2] + '/' + departure_date[0]);
          const diffTime = Math.abs(date2 - date1);
          const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

          var final_night = diffDays;
          var final_tipe = data.kode_tipe + " - " + data.nama_tipe;
          var final_rate = data.kode_rate;
          var final_rate_value = data.rate_value;
          var final_status = data.status;
          var final_company = data.kode_company + " - " + data.nama_company;
          var final_nationality = data.alpha_3_code + " - " + data.nama_nationality;
          var final_kabupaten = data.nama_kabupaten;
          var final_kamar = data.kamar.nomor;
          var final_checkin = arrival_time[0] + ":" + arrival_time[1];
          var final_checkout = departure_time[0] + ":" + departure_time[1];
          var final_block = data.block;
          var final_deposit = data.deposit;
          var final_ktp = data.id_number;
          var final_nama_depan = data.nama_depan;
          var final_nama_belakang = data.nama_belakang;
          var final_phone = data.phone;
          var final_email = data.email;
          var final_address = data.address;
          var final_pax = data.pax;
          var final_vip = data.vip;
          var final_tgl_lahir = data.tanggal_lahir;
          var final_alamat = data.alamat;
          var final_card = data.card_number;
          var final_card_valid = data.card_valid_until;
          var final_check_in_remark = data.check_in_remark;
          var final_payment = data.kode_payment + " " + data.ket_payment;


          $.ajax({
            async: false,
            url: __HOST__ + "miscellaneous/print_template/regis_card.php",
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
              final_arrival: new Date(arrival_date[0], (parseInt(arrival_date[1]) - 1), arrival_date[2]),
              final_departure: new Date(departure_date[0], (parseInt(departure_date[1]) - 1), departure_date[2]),
              final_res_no: data.no_reservasi,
              final_panggilan: data.nama_panggilan,
              final_arrival_date: data.check_in_date,
              final_kamar: data.kamar.nomor,
              final_departure_date: data.check_out_date,
              final_night: diffDays,
              final_tipe: data.kode_tipe + " - " + data.nama_tipe,
              final_rate: data.kode_rate,
              final_rate_value: data.rate_value,
              final_status: data.status,
              final_company: data.kode_company + " - " + data.nama_company,
              final_nationality: data.alpha_3_code + " - " + data.nama_nationality,
              final_kabupaten: data.nama_kabupaten,
              final_checkin: arrival_time[0] + ":" + arrival_time[1],
              final_checkout: departure_time[0] + ":" + departure_time[1],
              final_block: data.block,
              final_deposit: data.deposit,
              final_ktp: data.id_number,
              final_nama_depan: data.nama_depan,
              final_nama_belakang: data.nama_belakang,
              final_nama_guest: data.nama_depan + " " + data.nama_belakang,
              final_phone: data.phone,
              final_email: data.email,
              final_address: data.address,
              final_pax: data.pax,
              final_vip: data.vip,
              final_tgl_lahir: data.tanggal_lahir,
              final_alamat: data.alamat,
              final_card: data.card_number,
              final_card_valid: data.card_valid_until,
              final_check_in_remark: data.check_in_remark,
              final_payment: data.kode_payment + " - " + data.ket_payment,
              __ME__: __MY_NAME__
            },
            success: function(response) {
              var containerItem = document.createElement("DIV");

              $(containerItem).html(response);
              $(containerItem).printThis({
                header: null,
                footer: null,
                pageTitle: "Registration Card",
                afterPrint: function() {
                  //
                }
              });
            },
            error: function(response) {
              //
            }
          });
        },
        error: function(response) {
          console.log(response);
        }
      });
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
          $("#cap_check_in").html(data.check_in);
          $("#cap_check_out").html(data.check_out);
          $("#cap_alamat").html(data.alamat);
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
              "<td kode=\"" + data[a].transcode.kode + "\" class=\"text-right\" apply-tax=\"" + data[a].transcode.apply_tax + "\" apply-service=\"" + data[a].transcode.apply_service + "\"><b class=\"wrap_content\">" + data[a].transcode.kode + "</b></td>" +
              "<td>" + ((data[a].deskripsi === undefined || data[a].deskripsi === null) ? data[a].transcode.keterangan : data[a].deskripsi) + "</td>" +
              "<td>" + data[a].remark + "</td>" +
              "<td><span class=\"wrap_content debit\" set-data=\"" + ((data[a].transcode.dbcr === "D") ? priceSet : 0) + "\">" + ((data[a].transcode.dbcr === "D") ? price : "") + "</span></td>" +
              "<td><span class=\"wrap_content kredit\" set-data=\"" + ((data[a].transcode.dbcr === "K") ? priceSet : 0) + "\">" + ((data[a].transcode.dbcr === "K") ? price : "") + "</span></td>" +
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


    $("#btnSimpan").click(function() {
      var first_date = $("#txt_tanggal_arrival").datepicker("getDate");
      var arrival = $.datepicker.formatDate("dd-mm-yy", first_date);

      var last_date = $("#txt_tangal_departure").datepicker("getDate");
      var departure = $.datepicker.formatDate("dd-mm-yy", last_date);

      var check_in = $("#txt_check_in").val();
      var check_out = $("#txt_check_out").val();
      var status = $("#txt_status").val();
      var tipe_kamar = selectedTipe;
      var block = ($("#txt_block").is(":checked")) ? "Y" : "N";
      var kamar = $("#txt_kamar").val();
      var deposit = $("#txt_deposit").inputmask("unmaskedvalue");
      var id_number = $("#txt_identity_number").val();
      var title = $("#panggilan").val();
      var first_name = $("#txt_firstname").val();
      var last_name = $("#txt_last_name").val();
      var contact = $("#txt_contact").val();
      var email = $("#txt_email").val();
      var address = $("#txt_address").val();
      var pax = $("#txt_pax").inputmask("unmaskedvalue");
      var vip = ($("#txt_vip").is(":checked")) ? "Y" : "N";
      var tanggal_lahir = $("#txt_tanggal_lahir").val();
      var company = $("#txt_company").val();
      var rate_code = selectedRate;
      var rate_value = $("#txt_rate_price").inputmask("unmaskedvalue");
      var payment_method = $("#txt_payment_method").val();
      var card_number = $("#txt_card_number").inputmask("unmaskedvalue");
      var card_valid = $("#txt_card_expiry").inputmask("unmaskedvalue");
      var segmentasi = $("#txt_segmentasi").val();
      var nationality = $("#txt_nationality").val();
      var state = $("#txt_kabupaten").val();
      var check_in_remark = $("#txt_check_in_remark").val();

      Swal.fire({
        title: 'Alasan Perubahan',
        input: 'textarea'
      }).then(function(result) {
        if (result.value) {
          $.ajax({
            async: false,
            url: __HOSTAPI__ + "/Reservasi",
            type: "POST",
            data: {
              request: 'edit_reservasi',
              uid: selectedUID,
              alasan_ubah: result.value,
              arrival: arrival,
              departure: departure,
              check_in: check_in,
              check_out: check_out,
              status: status,
              tipe_kamar: tipe_kamar,
              block: block,
              kamar: kamar,
              deposit: deposit,
              id_number: id_number,
              pax: pax,
              id_number: id_number,
              title: title,
              first_name: first_name,
              last_name: last_name,
              contact: contact,
              email: email,
              address: address,
              vip: vip,
              tanggal_lahir: tanggal_lahir,
              company: company,
              rate_code: rate_code,
              rate_value: rate_value,
              payment_method: payment_method,
              card_number: card_number,
              card_valid: card_valid,
              segmentasi: segmentasi,
              nationality: nationality,
              state: state,
              check_in_remark: check_in_remark
            },
            beforeSend: function(request) {
              request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response) {
              console.log(response);
              if (response.response_package.response_result > 0) {
                $("#modal-reservasi").modal("hide");
                DTRE.ajax.reload();
                reset_form();
                selectedRate = "";
                selectedTipe = "";
              } else {
                console.log(response);
              }
            },
            error: function(response) {}
          });
        }
      });
    });
    $.fn.modal.Constructor.prototype._enforceFocus = function() {};

    $("#btnPrintDetail").click(function() {
      var room = $("#cap_room_number").html();
      var folio = $("#cap_folio").html();
      var check_in = $("#cap_check_in").html();
      var check_out = $("#cap_check_out").html();
      var guest = $("#cap_guest").html();
      var address = $("#cap_alamat").html();

      var tableList = [];
      var totalCharges = 0;
      var totalDep = 0;
      var totalPay = 0;
      var totalRetDep = 0;
      $("#entry-trans tbody tr").each(function(e) {
        var date = $(this).find("td:eq(0)").html();
        var trans = $(this).find("td:eq(1)").html();
        var desc = $(this).find("td:eq(2)").html();
        var remark = $(this).find("td:eq(3)").html();
        var charge = $(this).find("td:eq(4) span.debit").attr("set-data");
        var credit = $(this).find("td:eq(5) span.kredit").attr("set-data");
        var tax = $(this).find("td:eq(1)").attr("apply-tax");
        var service = $(this).find("td:eq(1)").attr("apply-service");
        var balance = 0;


        tableList.push({
          date: date,
          trans: trans,
          desc: desc,
          charge: charge,
          credit: credit,
          balance: balance,
          tax: tax,
          service: service
        });

        if ($(this).find("td:eq(1)").attr("kode") === 'DEP') {
          totalDep += parseFloat(credit);
        } else if ($(this).find("td:eq(1)").attr("kode") === 'CR') {
          totalPay += parseFloat(credit);
        } else if ($(this).find("td:eq(1)").attr("kode") === 'REC') {
          totalRetDep += parseFloat(credit);
        } else {
          totalCharges += parseFloat(charge);
        }



      });

      $.ajax({
        async: false,
        url: __HOST__ + "miscellaneous/print_template/bill_detail.php",
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
          folio: folio,
          kamar: room,
          check_in: check_in,
          check_out: check_out,
          guest: guest,
          address: address,
          finalData: tableList,
          total_charges: totalCharges,
          ret_dep: totalRetDep,
          adv_dep: totalDep,
          pay_received: totalPay,
          __ME__: __MY_NAME__
        },
        success: function(response) {
          var containerItem = document.createElement("DIV");

          $(containerItem).html(response);
          $(containerItem).printThis({
            header: null,
            footer: null,
            pageTitle: "Registration Card",
            afterPrint: function() {
              //
            }
          });
        },
        error: function(response) {
          //
        }
      });
    });

    $("#btnPrintStandard").click(function() {});
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
              <td>Check In</td>
              <td class="wrap_content">:</td>
              <td id="cap_check_in"></td>
              <td>Check Out</td>
              <td class="wrap_content">:</td>
              <td id="cap_check_out"></td>
              <td>Alamat</td>
              <td class="wrap_content">:</td>
              <td id="cap_alamat"></td>
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
        <button type="button" class="btn btn-info" id="btnPrintDetail"><i class="fa fa-print"></i> Detail</button>
        <button type="button" class="btn btn-info" id="btnPrintStandard"><i class="fa fa-print"></i> Standard</button>
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

<div id="pick-customer" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Pilih Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-12">
          <table id="table-customerz" class="table">
            <thead class="thead-dark">
              <tr>
                <th class="wrap_content">Customer</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>

<div id="pick-kamar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Pilih Kamar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-12">
          <table id="table-kamarz" class="table">
            <thead class="thead-dark">
              <tr>
                <th class="wrap_content">Nomor</th>
                <th class="wrap_content">Status</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>

<div id="pick-tipe" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Pilih Tipe</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="table-tipez" class="table">
          <thead class="thead-dark">
            <tr>
              <th class="wrap_content">Pilih Tipe</th>
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