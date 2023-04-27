
<?php





require_once 'vendor/autoload.php';

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
use MicrosoftAzure\Storage\Blob\Models\DeleteBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\ListContainersOptions;
use MicrosoftAzure\Storage\Blob\Models\GetBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\SetBlobPropertiesOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\CopyBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\Blob;
use MicrosoftAzure\Storage\Blob\Models\CopyBlobResult;
use MicrosoftAzure\Storage\Blob\Models\GetBlobPropertiesOptions;
use MicrosoftAzure\Storage\Blob\Models\LeaseMode;
use MicrosoftAzure\Storage\Common\ServiceException as StorageServiceException;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobResult;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsResult;
use MicrosoftAzure\Storage\Common\Exceptions\InvalidArgumentTypeException;
use MicrosoftAzure\Storage\Common\Exceptions\InvalidOptionsException;
use MicrosoftAzure\Storage\Common\Internal\Resources;
use MicrosoftAzure\Storage\Common\ServicesBuilder;




$connectionString = "BlobEndpoint=https://stdhelper.blob.core.windows.net/;QueueEndpoint=https://stdhelper.queue.core.windows.net/;FileEndpoint=https://stdhelper.file.core.windows.net/;TableEndpoint=https://stdhelper.table.core.windows.net/;SharedAccessSignature=sv=2021-12-02&ss=bfqt&srt=sco&sp=rwdlacupiytfx&se=2023-04-29T09:43:01Z&st=2023-04-27T01:43:01Z&spr=https,http&sig=zfPPGvsR%2FLyR%2BlJWvltGW2rpACdjWxn1MgxmD4JYANw%3D";
$sasToken = "?sv=2021-12-02&ss=bfqt&srt=sco&sp=rwdlacupiytfx&se=2023-04-29T09:43:01Z&st=2023-04-27T01:43:01Z&spr=https,http&sig=zfPPGvsR%2FLyR%2BlJWvltGW2rpACdjWxn1MgxmD4JYANw%3D";
$containerName = "storing";
$blobClient = BlobRestProxy::createBlobService($connectionString . $sasToken);
$blobClient = BlobRestProxy::createBlobService($connectionString);

// Get the uploaded file
$file = $_FILES['file']['tmp_name'];

// Get the file name
$fileName = $_FILES['file']['name'];

// Upload the file to Azure Blob Storage
try {
    // Create options for uploading the file to Azure Blob Storage
    $options = new CreateBlockBlobOptions();
    $options->setContentType(mime_content_type($file));

    // Upload the file to Azure Blob Storage
    $result = $blobClient->createBlockBlob($containerName, $fileName, fopen($file, "r"), $options);

    // Output the result
    echo "File uploaded successfully to Azure Blob Storage.";
} catch (ServiceException $e) {
    echo "ServiceException: " . $e->getMessage();
} catch (InvalidArgumentTypeException $e) {
    echo "InvalidArgumentTypeException: " . $e->getMessage();
}catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}




// Check if a file was uploaded
if(isset($_FILES['file'])) {
  $errors = array();
  $file_name = $_FILES['file']['name'];
  $file_size = $_FILES['file']['size'];
  $file_tmp = $_FILES['file']['tmp_name'];
  $file_type = $_FILES['file']['type'];
  $file_string = explode('.',$_FILES['file']['name']);
  $file_ext = strtolower(end($file_string));
  
  // Define allowed file extensions
  $allowed_extensions = array('jpg', 'jpeg', 'gif', 'png', 'pdf','txt');
  
  // Check file extension
  if(!in_array($file_ext, $allowed_extensions)) {
    $errors[] = "File type not allowed. Only JPG, JPEG, GIF, PNG, TXT and PDF files are allowed.";
  }
  
  // Check file size
  if($file_size > 2097152) {
    $errors[] = "File size must be less than 2 MB.";
  }
  
  if(empty($errors)) {
    // Move uploaded file to a permanent location
    $file_destination = 'uploads/' . $file_name;
    move_uploaded_file($file_tmp, $file_destination);
    echo "<p style='font-family:Times New Roman;>'Uploaded!</p>";

  } else {
    // Return a JSON response with error message
    echo "Upload failed";
    echo json_encode(array('success' => false, 'error' => implode("\n", $errors)));
  }
} else {
  // Return a JSON response with error message
  echo "Upload failed";
  echo json_encode(array('success' => false, 'error' => 'No file was uploaded.'));
}

	

?>








