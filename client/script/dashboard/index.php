<script src="<?php echo __HOSTNAME__; ?>/plugins/chartjs/chart.min.js"></script>
<script type="text/javascript">
  $(function() {

    var configOption = {
      plugins: {
        legend: {
          display: true
        }
      },
      scale: {
        ticks: {
          display: false,
          maxTicksLimit: 0
        }
      }
    };

    var ctx = document.getElementById("currentStokGraph").getContext("2d");

    var myNewChart = new Chart(ctx, {
      type: "line",
      data: {
        labels: [],
        datasets: []
      },
      options: configOption
    });

    refreshData(myNewChart);

    function getDateRange(target) {
      var rangeKartu = $(target).val().split(" to ");
      if (rangeKartu.length > 1) {
        return rangeKartu;
      } else {
        return [rangeKartu, rangeKartu];
      }
    }

    $("#range_stok").change(function() {
      if (
        !Array.isArray(getDateRange("#range_stok")[0]) &&
        !Array.isArray(getDateRange("#range_stok")[1])
      ) {
        refreshData(myNewChart);
      }
    });

    function refreshData(myNewChart) {
      var forReturn;
      $.ajax({
        url: __HOSTAPI__ + "/Reservasi",
        async: false,
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        type: "POST",
        data: {
          request: "dashboard_grafik_kamar",
          from: getDateRange("#range_stok")[0],
          to: getDateRange("#range_stok")[1]
        },
        success: function(response) {
          var data = response.response_package;
          if (data !== undefined && data !== null) {
            forReturn = data;
            myNewChart.data = forReturn;
            myNewChart.update();
          }
        },
        error: function(response) {
          //
        }
      });
    }

    $.ajax({
      async: false,
      url: __HOSTAPI__ + "/Reservasi/count_reservasi",
      type: "GET",
      beforeSend: function(request) {
        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
      },
      success: function(response) {
        var data = response.response_package.response_data.length;
        $("#room_arrival").html(data);
      }
    });

    $.ajax({
      async: false,
      url: __HOSTAPI__ + "/Reservasi/count_compliment",
      type: "GET",
      beforeSend: function(request) {
        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
      },
      success: function(response) {
        var data = response.response_package;
        $("#room_compliment").html(data);
      }
    });

    $.ajax({
      async: false,
      url: __HOSTAPI__ + "/Reservasi/count_checkout",
      type: "GET",
      beforeSend: function(request) {
        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
      },
      success: function(response) {
        var data = response.response_package.response_data.length;
        $("#room_checkout").html(data);
      }
    });

    $.ajax({
      async: false,
      url: __HOSTAPI__ + "/Reservasi/count_occupied",
      type: "GET",
      beforeSend: function(request) {
        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
      },
      success: function(response) {
        var data = response.response_package.response_data.length;
        $("#room_occupied").html(data);
      }
    });

    $.ajax({
      async: false,
      url: __HOSTAPI__ + "/Reservasi/count_available",
      type: "GET",
      beforeSend: function(request) {
        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
      },
      success: function(response) {
        var data = response.response_package.response_data.length;
        $("#room_available").html(data);
      }
    });

    $.ajax({
      async: false,
      url: __HOSTAPI__ + "/Reservasi/count_vacant",
      type: "GET",
      beforeSend: function(request) {
        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
      },
      success: function(response) {
        var data = response.response_package.response_data.length;
        $("#room_vacant").html(data);
      }
    });

    $.ajax({
      async: false,
      url: __HOSTAPI__ + "/Reservasi/count_vacant_dirty",
      type: "GET",
      beforeSend: function(request) {
        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
      },
      success: function(response) {
        var data = response.response_package.response_data.length;
        $("#room_vacant_dirty").html(data);
      }
    });

    $.ajax({
      async: false,
      url: __HOSTAPI__ + "/Reservasi/count_oo",
      type: "GET",
      beforeSend: function(request) {
        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
      },
      success: function(response) {
        var data = response.response_package.response_data.length;
        $("#room_oo").html(data);
      }
    });

    $.ajax({
      async: false,
      url: __HOSTAPI__ + "/Reservasi/count_arr",
      type: "GET",
      beforeSend: function(request) {
        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
      },
      success: function(response) {
        console.log(response);
        var data = response.response_package;
        $("#arr").html(number_format(data, 2, ".", ","));
      },
      error: function(response) {
        console.log(response);
      }
    });

    $.ajax({
      async: false,
      url: __HOSTAPI__ + "/Reservasi/count_occ",
      type: "GET",
      beforeSend: function(request) {
        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
      },
      success: function(response) {
        var data = response.response_package;
        $("#occ").html(data + "%");
      }
    });






  });

  // (function() {
  //   'use strict';

  //   Charts.init();

  //   var Performance = function Performance(id) {
  //     var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'line';
  //     var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  //     options = Chart.helpers.merge({
  //       scales: {
  //         yAxes: [{
  //           ticks: {
  //             callback: function callback(a) {
  //               if (!(a % 20)) return a + " orang";
  //             }
  //           }
  //         }]
  //       },
  //       tooltips: {
  //         callbacks: {
  //           label: function label(a, e) {
  //             var t = e.datasets[a.datasetIndex].label || "",
  //               o = a.yLabel,
  //               r = "";
  //             return 1 < e.datasets.length && (r += '<span class="popover-body-label mr-auto">' + t + "</span>"), r += '<span class="popover-body-value">$' + o + "k</span>";
  //           }
  //         }
  //       }
  //     }, options);
  //     var data = {
  //       labels: ["16 Okt", "17 Okt", "18 Okt", "19 Okt", "20 Okt", "21 Okt", "22 Okt"],
  //       datasets: [{
  //         label: "Performance",
  //         data: [0, 10, 5, 15, 10, 20, 15, 25, 20, 30, 25, 40, 30, 60, 35, 80, 40, 100]
  //       }]
  //     };
  //     Charts.create(id, type, options, data);
  //   };

  //   Performance('#performanceChart');
  //   Performance('#performanceAreaChart', 'line', {
  //     elements: {
  //       line: {
  //         fill: 'start',
  //         backgroundColor: settings.charts.colors.area
  //       }
  //     }
  //   });


  //   var ObatDoughnut = function ObatDoughnut(id) {
  //     var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'doughnut';
  //     var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  //     options = Chart.helpers.merge({
  //       tooltips: {
  //         callbacks: {
  //           title: function title(a, e) {
  //             return e.labels[a[0].index];
  //           },
  //           label: function label(a, e) {
  //             var t = "";
  //             return t += '<span class="popover-body-value">' + e.datasets[0].data[a.index] + "%</span>";
  //           }
  //         }
  //       }
  //     }, options);
  //     var data = {
  //       labels: ["Amoxilin", "Panadol", "Antibiotik", "Diabesol", "Aerosol"],
  //       datasets: [{
  //         data: [20, 25, 15, 30, 10],
  //         backgroundColor: [settings.colors.success[400], settings.colors.danger[400], settings.colors.primary[500], settings.colors.gray[300], settings.colors.primary[300]],
  //         hoverBorderColor: "dark" == settings.charts.colorScheme ? settings.colors.gray[800] : settings.colors.white
  //       }]
  //     };
  //     Charts.create(id, type, options, data);
  //   }

  //   var SakitDoughnut = function SakitDoughnut(id) {
  //     var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'doughnut';
  //     var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  //     options = Chart.helpers.merge({
  //       tooltips: {
  //         callbacks: {
  //           title: function title(a, e) {
  //             return e.labels[a[0].index];
  //           },
  //           label: function label(a, e) {
  //             var t = "";
  //             return t += '<span class="popover-body-value">' + e.datasets[0].data[a.index] + "%</span>";
  //           }
  //         }
  //       }
  //     }, options);
  //     var data = {
  //       labels: ["Demam", "Flu", "Masuk Angin", "Insomnia", "Selalu lapar"],
  //       datasets: [{
  //         data: [15, 10, 20, 25, 30],
  //         backgroundColor: [settings.colors.success[400], settings.colors.danger[400], settings.colors.primary[500], settings.colors.gray[300], settings.colors.primary[300]],
  //         hoverBorderColor: "dark" == settings.charts.colorScheme ? settings.colors.gray[800] : settings.colors.white
  //       }]
  //     };
  //     Charts.create(id, type, options, data);
  //   }

  //   SakitDoughnut('#sakitDoughnutChart');


  //   var ObatDoughnut = function ObatDoughnut(id) {
  //     var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'doughnut';
  //     var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  //     options = Chart.helpers.merge({
  //       tooltips: {
  //         callbacks: {
  //           title: function title(a, e) {
  //             return e.labels[a[0].index];
  //           },
  //           label: function label(a, e) {
  //             var t = "";
  //             return t += '<span class="popover-body-value">' + e.datasets[0].data[a.index] + "%</span>";
  //           }
  //         }
  //       }
  //     }, options);
  //     var data = {
  //       labels: ["Amoxilin", "Panadol", "Antibiotik", "Diabesol", "Aerosol"],
  //       datasets: [{
  //         data: [20, 25, 15, 30, 10],
  //         backgroundColor: [settings.colors.success[400], settings.colors.danger[400], settings.colors.primary[500], settings.colors.gray[300]],
  //         hoverBorderColor: "dark" == settings.charts.colorScheme ? settings.colors.gray[800] : settings.colors.white
  //       }]
  //     };
  //     Charts.create(id, type, options, data);
  //   }

  //   ObatDoughnut('#obatDoughnutChart');
  // })();
</script>