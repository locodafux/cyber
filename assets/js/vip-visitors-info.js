const vip_filePath = "database/vip-visitors-info.php";

// Function to fetch VIP visitor information
function getVipInfo() {
  fetch(vip_filePath, {
    method: "GET",
  })
    .then((response) => response.text())
    .then((text) => {
      console.log("Raw response text:", text); // Log the raw response text
      return JSON.parse(text); // Parse the raw text as JSON
    })
    .then((data) => {
      console.log("Parsed data:", data); // Log the parsed data
      displayVipInfo(data);
    })
    .catch((error) => console.error("Error:", error));
}

// Function to display VIP visitor information in the VIP table
function displayVipInfo(data) {
  const tbody = document.querySelector(".vip-table tbody");
  tbody.innerHTML = ""; // Clear existing rows

  // Now when you create the table rows, ensure each "Edit" button has a data-vipid attribute
  data.forEach((visitor) => {
    const row = document.createElement("tr");
    row.innerHTML = `
            <td>${visitor.vip_id}</td>
            <td>${visitor.fullname}</td>
            <td>${visitor.designation}</td>
            <td>${visitor.rank}</td>
            <td>${visitor.unit}</td>
            <td>${visitor.contact_no}</td>
            <td>${visitor.purpose_visit}</td>
            <td>${visitor.message}</td>
            <td><img src="data:image/jpeg;base64,${visitor.signature_base64}" alt="" style="max-width: 100px;"></td>
            <td><img src="data:image/jpeg;base64,${visitor.image_base64}" alt="" style="max-width: 100px;"></td>
            <td>${visitor.date}</td>
            <td>${visitor.time_in}</td>
            <td>${visitor.time_out}</td>
            <td>
                <div class="dropdown-container">
                    <button class="dropdown-button">Action</button>
                    <div class="dropdown-content" style="display: none;">
                        <button data-vipid="${visitor.vip_id}" class="edit-button">Edit</button>
                    </div>
                      <div class="dropdown-content" style="display: none;">
                        <button data-vipid="${visitor.vip_id}" class="edit-button">Edit</button>
                    </div>
                </div>
            </td>
        `;
    tbody.appendChild(row);
  });

  // Add event listeners to dropdown buttons
  const dropdownButtons = document.querySelectorAll(
    ".vip-table-container .dropdown-button"
  );

  dropdownButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const dropdownContent = this.nextElementSibling;
      if (dropdownContent.style.display === "none") {
        dropdownContent.style.display = "block";
      } else {
        dropdownContent.style.display = "none";
      }
    });
  });
  /// Add event listener to the table body for handling clicks on edit buttons
  tbody.addEventListener("click", function (event) {
    const target = event.target;

    // Check if the clicked element is an "Edit" button
    if (target.classList.contains("edit-button")) {
      // Display the edit popup
      document.querySelector(".popup-edit").style.display = "flex";

      // Retrieve the VIP ID from the data attribute
      const vipId = target.getAttribute("data-vipid");

      // Here you can add code to populate the edit fields with the visitor's information
      // For now, let's log a message
      console.log("Edit button clicked for VIP ID:", vipId);

      // You can fetch the specific visitor's information using the VIP ID if needed
      // For example:
      fetch(`database/get-vip-info.php?vip_id=${vipId}`)
        .then((response) => response.json())
        .then((data) => {
          // Populate the edit fields with the visitor's information
          document.getElementById("edit-name").value = data.fullname;
          document.getElementById("edit-designation").value = data.designation;
          // Populate other fields similarly
        })
        .catch((error) => console.error("Error fetching VIP info:", error));
    }
  });

  // Add event listener to the "Cancel" button in the edit popup to hide the popup
  document.getElementById("cancel-edit").addEventListener("click", function () {
    document.querySelector(".popup-edit").style.display = "none";
  });

  // Add event listener to the "Cancel" button in the edit popup to hide the popup
  document.getElementById("cancel-edit").addEventListener("click", function () {
    document.querySelector(".popup-edit").style.display = "none";
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

// Call the getVipInfo function to fetch and insert VIP visitor info
getVipInfo();
