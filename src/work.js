// Get references to the upload form and file input
const uploadForm = document.querySelector('#upload form');
const fileInput = document.querySelector('#upload input[type="file"]');

//maybe adding something can work u out for thr love
// Add an event listener to the upload form to handle file submissions
uploadForm.addEventListener('submit', (event) => {
  event.preventDefault(); // Prevent the form from submitting normally
  const formData = new FormData(); // Create a new FormData object
  formData.append('userfile', fileInput.files[0]); // Add the selected file to the FormData object
  // Send the form data to the server using an AJAX request
  fetch('upload.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    // Display a success message
    alert('File uploaded successfully!');
  })
  .catch(error => {
    // Display an error message
    alert('There was an error uploading the file.');
  });
});




