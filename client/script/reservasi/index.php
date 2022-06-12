<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
  $(function() {

    var selectedTipe = "";
    var selectedRate = "";
    var selectedUID = "";
    var selectedKamar = "";
    var MODE = "tambah";

    function getDateRange(target) {
      var rangeKwitansi = $(target).val().split(" to ");
      if (rangeKwitansi.length > 1) {
        return rangeKwitansi;
      } else {
        return [rangeKwitansi, rangeKwitansi];
      }
    }

    $("#btnTambahReservasi").click(function() {
      MODE = "tambah";
      selectedKamar = "";
      selectedTipe = ""
      selectedRate = "";
      selectedUID = "";

      $("#changeLog").hide();
      $("#modal-reservasi").modal("show");
    });

    $("#range-reservasi").change(function() {
      DTRH.ajax.reload();
    });

    var DTRH = $("#table-history").DataTable({
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
          d.request = "reservasi_list_history";
          d.from = getDateRange("#range-reservasi")[0];
          d.to = getDateRange("#range-reservasi")[1];
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
            return "<span class=\"wrap_content\">" + row.no_reservasi + " " + ((row.vip === "Y") ? "<i class=\"pull-right fa fa-star text-warning\"></i>" : "") + "</span>";
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
              "<button id=\"tipe_edit_" + row.uid + "\" class=\"btn btn-info btn-sm btn-edit-reservasi\">" +
              "<span><i class=\"fa fa-eye\"></i> Edit</span>" +
              "</button>" +
              "<button id=\"tipe_delete_" + row.uid + "\" class=\"btn btn-danger btn-sm btn-delete-tipe\">" +
              "<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
              "</button>" +
              "</div>";
          }
        }
      ]
    });

    var DTRE = $("#table-tipe").DataTable({
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
              "<button id=\"tipe_edit_" + row.uid + "\" class=\"btn btn-info btn-sm btn-edit-reservasi\">" +
              "<span><i class=\"fa fa-eye\"></i> Edit</span>" +
              "</button>" +
              "<button id=\"tipe_cetak_rc_" + row.uid + "\" class=\"btn btn-info btn-sm btn-cetak-rc-tipe\">" +
              "<span><i class=\"fa fa-print\"></i> Reg. Card</span>" +
              "</button>" +
              "<button id=\"tipe_delete_" + row.uid + "\" class=\"btn btn-danger btn-sm btn-delete-tipe\">" +
              "<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
              "</button>" +
              "</div>";
          }
        }
      ]
    });

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

    $("#changeLog").hide();

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

          $("#txt_tanggal_arrival").val(convertDate(new Date(arrival_date[0], (parseInt(arrival_date[1]) - 1), arrival_date[2])));
          $("#txt_tangal_departure").val(convertDate(new Date(departure_date[0], (parseInt(departure_date[1]) - 1), departure_date[2])));

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
          $("#txt_kamar").val("");
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


    loadTermSelectBox('panggilan', 3);

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

    refresh_segmentasi("#txt_segmentasi");
    $(".segmentation_non_individual").hide();
    $("#txt_segmentasi").change(function() {
      if ($(this).val() === __SEGMEN_INDIVIDUAL__) {
        $(".segmentation_non_individual").hide();
      } else {
        $(".segmentation_non_individual").show();
      }
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

    refresh_payment_method("#txt_payment_method");


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


    $('.timeset').timepicker({
      timeFormat: 'HH:mm',
      dynamic: false,
      dropdown: true,
      scrollbar: true,
      zindex: 9999999
    });

    function convertDate(date) {
      var yyyy = date.getFullYear().toString();
      var mm = (date.getMonth() + 1).toString();
      var dd = date.getDate().toString();

      var mmChars = mm.split('');
      var ddChars = dd.split('');

      return yyyy + '-' + (mmChars[1] ? mm : "0" + mmChars[0]) + '-' + (ddChars[1] ? dd : "0" + ddChars[0]);
    }

    Date.prototype.addDays = function(days) {
      var date = new Date(this.valueOf());
      date.setDate(date.getDate() + days);
      return date;
    }


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

    $("#txt_tanggal_arrival").val(__CURRENT_DATE__);

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
      console.clear();
      var first_date = $("#txt_tanggal_arrival").val();
      var first_date_parsed = first_date.split("-");
      var currentSet = parseFloat($(this).inputmask('unmaskedvalue'));
      var setDate = new Date(first_date_parsed[0], parseFloat(first_date_parsed[1]) - 1, first_date_parsed[2]);
      $("#txt_tangal_departure").val(convertDate(setDate.addDays(currentSet)));
    });

    $("#btnProsesCheckIn").click(function() {
      // var first_date = $("#txt_tanggal_arrival").val();
      var arrival = $("#txt_tanggal_arrival").val();
      if (selectedKamar !== "") {


        Swal.fire({
          title: "Proses Check In?",
          showDenyButton: true,
          confirmButtonText: "Ya",
          denyButtonText: "Belum",
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              async: false,
              url: __HOSTAPI__ + "/Folio",
              type: "POST",
              data: {
                request: 'tambah_folio',
                reservasi: selectedUID,
                arrival: arrival,
                night: $("#txt_night").inputmask("unmaskedvalue"),
                deposit: $("#txt_deposit").inputmask("unmaskedvalue"),
                rate_value: $("#txt_rate_price").inputmask("unmaskedvalue"),
                kamar: selectedKamar
              },
              beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
              },
              success: function(response) {
                if (response.response_package.response_result > 0) {
                  $("#modal-reservasi").modal("hide");
                  DTRE.ajax.reload();
                  DTRH.ajax.reload();
                  reset_form();
                  selectedRate = "";
                  selectedTipe = "";
                  selectedKamar = "";
                  selectedUID = "";
                } else {
                  console.log(response);
                }
              },
              error: function(response) {}
            });
          }
        });
      }
    });

    $("#btnSimpan").click(function() {
      var arrival = $("#txt_tanggal_arrival").val();
      var departure = $("#txt_tangal_departure").val();

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

      if (MODE === "tambah") {
        Swal.fire({
          title: "Simpan Reservasi?",
          showDenyButton: true,
          confirmButtonText: "Ya",
          denyButtonText: "Belum",
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              async: false,
              url: __HOSTAPI__ + "/Reservasi",
              type: "POST",
              data: {
                request: 'tambah_reservasi',
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
      } else {
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
      }
    });

    $.fn.modal.Constructor.prototype._enforceFocus = function() {};

    function reset_form() {
      $("#txt_tanggal_arrival").val(__CURRENT_DATE__);
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
  });
</script>
<style>
  .ui-datepicker {
    width: auto !important;
  }
</style>
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
              <input type="date" autocomplete="off" class="form-control" id="txt_tanggal_arrival">
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
              <input type="date" autocomplete="off" class="form-control" id="txt_tangal_departure" />
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