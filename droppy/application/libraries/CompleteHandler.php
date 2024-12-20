<?php

// Link image type to correct image loader and saver
const IMAGE_HANDLERS = [
    IMAGETYPE_JPEG => [
        'load' => 'imagecreatefromjpeg',
        'save' => 'imagejpeg',
        'quality' => 100
    ],
    IMAGETYPE_PNG => [
        'load' => 'imagecreatefrompng',
        'save' => 'imagepng',
        'quality' => 0
    ],
    IMAGETYPE_GIF => [
        'load' => 'imagecreatefromgif',
        'save' => 'imagegif'
    ]
];

/**
 * Droppy - Upload complete handler
 *
 * Class CompleteHandler
 */
class CompleteHandler
{
    /**
     * @var CI_Controller
     */
    protected $CI;

    /**
     * CompleteHandler constructor.
     */
    public function __construct() {
        $this->CI =& get_instance();
    }

    /**
     * Process the upload completion
     *
     * @param $post_data
     * @return bool
     * @throws Exception
     */
    public function complete($post_data) {
        $this->CI->load->model('uploads');
        $this->CI->load->model('files');
        $this->CI->load->model('receivers');

        // Make sure to keep the script running
        // Zipping and encrypting can take some time
        ini_set('max_execution_time', 0);

        // Get the uploaded files
        $files = $this->CI->files->getByUploadID($post_data['upload_id']);

        $this->CI->logging->log($post_data['upload_id'] . " > Disconnecting the client from the request, Droppy will continue completing the upload in the background");

        // Kill the connection to the user, we got what we need.
        $this->killConnection();

        $this->CI->logging->log($post_data['upload_id'] . " > Client disconnected, start processing files..");

        // Process the files (Zipping, moving etc.)
        if($this->processFiles($files, $post_data['upload_id'])) {
            $this->CI->db->reconnect();

            // Update the upload info in the DB
            $this->CI->uploads->updateByUploadID($post_data['upload_id'], array('count' => $post_data['total_files']));

            // Get upload info
            $upload = $this->CI->uploads->getByUploadID($post_data['upload_id']);

            if($upload !== false && $upload['share'] == 'mail' && !empty($upload['email_from'])) {
                // Load email library
                $this->CI->load->library('email');

                // Prepare sender data array to pass to email
                $sender_data = array('upload_id' => $post_data['upload_id']);
                if(isset($post_data['password'])) {
                    $sender_data['password'] = $post_data['password'];
                }

                // Send email to uploader
                $this->CI->email->sendEmail('sender', $sender_data, array($upload['email_from']));

                // Get receivers
                $receivers = $this->CI->receivers->getByUploadID($post_data['upload_id']);

                // Send email to receivers
                foreach($receivers as $receiver) {
                    $this->CI->load->library('email');

                    // Prepare receiver data array to pass to email
                    $receiver_data = array('upload_id' => $post_data['upload_id'], 'private_id' => $receiver['private_id'], 'email' => $receiver['email'], 'email_from' => $upload['email_from']);
                    if(isset($post_data['password'])) {
                        $receiver_data['password'] = $post_data['password'];
                    }

                    if(!empty($receiver['email'])) {
                        $this->CI->email->sendEmail('receiver', $receiver_data, array($receiver['email']));
                    }
                }
            }

            return true;
        }
        return false;
    }

    /**
     * Process the upoaded files to one single zip file.
     *
     * @param $files
     * @param $upload_id
     * @return bool
     * @throws Exception
     */
    private function processFiles($files, $upload_id) {
        $this->CI->load->helper('file');
        $this->CI->load->helper('string');

        $upload_path    = FCPATH . $this->CI->config->item('upload_dir');
        $temp_path      = $upload_path . 'temp/';

        // Check the total amount of files
        $total = count($files);

        $this->CI->logging->log("$upload_id > Total of $total files to process");

        $encryptKey = '';
        if($this->CI->config->item('encrypt_files') == 1) {
            $encryptKey = random_string('alnum', 32);
        }

        if($total > 1) {
            $this->CI->logging->log("$upload_id > Start zipping process");

            // If more than 1 start zipping files
            $file_name  = $this->CI->config->item('name_on_file') . '-' . $upload_id . '.zip';
            $zip_path   = FCPATH . $this->CI->config->item('upload_dir') . $file_name;

            // Load zip library
            $this->CI->load->library('zip');

            $this->CI->logging->log("$upload_id > Created $zip_path");

            // Create the zip file
            $this->CI->zip->zip_start($zip_path);

            $used_paths = [];

            // Loop through files
            foreach($files as $file) {
                $path = $temp_path . $file['secret_code'] . '-' . $file['file'];
                $original_filename = pathinfo($file['file'], PATHINFO_FILENAME);
                $extension = pathinfo($file['file'], PATHINFO_EXTENSION);

                $zip_filepath = $file['original_path'] . $file['file'];
                $unique_filename = $original_filename;
                $counter = 1;

                // Ensure the filename is unique
                while (in_array($file['original_path'] . $unique_filename . '.' . $extension, $used_paths)) {
                    $unique_filename = $original_filename . ' ' . $counter;
                    $counter++;
                }

                // Update the zip path to the unique filename
                $zip_filepath = $file['original_path'] . $unique_filename . '.' . $extension;

                $this->CI->logging->log("$upload_id > Adding $path to ZIP $zip_path");

                $this->CI->zip->zip_add($path, $zip_filepath);

                $this->CI->logging->log("$upload_id > Added $path to ZIP $zip_path");

                $used_paths[] = $zip_filepath;
            }

            $this->CI->logging->log("$upload_id > Saving ZIP $zip_path");

            // Save the ZIP file
            if($this->CI->zip->zip_end(1)) {
                $this->CI->logging->log("$upload_id > ZIP file $zip_path saved! Starting to remove original files");
                // Loop through files again but delete them this time
                foreach($files as $file) {
                    $path = $temp_path . $file['secret_code'] . '-' . $file['file'];

                    // Do not delete files when file previews is enabled
                    // Store the files in a separate folder
                    if($this->CI->config->item('file_previews') == 'true') {
                        $final_path = $upload_path . $upload_id . '/' . $file['secret_code'] . '-' . $file['file'];

                        if(!is_dir($upload_path . $upload_id)) {
                            mkdir($upload_path . $upload_id);
                        }

                        rename($path, $final_path);

                        // Make sure the file is an image
                        if($file['thumb'] == 1) {
                            $thumb_path = $upload_path . $upload_id . '/' . $file['secret_code'] . '-thumb-' . $file['file'];
                            $this->CI->logging->log("$upload_id > Creating thumbnail at $thumb_path");
                            try {
                                $this->createThumbnail($final_path, $thumb_path, 400);
                            } catch(Exception $e) {
                                $this->CI->logging->log("Something went wrong creating thumb $thumb_path");
                                $this->CI->logging->log($e);
                            }
                            $this->CI->logging->log("$upload_id > Thumbnail $thumb_path created!");
                        }

                        $this->CI->logging->log("$upload_id > Moved file to file $final_path");

                        if($this->CI->config->item('encrypt_files') == 1 && !$this->CI->plugin->pluginLoaded('upload')) {
                            $this->CI->logging->log("$upload_id > Encrypting single file $final_path");
                            $this->encryptFile($final_path, $encryptKey);

                            if($file['thumb'] == 1 && isset($thumb_path)) {
                                $this->encryptFile($thumb_path, $encryptKey);
                            }
                        }
                    } else {
                        unlink($path);

                        $this->CI->logging->log("$upload_id > Removed file $path");
                    }
                }
            } else {
                $this->CI->logging->log("$upload_id > Something went wrong with zipping $zip_path");
            }

            $final_path = $zip_path;
            $success    = true;
        }
        else
        {
            $this->CI->logging->log("$upload_id > Single file in upload, start renaming the file");

            // Ignore zipping when only 1 file has been selected
            foreach($files as $file) {
                $file_name = $file['secret_code'] . '-' . $file['file'];
                $path = $temp_path . $file_name;

                $final_path = $upload_path . $file_name;
                rename($path, $final_path);
                $this->CI->logging->log("$upload_id > File $path renamed to $final_path");

                // Make sure the file is an image
                if($file['thumb'] == 1) {
                    $thumb_path = $upload_path . $file['secret_code'] . '-thumb-' . $file['file'];
                    $this->CI->logging->log("$upload_id > Creating thumbnail at $thumb_path");
                    try {
                        $this->createThumbnail($final_path, $thumb_path, 400);
                    } catch(Exception $e) {
                        var_dump($e);
                        $this->CI->logging->log($e);
                    }

                    $this->CI->logging->log("$upload_id > Thumbnail $thumb_path created!");

                    if($this->CI->config->item('encrypt_files') == 1) {
                        $this->encryptFile($thumb_path, $encryptKey);
                    }
                }
            }

            $success    = true;
        }

        // File processing was successful
        if($success == true) {
            $this->CI->logging->log("$upload_id > File processing finished successfully!");
            $this->CI->db->reconnect();

            // Store the final size of the file in the database
            $final_size = filesize($final_path);
            $this->CI->uploads->updateByUploadID($upload_id, array('size' => $final_size));
            $this->CI->logging->log("$upload_id > Final size of file ($final_size) stored in DB");

            $this->CI->load->library('plugin');
            if($this->CI->plugin->pluginLoaded('upload')) {
                $this->CI->logging->log("$upload_id > External storage plugin installed, start sending the file to external storage..");

                $upload_plugin = $this->CI->plugin->_plugins['upload'];

                require_once $this->CI->plugin->_pluginDir . $upload_plugin['load'] . '/' . $upload_plugin['handler_library'];

                if($total > 1 && $this->CI->config->item('file_previews') == 'true') {
                    $this->CI->logging->log("$upload_id > File previews enabled, offloading ".count($files)." files to external storage..");
                    foreach($files as $file) {
                        $file_path = $upload_path . $upload_id . '/' . $file['secret_code'] . '-' . $file['file'];
                        $remote_path = $upload_id . '/' . $file['secret_code'] . '-' . $file['file'];

                        $handlerLibrary = new HandlerLibrary();
                        if($handlerLibrary->upload($upload_id, $file_path, $remote_path, $this->CI->config->item('encrypt_files'))) {
                            $this->CI->logging->log("$upload_id > File successfully sent to external storage $remote_path, removing local file $file_path");
                            if(unlink($file_path)) {
                                $this->CI->logging->log("$upload_id > Local file $file_path removed!");
                            }

                            // Make sure the file is an image
                            if($file['thumb'] == 1) {
                                $thumb_file_path = $upload_path . $upload_id . '/' . $file['secret_code'] . '-thumb-' . $file['file'];
                                $thumb_remote_path = $upload_id . '/' . $file['secret_code'] . '-thumb-' . $file['file'];
                                $this->CI->logging->log("$upload_id > Uploading thumbnail version $thumb_file_path, removing local file $thumb_remote_path");

                                if($handlerLibrary->upload($upload_id, $thumb_file_path, $thumb_remote_path, $this->CI->config->item('encrypt_files'))) {
                                    if (unlink($thumb_file_path)) {
                                        $this->CI->logging->log("$upload_id > Local thumbnail $thumb_file_path removed!");
                                    }
                                }
                            }
                        }
                        unset($handlerLibrary);
                    }

                    if(!is_dir($upload_path . $upload_id)) {
                        rmdir($upload_path . $upload_id);
                    }
                }

                $handlerLibrary = new HandlerLibrary();
                if($handlerLibrary->upload($upload_id, $final_path, $file_name, $this->CI->config->item('encrypt_files'))) {
                    $this->CI->logging->log("$upload_id > File successfully sent to external storage, removing local file $final_path");
                    if(unlink($final_path)) {
                        $this->CI->logging->log("$upload_id > Local file $final_path removed!");
                        return true;
                    }
                }
            }

            // If enabled start encrypting the zip file
            if($this->CI->config->item('encrypt_files') == 1) {
                $this->CI->logging->log("$upload_id > File encryption enabled! Start encrypting the file $final_path");
                $this->encryptFile($final_path, $encryptKey);

                $this->CI->db->reconnect();

                $this->CI->uploads->updateEncrypt($encryptKey, $upload_id);
                $this->CI->logging->log("$upload_id > File $final_path successfully encrypted");
            }

            return $final_path;
        }

        // Ohno !! Something went wrong
        return false;
    }

    /**
     * Encrypt a file
     *
     * @param string $file_path
     * @param string $gen_key
     * @return string
     */
    function encryptFile(string $file_path, string $gen_key = ''): string
    {
        $this->CI->load->helper('string');

        if(empty($gen_key)) {
            $gen_key = random_string('alnum', 32);
        }

        $key = substr(sha1($gen_key, true), 0, 16);
        $iv = openssl_random_pseudo_bytes(16);
        $blocks = 10000;

        $temp_file  = $this->CI->config->item('upload_dir') . 'temp/' . random_string('alnum', 32) . '.tmp';

        if ($fpOut = fopen($temp_file, 'w')) {
            // Put the initialzation vector to the beginning of the file
            fwrite($fpOut, $iv);
            if ($fpIn = fopen($file_path, 'rb')) {
                while (!feof($fpIn)) {
                    $plaintext = fread($fpIn, 16 * $blocks);
                    $ciphertext = openssl_encrypt($plaintext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                    // Use the first 16 bytes of the ciphertext as the next initialization vector
                    $iv = substr($ciphertext, 0, 16);
                    fwrite($fpOut, $ciphertext);
                }
                fclose($fpIn);
            }
            fclose($fpOut);
        }

        if(unlink($file_path)) {
            // Place encrypted version
            if(rename($temp_file, $file_path)) {
                return $gen_key;
            }
        }
    }

    /**
     * Decrypt a file
     *
     * @param $source
     * @param $dest
     * @param $key
     * @return bool
     */
    function decryptFile($source, $dest, $key)
    {
        $key = substr(sha1($key, true), 0, 16);

        $error = false;
        if ($fpOut = fopen($dest, 'w')) {
            if ($fpIn = fopen($source, 'rb')) {
                // Get the initialzation vector from the beginning of the file
                $iv = fread($fpIn, 16);
                while (!feof($fpIn)) {
                    $ciphertext = fread($fpIn, 16 * (10000 + 1)); // we have to read one block more for decrypting than for encrypting
                    $plaintext = openssl_decrypt($ciphertext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                    // Use the first 16 bytes of the ciphertext as the next initialization vector
                    $iv = substr($ciphertext, 0, 16);
                    fwrite($fpOut, $plaintext);
                }
                fclose($fpIn);
            } else {
                $error = true;
            }
            fclose($fpOut);
        } else {
            $error = true;
        }

        return $error ? false : $dest;
    }

    /**
     * Kill the connection with the user, but keep the script running in the background.
     */
    private function killConnection() {
        ignore_user_abort(true);
        ob_start();
        echo "success";
        $size = ob_get_length();
        header("Content-Length: $size");
        header('Connection: close');
        ob_end_flush();
        ob_flush();
        flush();
        if (session_id()) session_write_close();
    }

    // Function to create a thumbnail using the Imagick extension
    function createThumbnailWithImagick($src, $dest, $desired_width) {
        try {
            // Create a new Imagick object
            $image = new Imagick($src);

            // Set the image format to jpg (optional)
            $image->setImageFormat('jpg');

            // Calculate the desired height based on aspect ratio
            $image->thumbnailImage($desired_width, 0);

            // Save the thumbnail to a file
            $image->writeImage($dest);

            // Clear resources
            $image->clear();
            $image->destroy();

            return true;
        } catch (ImagickException $e) {
            return false;
        }
    }

    // Function to create a thumbnail using the GD library
    function createThumbnailWithGD($src, $dest, $desired_width) {
        // Get the source image size
        list($width, $height) = getimagesize($src);

        // Calculate the desired height based on aspect ratio
        $desired_height = floor($height * ($desired_width / $width));

        // Create a new true color image
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

        // Create a new image from the source file
        $image = imagecreatefromjpeg($src);

        // Copy and resize the image to the virtual image
        imagecopyresampled($virtual_image, $image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

        // Save the thumbnail to the destination path
        imagejpeg($virtual_image, $dest, 90); // 90 is the quality

        // Free up memory
        imagedestroy($virtual_image);
        imagedestroy($image);

        return true;
    }

    // Main function to create a thumbnail
    function createThumbnail($src, $dest, $desired_width, $desired_height = null) {
        if (extension_loaded('imagick')) {
            $result = $this->createThumbnailWithImagick($src, $dest, $desired_width);
            if ($result) {
                $this->CI->logging->log("Thumbnail generated successfully using Imagick.");
            } else {
                $this->CI->logging->log("Failed to generate thumbnail using Imagick.");
            }
        } elseif (extension_loaded('gd')) {
            $result = $this->createThumbnailWithGD($src, $dest, $desired_width);
            if ($result) {
                $this->CI->logging->log("Thumbnail generated successfully using GD.");
            } else {
                $this->CI->logging->log("Failed to generate thumbnail using GD.");
            }
        } else {
            $this->CI->logging->log("Neither Imagick nor GD is available.");
        }
    }
}