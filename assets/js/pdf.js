function printReport() {
  let startDate = document.getElementById("start_date").value;
  let endDate = document.getElementById("end_date").value;
  let queryString = "";
  if (startDate) queryString += "&start_date=" + startDate;
  if (endDate) queryString += "&end_date=" + endDate;
  let printWindow = window.open(
    "print_opname_produk.php?" + queryString.substring(1),
    "_blank"
  );
  printWindow.focus();
}

function generatePdf() {
  let startDate = document.getElementById("start_date").value;
  let endDate = document.getElementById("end_date").value;
  let queryString = "";
  if (startDate) queryString += "&start_date=" + startDate;
  if (endDate) queryString += "&end_date=" + endDate;
  window.location.href =
    "generate_pdf_opname_produk.php?" + queryString.substring(1);
}
