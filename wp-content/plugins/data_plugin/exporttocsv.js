function exportTableToCSV(tablerRows, filename) {
	var csv = [];
  
	for (var i = 0; i < tablerRows.length; i++) {
	  var row = [];
	  var cols = tablerRows[i].querySelectorAll("th, td");
	  for (var j = 0; j < cols.length; j++) 
	  {row.push(cols[j].innerText);
	  }
	  csv.push(row.join(","));
	}
  
	// Download CSV file
	downloadCSV(csv.join("\n"), filename);
  }
  
  function downloadCSV(csv, filename) {
	var csvFile;
	var downloadLink; 
  
	// CSV file
	csvFile = new Blob([csv], { type: "text/csv" });
  
	// Download link
	downloadLink = document.createElement("a");
  
	// File name
	downloadLink.download = filename;
  
	// Create a link to the file
	downloadLink.href = window.URL.createObjectURL(csvFile);
  
	// Hide download link
	downloadLink.style.display = "none";
  
	// Add the link to DOM
	document.body.appendChild(downloadLink);
  
	// Click download link
	downloadLink.click();
  }
  
  jQuery(document).ready(function () {
	//loop through all buttons and find the
  
	  jQuery('#btn_csv_export').on("click", function () {
		exportTableToCSV(jQuery('#dataTable').find("tr"), "Table_" + 1);
	  });
  });
  