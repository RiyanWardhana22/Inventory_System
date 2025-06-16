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

// SIDEBAR
document.addEventListener("DOMContentLoaded", function () {
  const sidebarToggle = document.getElementById("sidebarToggleTop");
  const sidebar = document.querySelector(".sidebar");
  const wrapper = document.querySelector(".wrapper");

  if (sidebarToggle && sidebar && wrapper) {
    sidebarToggle.addEventListener("click", function () {
      sidebar.classList.toggle("active");
      wrapper.classList.toggle("sidebar-toggled");
    });

    document.addEventListener("click", function (e) {
      if (
        window.innerWidth <= 768 &&
        !sidebar.contains(e.target) &&
        !sidebarToggle.contains(e.target) &&
        sidebar.classList.contains("active")
      ) {
        sidebar.classList.remove("active");
        wrapper.classList.remove("sidebar-toggled");
      }
    });
  }

  const settingsCollapseLink = document.querySelector(
    '.nav-link[data-bs-toggle="collapse"]'
  );
  if (settingsCollapseLink) {
    settingsCollapseLink.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("href");
      const targetCollapse = document.querySelector(targetId);
      if (targetCollapse) {
        if (targetCollapse.classList.contains("show")) {
          targetCollapse.classList.remove("show");
          this.setAttribute("aria-expanded", "false");
          this.classList.add("collapsed");
        } else {
          targetCollapse.classList.add("show");
          this.setAttribute("aria-expanded", "true");
          this.classList.remove("collapsed");
        }
      }
    });
  }

  const activeSubmenuLink = document.querySelector(
    ".sub-menu .nav-link.active"
  );
  if (activeSubmenuLink) {
    const parentCollapse = activeSubmenuLink.closest(".collapse");
    if (parentCollapse) {
      parentCollapse.classList.add("show");
      const toggleLink = document.querySelector(
        `[href="#${parentCollapse.id}"]`
      );
      if (toggleLink) {
        toggleLink.setAttribute("aria-expanded", "true");
        toggleLink.classList.remove("collapsed");
      }
    }
  }
});
