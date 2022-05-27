<script type="text/javascript">
  $(function() {
    load_segmentation();
    load_room_actual();
    load_room_saleable();
    load_room_sold();

    $("#table-report tbody tr").each(function(f) {
      $(this).find("td").each(function(e) {
        var dataset = parseFloat($(this).attr("dataset"));

        if (!isNaN(dataset)) {
          if (parseFloat(dataset) <= 0) {
            $(this).addClass("text-muted");
          } else {
            $(this).removeClass("text-muted");
          }
        }
      });
    });

    function load_room_actual() {
      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Dashboard/count_room_available",
        type: "GET",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        success: function(response) {
          var data = response.response_package;
          $("#actual_room_available").attr("dataset", data.current).html(data.current);
          $("#month_room_available").attr("dataset", data.month).html(data.month);
          $("#year_room_available").attr("dataset", data.year).html(data.year);
        }
      });
    }

    function load_room_sold() {
      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Dashboard/count_room_sold",
        type: "GET",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        success: function(response) {
          var data = response.response_package;
          $("#actual_room_sold").attr("dataset", data.current).html(data.current);
          $("#month_room_sold").attr("dataset", data.month).html(data.month);
          $("#year_room_sold").attr("dataset", data.year).html(data.year);
        }
      });
    }

    function load_room_saleable() {
      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Dashboard/count_room_saleable",
        type: "GET",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        success: function(response) {
          var data = response.response_package;
          $("#actual_room_saleable").attr("dataset", data.current).html(data.current);
          $("#month_room_saleable").attr("dataset", data.month).html(data.month);
          $("#year_room_saleable").attr("dataset", data.year).html(data.year);
        }
      });
    }

    function load_segmentation() {
      $.ajax({
        url: __HOSTAPI__ + "/Dashboard/count_folio_segmentasi",
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        type: "GET",
        success: function(response) {
          var data = response.response_package.response_data;
          console.log(data);
          for (var a in data) {
            $("#load_cust_type").append("<tr class=\"num_style\">" +
              "<td class=\"pad_1\">" +
              data[a].deskripsi.toUpperCase() +
              "</td>" +
              "<td class=\"number_style text-muted\" dataset=\"" + data[a].current + "\">" + data[a].current + "</td>" +
              "<td class=\"number_style text-muted\" dataset=\"" + data[a].month + "\">" + data[a].month + "</td>" +
              "<td class=\"number_style text-muted\">0</td>" +
              "<td class=\"number_style text-muted\">0</td>" +
              "<td class=\"number_style text-muted\" dataset=\"" + data[a].year + "\">" + data[a].year + "</td>" +
              "<td class=\"number_style text-muted\">0</td>" +
              "<td class=\"number_style text-muted\">0</td>" +
              "</tr>");
          }
        },
        error: function(response) {
          console.log(response);
        }
      });
    }
  });
</script>