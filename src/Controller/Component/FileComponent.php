<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

class FileComponent extends Component
{
    //var $components = ['Session', 'File', 'String'];

    /**
     * Check filename
     *
     * @param (string) $filename - Uploaded file name.
     */
    function checkFileUploadedName($filename)
    {
        return (bool) ((preg_match("`^[-0-9A-Z_\.\(\)[:space:]]+$`i", $filename)) ? true : false);
        // return (bool) ((preg_match("`^[-0-9A-Z_\.]+$`i",$filename)) ? true : false);
    }

    /**
     * Check filename length.
     *
     * @param (string) $filename - Uploaded file name.
     */
    function checkFileUploadedLength($filename)
    {
        return (bool) ((mb_strlen($filename, "UTF-8") < 225) ? true : false);
    }

    /**
     * Clean filename
     */
    function cleanName($filename)
    {
        $filename = trim(addslashes($filename));
        $filename = str_replace(' ', '_', $filename);
        $filename = preg_replace('/\s+/', '_', $filename);
        return $filename;
    }

    function checkFileInForm($file, $filePath, $allowed_ext = ['xls', 'xlsx'])
    {

        if (!empty($file->getStream())) {
            $file_name = $file->getClientFilename();

            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $tmpName = $file->getStream()->getMetadata('uri');

            if (in_array($ext, $allowed_ext) || empty($allowed_ext)) {
                if ($this->checkFileUploadedName($file_name)) {
                    if ($this->checkFileUploadedLength($file_name)) {
                        if (file_exists($filePath)) {
                            chmod($filePath, 0755); //Change the file permissions if allowed
                            unlink($filePath); //remove the file
                        }
                        if (move_uploaded_file($tmpName, $filePath)) {
                            return true;
                        } else {
                            $err = $file->moveTo($filePath);
                            if (!empty($err)) {
                                error_log("move_uploaded_file error : " . json_encode($_FILES));
                                $this->getController()->Flash->error('The file cannot be moved, please contact the administrator');
                            }
                        }
                    } else {
                        $this->getController()->Flash->error('The file name cannot be bigger than 250 characters');
                    }
                } else {
                    $this->getController()->Flash->error('The file name must use only English characters, numbers and (_-. ) symbols');
                }
            } else {
                $this->getController()->Flash->error('Please select a file with the following extension: ' . implode(', ', $allowed_ext));
            }
        } else {
            $this->getController()->Flash->error('Please select a file');
        }

        return false;
    }

    /**
     * Keep n file with a specific extension from a filepath
     *
     * @param string $filepath
     * @param string $extension
     * @param int $nb
     */
    function keepLastNFiles($filepath, $extension, $nb)
    {
        $files = glob($filepath . '*.' . $extension);
        usort($files, function($a, $b) {
            if ($a[0] == $b[0]) {
                return 0;
            }
            return $a[0] < $b[0] ? 1 : -1;
        });
        //usort($files, create_function('$a,$b', 'return filemtime($a)<filemtime($b);'));
        foreach ($files as $key => $filename) {
            if ($key >= $nb) {
                unlink($filename);
            }
        }
    }

}
