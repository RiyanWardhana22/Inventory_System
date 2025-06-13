$(document).ready(function () {
  // Toggle sidebar on mobile
  $("#sidebarToggleTop").on("click", function () {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
  });

  // Activate tooltips
  $('[data-bs-toggle="tooltip"]').tooltip();

  // Auto-dismiss alerts after 5 seconds
  setTimeout(function () {
    $(".alert").alert("close");
  }, 5000);

  // Initialize DataTables
  if ($("#dataTable").length) {
    $("#dataTable").DataTable({
      responsive: true,
      language: {
        url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json",
      },
    });
  }

  // Show file name on file input change
  $(".custom-file-input").on("change", function () {
    let fileName = $(this).val().split("\\").pop();
    $(this).next(".custom-file-label").addClass("selected").html(fileName);
  });
});
