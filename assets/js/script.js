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

  // Show file name on file input change
  $(".custom-file-input").on("change", function () {
    let fileName = $(this).val().split("\\").pop();
    $(this).next(".custom-file-label").addClass("selected").html(fileName);
  });

  // SweetAlert Delete Satuan_barang
  $(document).on("click", ".delete-btn", function (e) {
    e.preventDefault();
    const deleteUrl = $(this).data("url");

    Swal.fire({
      title: "Yakin ingin dihapus?",
      text: "Apakah anda yakin ingin menghapus data tersebut",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Ya, Hapus",
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = deleteUrl;
      }
    });
  });
});

// DATATABLES
$(document).ready(function () {
  $("#dataTable").DataTable({
    destroy: true,
    pagingType: "full_numbers",
    language: {
      lengthMenu: "Show _MENU_ entries",
      paginate: {
        next: "Next",
        previous: "Previous",
      },
    },
  });
});
