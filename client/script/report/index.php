<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
  $(function() {

    $("#btnCetakDSR").click(function() {
      $.ajax({
        async: false,
        url: __HOST__ + "miscellaneous/print_template/dsr.php",
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
          report: $("#report_result").html(),
          __ME__: __MY_NAME__
        },
        success: function(response) {
          var containerItem = document.createElement("DIV");

          $(containerItem).html(response);
          $(containerItem).printThis({
            header: null,
            footer: null,
            pageTitle: "Daily Sales Report - " + __CURRENT_DATE__,
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


    $.ajax({
      async: false,
      url: __HOSTAPI__ + "/DSR",
      type: "POST",
      data: {
        request: 'report_dsr'
      },
      beforeSend: function(request) {
        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
      },
      success: function(response) {
        var data = response.response_package;
        var actual = data.actual;
        var actualBuilder = {};
        for (var a in actual) {
          var metaParse = actual[a].meta_data;
          for (var b in metaParse) {
            if (actualBuilder[b] === undefined) {
              if (Array.isArray(metaParse[b])) {
                actualBuilder[b] = [];
              } else {
                actualBuilder[b] = 0;
              }
            }

            if (Array.isArray(metaParse[b])) {
              for (var c in metaParse[b]) {
                actualBuilder[b].push(metaParse[b][c]);
              }
            } else {
              actualBuilder[b] += parseFloat(metaParse[b]);
            }

          }
        }
        //============================================================
        var month = data.month;
        var monthBuilder = {};
        for (var a in month) {
          var metaParse = month[a].meta_data;
          for (var b in metaParse) {
            if (monthBuilder[b] === undefined) {
              if (Array.isArray(metaParse[b])) {
                monthBuilder[b] = [];
              } else {
                monthBuilder[b] = 0;
              }
            }

            if (Array.isArray(metaParse[b])) {
              for (var c in metaParse[b]) {
                monthBuilder[b].push(metaParse[b][c]);
              }
            } else {
              monthBuilder[b] += parseFloat(metaParse[b]);
            }

          }
        }
        //============================================================
        var year = data.year;
        var yearBuilder = {};
        for (var a in year) {
          var metaParse = year[a].meta_data;
          for (var b in metaParse) {
            if (yearBuilder[b] === undefined) {
              if (Array.isArray(metaParse[b])) {
                yearBuilder[b] = [];
              } else {
                yearBuilder[b] = 0;
              }
            }

            if (Array.isArray(metaParse[b])) {
              for (var c in metaParse[b]) {
                yearBuilder[b].push(metaParse[b][c]);
              }
            } else {
              yearBuilder[b] += parseFloat(metaParse[b]);
            }

          }
        }

        parse_closing(actualBuilder, "_actual");
        parse_closing(monthBuilder, "_month");
        parse_closing(yearBuilder, "_year");
      }
    });

    function parse_closing(data, target) {
      var outletNett = 0;
      var outlet = data.outlet;
      var outletCatAccumulate = {};

      for (var b in outlet) {
        var categories = outlet[b]['categories'];
        if (!Array.isArray(categories)) {
          if ($("#outlet_" + outlet[b].kode).length === 0) {
            $("#outlet-container").append("<tr id=\"outlet_" + outlet[b].kode + "\">" +
              "<td class=\"pad_1\" colspan=\"7\">" + outlet[b].kode + "-" + outlet[b].nama + "</td><td></td>" +
              "</tr>");
          }


          for (var c in categories) {
            if (outletCatAccumulate[c] === undefined) {
              outletCatAccumulate[c] = 0;
            }

            outletNett += categories[c].total;
            outletCatAccumulate[c] += categories[c].total;

            if (target === '_actual') {
              $("#outlet-container").append("<tr>" +
                "<td class=\"pad_2\">" + categories[c].nama + "</td>" +
                "<td class=\"number_style\" id=\"cat_" + c + "_actual\">" + number_format(categories[c].total, 2, ".", ",") + "</td>" +
                "<td class=\"number_style\" id=\"cat_" + c + "_month\">0</td>" +
                "<td class=\"number_style\" id=\"cat_" + c + "_month_budget\">0</td>" +
                "<td class=\"number_style\" id=\"cat_" + c + "_month_variance\">0</td>" +
                "<td class=\"number_style\" id=\"cat_" + c + "_year\">0</td>" +
                "<td class=\"number_style\" id=\"cat_" + c + "_year_budget\">0</td>" +
                "<td class=\"number_style\" id=\"cat_" + c + "_year_variance\">0</td>" +
                "</tr>");
            } else {
              $("#cat_" + c + target).html(number_format(outletCatAccumulate[c], 2, ".", ","));
            }
          }
        }
      }
      for (var a in data) {
        if (data[a] === undefined) {
          if (parseFloat(data[a]) > 0) {
            $("#cap_" + a + target).attr({
              valset: 0
            }).html(0).addClass("text-info").removeClass("text-muted");
          } else {
            $("#cap_" + a + target).attr({
              valset: 0
            }).html(0).addClass("text-muted").removeClass("text-info");
          }
        } else {
          if (parseFloat(data[a]) > 0) {
            $("#cap_" + a + target).attr({
              valset: data[a]
            }).html(number_format(data[a], 2, ".", ",")).addClass("text-info").removeClass("text-muted");
          } else {
            if (parseFloat(data[a]) < 0) {
              $("#cap_" + a + target).attr({
                valset: data[a]
              }).html("(" + number_format(Math.abs(data[a]), 2, ".", ",") + ")").addClass("text-danger").removeClass("text-info");
            } else {
              $("#cap_" + a + target).attr({
                valset: data[a]
              }).html(number_format(data[a], 2, ".", ",")).addClass("text-muted").removeClass("text-info");
            }
          }
        }
      }

      var outNett = outletNett / 1.21;
      $("#cap_outlet_revenue" + target).html(number_format(outletNett, 2, ".", ","));
      $("#cap_outlet_tax" + target).html(number_format(outletNett, 2, ".", ","));
      $("#cap_outlet_service" + target).html(number_format(outNett * 10 / 100, 2, ".", ","));
      $("#cap_outlet_tax" + target).html(number_format(outNett * 11 / 100, 2, ".", ","));
      $("#cap_outlet_revenue_nett" + target).html(number_format(outNett, 2, ".", ","));
    }

    // load_segmentation();
    // load_room_actual();
    // load_room_saleable();
    // load_room_sold();

    // $("#table-report tbody tr").each(function(f) {
    //   $(this).find("td").each(function(e) {
    //     var dataset = parseFloat($(this).attr("dataset"));

    //     if (!isNaN(dataset)) {
    //       if (parseFloat(dataset) <= 0) {
    //         $(this).addClass("text-muted");
    //       } else {
    //         $(this).removeClass("text-muted");
    //       }
    //     }
    //   });
    // });

    // function load_room_actual() {
    //   $.ajax({
    //     async: false,
    //     url: __HOSTAPI__ + "/Dashboard/count_room_available",
    //     type: "GET",
    //     beforeSend: function(request) {
    //       request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
    //     },
    //     success: function(response) {
    //       var data = response.response_package;
    //       $("#actual_room_available").attr("dataset", data.current).html(data.current);
    //       $("#month_room_available").attr("dataset", data.month).html(data.month);
    //       $("#year_room_available").attr("dataset", data.year).html(data.year);
    //     }
    //   });
    // }

    // function load_room_sold() {
    //   $.ajax({
    //     async: false,
    //     url: __HOSTAPI__ + "/Dashboard/count_room_sold",
    //     type: "GET",
    //     beforeSend: function(request) {
    //       request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
    //     },
    //     success: function(response) {
    //       var data = response.response_package;
    //       $("#actual_room_sold").attr("dataset", data.current).html(data.current);
    //       $("#month_room_sold").attr("dataset", data.month).html(data.month);
    //       $("#year_room_sold").attr("dataset", data.year).html(data.year);
    //     }
    //   });
    // }

    // function load_room_saleable() {
    //   $.ajax({
    //     async: false,
    //     url: __HOSTAPI__ + "/Dashboard/count_room_saleable",
    //     type: "GET",
    //     beforeSend: function(request) {
    //       request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
    //     },
    //     success: function(response) {
    //       var data = response.response_package;
    //       $("#actual_room_saleable").attr("dataset", data.current).html(data.current);
    //       $("#month_room_saleable").attr("dataset", data.month).html(data.month);
    //       $("#year_room_saleable").attr("dataset", data.year).html(data.year);
    //     }
    //   });
    // }

    // function load_segmentation() {
    //   $.ajax({
    //     url: __HOSTAPI__ + "/Dashboard/count_folio_segmentasi",
    //     beforeSend: function(request) {
    //       request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
    //     },
    //     type: "GET",
    //     success: function(response) {
    //       var data = response.response_package.response_data;
    //       console.log(data);
    //       for (var a in data) {
    //         $("#load_cust_type").append("<tr class=\"num_style\">" +
    //           "<td class=\"pad_1\">" +
    //           data[a].deskripsi.toUpperCase() +
    //           "</td>" +
    //           "<td class=\"number_style text-muted\" dataset=\"" + data[a].current + "\">" + data[a].current + "</td>" +
    //           "<td class=\"number_style text-muted\" dataset=\"" + data[a].month + "\">" + data[a].month + "</td>" +
    //           "<td class=\"number_style text-muted\">0</td>" +
    //           "<td class=\"number_style text-muted\">0</td>" +
    //           "<td class=\"number_style text-muted\" dataset=\"" + data[a].year + "\">" + data[a].year + "</td>" +
    //           "<td class=\"number_style text-muted\">0</td>" +
    //           "<td class=\"number_style text-muted\">0</td>" +
    //           "</tr>");
    //       }
    //     },
    //     error: function(response) {
    //       console.log(response);
    //     }
    //   });
    // }
  });
</script>