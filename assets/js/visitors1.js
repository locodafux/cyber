document.querySelector(".vip-table-container").style.display = "none";
document.addEventListener("DOMContentLoaded", function () {
  var saveBtn = document.getElementById("edit-visitor-btn");

  saveBtn.addEventListener("click", function () {
    // Get the values from the input fields
    var regId = document.getElementById("edit-id").value;
    var name = document.getElementById("edit-reg-name").value;
    var designation = document.getElementById("edit-reg-designation").value;
    var rank = document.getElementById("edit-reg-rank").value;
    var unit = document.getElementById("edit-reg-unit").value;
    var contact = document.getElementById("edit-reg-contact").value;
    var purpose = document.getElementById("edit-reg-purpose").value;

    // Prepare the data to send to the server
    var data = {
      regId: regId,
      name: name,
      designation: designation,
      rank: rank,
      unit: unit,
      contact: contact,
      purpose: purpose,
    };

    // Send the data to the server using fetch or XMLHttpRequest
    fetch("save-reg.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Failed to save data");
        }
        // If the request was successful, show a success message or perform any other actions
        alert("Changes saved successfully!");
        location.reload();
      })
      .catch((error) => {
        // Handle errors
        console.error("Error saving data:", error);
        alert("Failed to save changes. Please try again later.");
      });
  });
});

function printReg() {
  window.open("./database/print-reg.php");
}

function printVIP() {
  window.open("./database/print-vip.php");
}
// Add event listeners to all dropdown buttons
const dropdownButtons = document.querySelectorAll(".guestType-button");
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

const popup_dropdownButtons = document.querySelectorAll(
  ".popup-guestType-button"
);
popup_dropdownButtons.forEach((button) => {
  button.addEventListener("click", function () {
    const dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "none") {
      dropdownContent.style.display = "block";
    } else {
      dropdownContent.style.display = "none";
    }
  });
});

//VIP

document.addEventListener("DOMContentLoaded", function () {
  const vipButtons = document.querySelectorAll(
    ".guest-dropdown-content .VIP-button"
  );
  vipButtons.forEach((vipButton) => {
    vipButton.addEventListener("click", function (event) {
      event.stopPropagation(); // Stop event propagation to prevent closing dropdown
      const dropdownContainer = vipButton.closest(".guest-dropdown-container");
      const guestBoxText = dropdownContainer.querySelector(".guest-box-text");
      if (guestBoxText.textContent === "Regular") {
        guestBoxText.textContent = "VIP";
        vipButton.textContent = "Regular";
        document.querySelector(".reg-table-container").style.display = "none";
        document.querySelector(".vip-table-container").style.display = "block";
      } else {
        guestBoxText.textContent = "Regular";
        vipButton.textContent = "VIP";
        document.querySelector(".reg-table-container").style.display = "block";
        document.querySelector(".vip-table-container").style.display = "none";
      }
      // Hide dropdown after selection
      const dropdownContent = dropdownContainer.querySelector(
        ".guest-dropdown-content"
      );
      dropdownContent.style.display = "none";
    });
  });

  // Hide dropdown when clicking outside
  document.addEventListener("click", function (event) {
    const dropdownContents = document.querySelectorAll(
      ".guest-dropdown-content"
    );
    dropdownContents.forEach((content) => {
      if (
        !content.previousElementSibling.contains(event.target) &&
        !content.contains(event.target)
      ) {
        content.style.display = "none";
      }
    });
  });

  // Show popup when "Add Visitor" button is clicked
  document.getElementById("addGuest").addEventListener("click", function () {
    document.querySelector(".popup").style.display = "flex";
  });

  // exit popup when "cancel" button is clicked
  document.getElementById("cancel").addEventListener("click", function () {
    document.querySelector(".popup").style.display = "none";
  });
});

// This function is for popup dropdown for adding visitors
document.addEventListener("DOMContentLoaded", function () {
  // Add event listener for the VIP button
  const vipButtons = document.querySelectorAll(
    ".popup-dropdown-content .popup-VIPVis-btn"
  );
  vipButtons.forEach((vipButton) => {
    vipButton.addEventListener("click", function (event) {
      event.stopPropagation(); // Stop event propagation to prevent closing dropdown
      const dropdownContainer = vipButton.closest(".popup-dropdown-container");
      const guestBoxText = dropdownContainer.querySelector(
        ".popup-RegularVis-btn"
      );
      if (guestBoxText.textContent === "Regular") {
        guestBoxText.textContent = "VIP";
        vipButton.textContent = "Regular";

        document.getElementById("reg_input_container").style.display = "none";
        document.getElementById("vip_input_container").style.display = "flex";
      } else {
        guestBoxText.textContent = "Regular";
        vipButton.textContent = "VIP";
        document.getElementById("reg_input_container").style.display = "flex";
        document.getElementById("vip_input_container").style.display = "none";
      }
      // Hide dropdown after selection
      const dropdownContent = dropdownContainer.querySelector(
        ".popup-dropdown-content"
      );
      dropdownContent.style.display = "none";
    });
  });

  // Hide dropdown when clicking outside
  document.addEventListener("click", function (event) {
    const dropdownContents = document.querySelectorAll(
      ".popup-dropdown-content"
    );
    dropdownContents.forEach((content) => {
      if (
        !content.previousElementSibling.contains(event.target) &&
        !content.contains(event.target)
      ) {
        content.style.display = "none";
      }
    });
  });
});

// Access the webcam
const video = document.getElementById("video");
const canvas = document.getElementById("canvas");
const captureButton = document.getElementById("take-image");
const retakeButton = document.getElementById("retake");
const doneButton = document.getElementById("done");
const imageInput = document.getElementById("image");
const imageHolder = document.querySelector(".image-holder");
const popupCamera = document.querySelector(".popup-camera");

// Get access to the camera
navigator.mediaDevices
  .getUserMedia({ video: true })
  .then((stream) => {
    video.srcObject = stream;
  })
  .catch((err) => {
    console.error("Error accessing the camera: " + err);
  });

// Capture the image
captureButton.addEventListener("click", () => {
  const context = canvas.getContext("2d");
  context.drawImage(video, 0, 0, canvas.width, canvas.height);
  const dataURL = canvas.toDataURL("image/png");
  imageInput.value = dataURL;

  // Display captured image in canvas
  canvas.style.display = "block";
  video.style.display = "none";
  captureButton.style.display = "none";
  retakeButton.style.display = "block";
  doneButton.style.display = "block";
});

// Retake the image
retakeButton.addEventListener("click", () => {
  canvas.style.display = "none";
  video.style.display = "block";
  captureButton.style.display = "block";
  retakeButton.style.display = "none";
  doneButton.style.display = "none";
});

// Done button (place captured image and close popup)
doneButton.addEventListener("click", () => {
  // Place captured image in image-holder
  const capturedImage = new Image();
  capturedImage.src = imageInput.value;
  capturedImage.alt = "Captured Image";

  // Clear any previous image and append the new one
  imageHolder.innerHTML = "";
  imageHolder.appendChild(capturedImage);

  // Close the popup
  popupCamera.style.display = "none";

  // Log captured image data URL (optional)
  console.log("Image captured and saved:", imageInput.value);
});

document.getElementById("captureButton").addEventListener("click", function () {
  document.querySelector(".popup-camera").style.display = "flex";
});

document.getElementById("uploadButton").addEventListener("click", function () {
  document.getElementById("fileInput").click();
});

document
  .getElementById("fileInput")
  .addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        const imageHolder = document.querySelector(".image-holder");
        imageHolder.innerHTML = `<img src="${e.target.result}" alt="Uploaded Image" style="max-width: 100%; border-radius: 1vh;">`;
      };
      reader.readAsDataURL(file);
    }
  });

document.getElementById("captureButton").addEventListener("click", function () {
  document.querySelector(".popup-camera").style.display = "flex";
});

const signatureCanva = document.getElementById("signatureCanvas");
const signaturePad = new SignaturePad(signatureCanva);
// Clear the signature
document
  .getElementById("clearSignatureBtn")
  .addEventListener("click", function () {
    signaturePad.clear();
  });
