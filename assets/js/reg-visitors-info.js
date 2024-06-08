const reg_filePath = "database/reg-visitors-info.php";

// This function is to get the info.
function getInfo() {
  fetch(reg_filePath, {
    method: "GET",
  })
    .then((response) => response.text())
    .then((text) => {
      console.log("Raw response text:", text); // Log the raw response text
      return JSON.parse(text); // Parse the raw text as JSON
    })
    .then((data) => {
      displayInfo(data);
    })
    .catch((error) => console.error("Error:", error));
}

// Function to insert info into the table
function displayInfo(data) {
  const tbody = document.querySelector(".reg-table tbody");
  tbody.innerHTML = ""; // Clear existing rows

  data.forEach((visitor) => {
    const row = document.createElement("tr");
    row.innerHTML = `
            <td>${visitor.reg_id}</td>
            <td>${visitor.fullname}</td>
            <td>${visitor.designation}</td>
            <td>${visitor.rank}</td>
            <td>${visitor.unit}</td>
            <td>${visitor.contact_no}</td>
            <td>${visitor.purpose_visit}</td>
            <td>${visitor.date}</td>
            <td>${visitor.time_in}</td>
            <td>${visitor.time_out}</td>
            <td>
                <div class="dropdown-container">
                    <button class="dropdown-button">Action</button>
                    <div class="dropdown-content" style="display: none;">
                        <button data-regid="${visitor.reg_id}" class="edit-button">Edit</button>
                    </div>
                </div>
            </td>
        `;
    tbody.appendChild(row);
  });

  // Add event listeners to dropdown buttons in the regular table
  const dropdownButtonsReg = document.querySelectorAll(
    ".reg-table-container .dropdown-button"
  );
  dropdownButtonsReg.forEach((button) => {
    button.addEventListener("click", function () {
      const dropdownContentReg = this.nextElementSibling;
      if (dropdownContentReg.style.display === "none") {
        dropdownContentReg.style.display = "block";
      } else {
        dropdownContentReg.style.display = "none";
      }
    });
  });

  // Adding event listener to the table body (ancestor element)
  tbody.addEventListener("click", function (event) {
    const target = event.target;

    // Check if the clicked element is an "Edit" button
    if (target.classList.contains("edit-button")) {
      const regId = target.getAttribute("data-regid"); // Get the value of the data-regid attribute
      console.log("Edit button clicked for Reg ID:", regId);
      // Here you can use the regId value for further processing, such as opening an edit modal
    }
  });

  // Add event listener for the print button
  document.addEventListener("click", function (event) {
    const printButtons = document.querySelectorAll(".print-button");
    printButtons.forEach((printButton) => {
      if (printButton.contains(event.target)) {
        console.log("Print button clicked");
        // Handle print functionality here
      }
    });
  });

  // Hide dropdown when clicking outside
  document.addEventListener("click", function (event) {
    const dropdownContents = document.querySelectorAll(".dropdown-content");
    dropdownContents.forEach((content) => {
      if (
        !content.previousElementSibling.contains(event.target) &&
        !content.contains(event.target)
      ) {
        content.style.display = "none";
      }
    });
  });
}

// Call the getInfo function to fetch and insert info
getInfo();
