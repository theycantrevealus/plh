<script type="text/javascript">
  $(function() {
    var additionalItem = {};

    $.ajax({
      url: __HOSTAPI__ + "/Code/rate_detail/" + __PAGES__[3],
      beforeSend: function(request) {
        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
      },
      type: "GET",
      success: function(response) {
        var data = response.response_package.response_data[0];
        $("#txt_kode").val(data.kode);
        $("#txt_harga").val(data.harga).inputmask({
          alias: 'decimal',
          rightAlign: true,
          placeholder: "0.00",
          prefix: "",
          groupSeparator: ".",
          autoGroup: false,
          digitsOptional: true
        });

        for (const a in data.detail) {
          if (additionalItem[data.detail[a].add_code] !== undefined) {
            additionalItem[data.detail[a].add_code] = 0;
          }

          additionalItem[data.detail[a].add_code] = data.detail[a].harga;
        }
        $("#txt_keterangan").val(data.keterangan);
      },
      error: function(response) {
        console.log(response);
      }
    });

    var tabletipe = $("#add_code").DataTable({
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
        $("td input.harga", row).val(additionalItem[data.uid]).inputmask({
          alias: 'decimal',
          rightAlign: true,
          placeholder: "0.00",
          prefix: "",
          groupSeparator: ".",
          autoGroup: false,
          digitsOptional: true
        });
      },
      "ajax": {
        url: __HOSTAPI__ + "/Code",
        type: "POST",
        headers: {
          Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
        },
        data: function(d) {
          d.request = "add_code_list";
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
            return "<span class=\"wrap_content\" id=\"kode_" + row.uid + "\"><b>" + row.kode + "</b> - " + row.nama + "</span>";
          }
        },
        {
          "data": null,
          render: function(data, type, row, meta) {
            return "<input type=\"text\" style=\"width: 300px\" class=\"form-control harga\" id=\"harga_" + row.uid + "\"/>";
          }
        }
      ]
    });

    $("body").on("keyup", ".harga", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];

      if (additionalItem[uid] !== undefined) {
        additionalItem[uid] = 0;
      }

      additionalItem[uid] = $(this).inputmask("unmaskedvalue");
    });

    $("#btnSelesai").click(function() {
      var kode = $("#txt_kode").val();
      var harga = $("#txt_harga").inputmask("unmaskedvalue");
      var keterangan = $("#txt_keterangan").val();

      $.ajax({
        url: __HOSTAPI__ + "/Code",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        type: "POST",
        data: {
          request: 'edit_rate_code',
          uid: __PAGES__[3],
          kode: kode,
          harga: harga,
          keterangan: keterangan,
          add_code: additionalItem
        },
        success: function(response) {
          if (response.response_package.response_result > 0) {
            location.href = __HOSTNAME__ + "/master/rate";
          }
        },
        error: function(response) {
          console.log(response);
        }
      });
    });
  });
</script>