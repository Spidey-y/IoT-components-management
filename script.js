// show/hide add component form when button is clicked
// document
//   .getElementById("add-component-button")
//   .addEventListener("click", function () {
//     const form = document.getElementById("add-component-form");
//     if (form.style.display === "none") {
//       form.style.display = "block";
//     } else {
//       form.style.display = "none";
//     }
//   });

// submit add component form via AJAX
document
  .getElementById("add-component-form")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // prevent form from being submitted the traditional way
    var formData = new FormData();
    formData.append("name", $("#name").val());
    formData.append("image", $("#image")[0].files[0]);
    formData.append("status", $("#status").val());
    formData.append("purchase_date", $("#purchase-date").val());
    formData.append("quantity", $("#quantity").val());
    if ($("#action").val() != 'edit') {
      $.ajax({
        url: "add_component.php",
        data: formData,
        cache: false,
        enctype: 'multipart/form-data',
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function (data) {
          var json = $.parseJSON(data);
          $('.success-msg').text(json.message);
          $('.success-msg').toggle();
          document.getElementById("add-component-form").reset();
          modal.style.display = "none";
        },
        error: function (data) {
          var json = $.parseJSON(data);
          $('.error-msg').text(json.message);
          $('.error-msg').toggle();
          document.getElementById("add-component-form").reset();
          modal.style.display = "none";
        },
      });
    } else {
      formData.append("id", $("#id").val());
      $.ajax({
        url: "edit_component.php",
        data: formData,
        cache: false,
        enctype: 'multipart/form-data',
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function (data) {
          console.log(data);
          var json = $.parseJSON(data);
          $('.success-msg').text(json.message);
          $('.success-msg').toggle();
          document.getElementById("add-component-form").reset();
          modal.style.display = "none";
          refreshTable();
        },
        error: function (data) {
          console.log(data);
          var json = $.parseJSON(data);
          $('.error-msg').text(json.message);
          $('.error-msg').toggle();
          document.getElementById("add-component-form").reset();
          modal.style.display = "none";
          refreshTable();
        },
      });
      $("#current_image").css("display", "none");
      $("#current_image").empty();
      $("#action").val('add');
      $('#image').prop('required', true);
    }
  });

function refreshTable() {
  $.ajax({
    type: "GET",
    url: `get_all_components.php`,
    success: function (data) {
      var json = $.parseJSON(data);
      print_results(json);
    },
  });
}
var sortAscending = true;
function sortTable(columnIndex, headerCell) {
  // Get the table element
  var table = document.getElementById("result_tbody");

  // Get the rows of the table
  var rows = table.rows;

  // Convert the rows to an array, skipping the first row (header)
  var rowArray = [].slice.call(rows, 0);

  // Sort the array by the values in the specified column
  rowArray.sort(function (a, b) {
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

// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("add-component-button");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
btn.onclick = function () {
  if (modal.style.display == "none") {
    modal.style.display = "block";
  } else {
    document.getElementById("add-component-form").reset();
    modal.style.display = "none";
    $("#action").val('add');
    $('#image').prop('required', true);
    $("#current_image").css("display", "none");
    $("#current_image").empty();
  }
}

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
  document.getElementById("add-component-form").reset();
  modal.style.display = "none";
  $("#action").val('add');
  $('#image').prop('required', true);
  $("#current_image").css("display", "none");
  $("#current_image").empty();
}



$(document).on('click', '.edits', function () {
  console.log(this.dataset.id);
  $.ajax({
    url: "get_component.php?id=" + this.dataset.id,
    data: {
      id: this.dataset.id,
    },
    method: 'GET',
    type: 'GET',
    success: function (data) {
      console.log(data);
      if (data != '{}') {
        var json = $.parseJSON(data); // create an object with the key of the array
        fillData(json);
        modal.style.display = "block";
      }
    },
    error: function (data) {
      console.log("error");
      console.log(data);
    },
  });
})

function fillData(data) {
  $("#name").val(data['name']);
  $("#id").val(data['id']);
  $("#action").val('edit');
  $("#status").val(data['status']);
  $("#purchase-date").val(data['purchase_date']);
  $("#quantity").val(data['quantity']);
  $('#image').prop('required', false);
  $("#current_image").append('<br><br><label for="image">Current Image:</label><br><img src="' + data['image'] + '" id="preview_image">')
  $("#current_image").css("display", "block");
}

$("#search_form").submit(function (event) {
  resetFilters(0);
  $.ajax({
    type: "GET",
    url: `search.php?query=${$("#search_query").val()}`,
  }).done(function (data) {
    var json = $.parseJSON(data);
    print_results(json);
  });
  event.preventDefault();
});

$("#search_query").on('input', function (event) {
  resetFilters(0);
  $.ajax({
    type: "GET",
    url: `search.php?query=${$("#search_query").val()}`,
    success: function (data) {
      var json = $.parseJSON(data);
      print_results(json);
    },
  });
  event.preventDefault();
});

function print_results(data) {
  $("#result_tbody").empty();
  if (!$.isEmptyObject(data))
    data.forEach(element => {
      var output = '  <tr>';
      output += '    <td>' + element.name + '</td>';
      output += '    <td>' + element.purchase_date + '</td>';
      output += '    <td><span class="status ' + element.status + '">' + element.status.toUpperCase() + '</span></td>';
      output += '    <td>' + element.quantity + '</td>';
      output += '    <td><a href="' + element.image + '" class="btn btn-primary"><i class="fa fa-image"></i> View Image</a></td>';
      output += '    <td>';
      output += '       <a class="edits" data-id="' + element.id + '"  class="btn btn-secondary"><i class="fa fa-edit"></i> Edit</a>';
      output += '      <a  class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>';
      output += '    </td>';
      output += '  </tr>';
      $("#result_tbody").append(output);
    });
}

$('.info-msg, .success-msg, .warning-msg, .error-msg').click(function () {
  $(this).toggle();
})

$("#filter_status").on('input', function (event) {
  var curentVal = $("#filter_status").val()
  resetFilters(1);
  $.ajax({
    type: "GET",
    url: `filter.php?status=${curentVal}`,
    success: function (data) {
      var json = $.parseJSON(data);
      print_results(json);
    },
  });
  event.preventDefault();
});

function resetFilters(opt = -1) {
  if (opt != 0) $("#search_query").val('');
  if (opt != 1) $("#filter_status").val('all');
  if (opt != 2) $("#filter_date_from").val('');
  if (opt != 2) $("#filter_date_to").val('');
}

function resetFiltersBtn(opt = -1) {
  $("#search_query").val('');
  $("#filter_status").val('all');
  $("#filter_date_from").val('');
  $("#filter_date_to").val('');
  refreshTable();
}

$("#filter_date_from").on('input', function (event) {
  var startDate = $("#filter_date_from").val()
  if ($("#filter_date_to").val() != '') {
    var endDate = $("#filter_date_to").val()
    resetFilters(2);
    $.ajax({
      type: "GET",
      url: `filter.php?start_date=${startDate}&end_date=${endDate}`,
      success: function (data) {
        var json = $.parseJSON(data);
        print_results(json);
      },
    });
    event.preventDefault();
  }
});

$("#filter_date_to").on('input', function (event) {
  var endDate = $("#filter_date_to").val()
  if ($("#filter_date_from").val() != '') {
    var startDate = $("#filter_date_from").val()
    resetFilters(2);
    $.ajax({
      type: "GET",
      url: `filter.php?start_date=${startDate}&end_date=${endDate}`,
      success: function (data) {
        var json = $.parseJSON(data);
        print_results(json);
      },
    });
    event.preventDefault();
  }
});

const downloadBtn = document.getElementById("downloadBtn");

downloadBtn.addEventListener("click", e => {
  e.preventDefault();
  downloadBtn.innerText = "Exporting file...";
  var query = $("#search_query").val();
  var status = $("#filter_status").val();
  var start_date = $("#filter_date_from").val();
  var end_date = $("#filter_date_to").val();
  fetchExcelFile("export_excel.php?query=" + query + "&status=" + status + "&start_date=" + start_date + "&end_date=" + end_date);
});

function fetchExcelFile(url) {
  fetch(url).then(res => res.blob()).then(file => {
    let tempUrl = URL.createObjectURL(file);
    const aTag = document.createElement("a");
    aTag.href = tempUrl;
    // aTag.download = url.replace(/^.*[\\\/]/, '');
    aTag.download = 'export.xlsx';
    document.body.appendChild(aTag);
    aTag.click();
    downloadBtn.innerText = "Export Data";
    URL.revokeObjectURL(tempUrl);
    aTag.remove();
  }).catch(() => {
    alert("Failed to download file!");
    downloadBtn.innerText = "Export Data";
  });
}
const wordExportBtn = document.getElementById("wordExportBtn");

wordExportBtn.addEventListener("click", e => {
  e.preventDefault();
  wordExportBtn.innerText = "Create file...";
  var student_name = $("#student_name").val();
  var checkedValuesQuery = '';
  $('.messageCheckbox:checked').each(function(i, element) {
      checkedValuesQuery+=`&devices[]=${$(element).val()}`;
  });
  fetchWordFile("export_word.php?student_name=" + student_name + checkedValuesQuery);
});

function fetchWordFile(url) {
  fetch(url).then(res => res.blob()).then(file => {
    let tempUrl = URL.createObjectURL(file);
    const aTag = document.createElement("a");
    aTag.href = tempUrl;
    // aTag.download = url.replace(/^.*[\\\/]/, '');
    aTag.download = 'decharge.docx';
    document.body.appendChild(aTag);
    aTag.click();
    wordExportBtn.innerText = "Create Decharge";
    URL.revokeObjectURL(tempUrl);
    aTag.remove();
  }).catch(() => {
    alert("Failed to download file!");
    wordExportBtn.innerText = "Create Decharge";
  });
}

var exportModal = document.getElementById("dechargeModal");

// Get the button that opens the modal
var exportBtn = document.getElementById("export-decharge-button");

var exportCloseSspan = document.getElementsByClassName("close")[1];

exportBtn.onclick = function () {
  console.log('shit');
  if (exportModal.style.display == "none") {
    exportModal.style.display = "block";
  } else {
    exportModal.style.display = "none";
  }
}

exportCloseSspan.onclick = function () {
  exportModal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
  if (event.target == modal) {
    document.getElementById("add-component-form").reset();
    modal.style.display = "none";
    $("#action").val('add');
    $('#image').prop('required', true);
    $("#current_image").css("display", "none");
    $("#current_image").empty();
  } else if (event.target == exportModal) {
    exportModal.style.display = "none";
  }
}