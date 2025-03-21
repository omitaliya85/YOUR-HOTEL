$(document).ready(function () {
  loadRooms("all", 1); // Initial Load

  // Filter Rooms
  $("#filterStatus").on("change", function () {
    let status = $(this).val();
    loadRooms(status, 1);
  });

  // Load Rooms with AJAX
  function loadRooms(status, page) {
    $.ajax({
      url: "fetch_rooms.php",
      type: "GET",
      data: { status: status, page: page },
      success: function (response) {
        let result = JSON.parse(response);
        $("#room-container").html(result.rooms);
        $("#pagination").html(result.pagination);
      },
      error: function () {
        alert("⚠️ Failed to load rooms. Try again.");
      },
    });
  }

  // Handle Pagination
  $(document).on("click", ".pagination-btn", function () {
    let page = $(this).data("page");
    let status = $("#filterStatus").val();
    loadRooms(status, page);
  });
});
