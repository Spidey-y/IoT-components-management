// show/hide add component form when button is clicked
document
  .getElementById("add-component-button")
  .addEventListener("click", function () {
    const form = document.getElementById("add-component-form");
    if (form.style.display === "none") {
      form.style.display = "block";
    } else {
      form.style.display = "none";
    }
  });

// submit add component form via AJAX
document
  .getElementById("add-component-form")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // prevent form from being submitted the traditional way

    var formData = {
      "name": $("#name").val(),
      "image": $("#image").val(),
      "status": $("#status").val(),
      "purchase-date": $("#purchase-date").val(),
      "quantity": $("#quantity").val(),
    };

    $.ajax({
      type: "POST",
      url: "add-component.php",
      data: formData,
      success: function (data) {
        console.log("success");
        console.log(data);
      },
      error: function (data) {
        console.log("error");
        console.log(data);
      },
    });
  });
  var sortAscending = true;
  function sortTable(columnIndex, headerCell) {
    // Get the table element
    var table = document.getElementsByTagName("table")[0];
  
    // Get the rows of the table
    var rows = table.rows;
  
    // Convert the rows to an array, skipping the first row (header)
    var rowArray = [].slice.call(rows, 1);
  
    // Sort the array by the values in the specified column
    rowArray.sort(function(a, b) {
      var cellA = a.cells[columnIndex].innerHTML;
      var cellB = b.cells[columnIndex].innerHTML;
      if (cellA < cellB) {
        return -1;
      }
      if (cellA > cellB) {
        return 1;
      }
      return 0;
    });
  
    // Reverse the array if the sort order is descending
    if (!sortAscending) {
      rowArray.reverse();
    }
  
    // Replace the rows in the table with the sorted array, starting from the second row
    for (var i = 0; i < rowArray.length; i++) {
      table.appendChild(rowArray[i], rows[i + 1]);
    }
  
    // Toggle the sort order
    sortAscending = !sortAscending;
  
    // Clear the sort icons from all header cells
    var headers = table.getElementsByTagName("th");
    for (var i = 0; i < headers.length; i++) {
      var span = headers[i].getElementsByTagName("span")[0];
      span.className = "fa fa-sort";
    }
  // Add the sort icon to the current header cell
  var span = headerCell.getElementsByTagName("span")[0];
  if (sortAscending) {
    span.className = "fa fa-sort-up";
  } else {
    span.className = "fa fa-sort-down";
  }
}