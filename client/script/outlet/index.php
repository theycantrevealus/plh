<script type="text/javascript">
  $(function() {
    $.ajax({
      async: false,
      url: __HOSTAPI__ + "/Outlet/load_my_outlet",
      type: "GET",
      beforeSend: function(request) {
        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
      },
      success: function(response) {
        $("#outlet_loader").html("");
        var data = response.response_package.response_data;
        for (var a in data) {
          $("#outlet_loader").append("<a class=\"col-4 btn btn-info btn-antrian btn-lg\" href=\"" + __HOSTNAME__ + "/pos?id=" + data[a].outlet + "&nama=" + data[a].kode + "-" + data[a].nama + "\">" +
            "<h3 class=\"text-center\" style=\"color: #fff\">" + data[a].kode + "</h3>" +
            "<h5 class=\"text-center\" style=\"color: #fff\">" + data[a].nama + "</h5>" +
            "</a>");
        }
      }
    });
  });
</script>