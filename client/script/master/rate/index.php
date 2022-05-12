<script type="text/javascript">
  $(function() {
    var MODE = "tambah",
      selectedUID;
    var tabletipe = $("#table-rate").DataTable({
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
            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
              "<a href=\"" + __HOSTNAME__ + "/master/rate/edit/" + row.uid + "\" class=\"btn btn-info btn-sm btn-edit-tipe\">" +
              "<span><i class=\"fa fa-pencil-alt\"></i> Edit</span>" +
              "</a>" +
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
        title: "Hapus rate?",
        showDenyButton: true,
        confirmButtonText: `Ya`,
        denyButtonText: `Tidak`,
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: __HOSTAPI__ + "/Code/rate/" + uid,
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
  });
</script>