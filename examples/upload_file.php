<?php

/**
 * This web service demonstrates how to handle uploaded files
 * in form of 'multipart/form-data'.
 *
 * @Route('upload')
 */
class UploadService extends Service
{

  /**
   * The upload method. We only accept png images for this demo.
   *
   * Takes an image and returnes the same image. Not really a useful
   * web service, but it demonstrates that files may be upped to a
   * service under the phpREST library.
   *
   * @ContentType('image/png')
   */
  public function post($file)
  {

    // Check that we've got input
    if ($file !== NULL) {

      // Check file type
      if ($file['type'] == 'image/png') {

        // Return the image uploaded to demonstrate that we got it
        echo file_get_contents($file['tmp_name']);
      } else if ($file['type'] == 'application/x-rar-compressed') {

        // Return the image uploaded to demonstrate that we got it
        $target_dir = "uploads/";
        $path = pathinfo($file['name']);
        $filename = $path['filename'];
        $ext = $path['extension'];
        $temp_name = $file['tmp_name'];
        $path_filename_ext = $target_dir . $filename . "." . $ext;

        if (file_exists($path_filename_ext)) {
          echo "Sorry, file already exists.";
        } else {
          move_uploaded_file($temp_name, $path_filename_ext);
          echo "Congratulations! File Uploaded Successfully.";
        }
        echo 'uploaded !' . $file;
      } else {
        throw new ServiceException(HttpStatus::BAD_GATEWAY, $file['type']);
      }
    } else {
      throw new ServiceException(
        HttpStatus::BAD_REQUEST,
        'Missing file'
      );
    }
  }
}

// The $server object is instantiated in index.php

// Register our service with the server
$server->addService(new UploadService());
