<?php

namespace Src\Helper\Uploader\impl;

use Src\Helper\Uploader\IUploader;

class FileUploader implements IUploader
{
    /**
     * @param $file $_FILE['image-input-name']
     * @param $fileRandName $randomNameGenerated
     * @param $folder $destinationFolder
     * @return bool
     */
    public function upload($file, $fileRandName, $folder): bool
    {
        $dir = str_replace("\\", "/", ROOT) . $folder;
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $uploadFile = $dir . $fileRandName;

        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            return true;
        } else
            return false;
    }

    /**
     * @param $base64String
     * @param $fileRandName
     * @param $folder - from the root with start and end slashes like '/tmp/news/'
     * @return bool
     */
    public static function uploadBase64Image($base64String, $fileRandName, $folder)
    {
        $imageData = base64_decode($base64String);

        $sizeLimitInBytes = 5 * 1024 * 1024; // 5 MB
        $imageSizeInBytes = strlen($imageData);
        if ($imageSizeInBytes > $sizeLimitInBytes) {
            return 'The image size should not exceed 5 MB';
        }

        $dir = str_replace("\\", "/", ROOT) . $folder;
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $uploadFile = $dir . $fileRandName;

        if (file_put_contents($uploadFile, $imageData)) {
            return true;
        } else
            return false;
    }

    public function copyFile($sourceFilePath, $destinationFolderPath): bool
    {
        $root = str_replace("\\", "/", ROOT);
        $sourceFilePath = $root . $sourceFilePath;
        $destinationFolderPath = $root . $destinationFolderPath;
        if (file_exists($sourceFilePath)) {
            if (!is_dir($destinationFolderPath)) {
                mkdir($destinationFolderPath, 0755, true);
            }

            // Construct the full destination path
            $destinationFilePath = $destinationFolderPath . basename($sourceFilePath);

            // Copy the file
            if (copy($sourceFilePath, $destinationFilePath)) {
                return true; // File copied successfully
            } else {
                return false; // Failed to copy the file
            }
        } else {
            return false; // Source file does not exist
        }
    }


    public static function deleteFolder($folderPath)
    {
        $folderPath = str_replace("\\", "/", ROOT) . $folderPath;
        if (is_dir($folderPath)) {
            // Remove all files within the folder
            $files = glob($folderPath . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }

            // Remove the folder
            rmdir($folderPath);
        }
    }

    public static function deleteFolderContent($folderPath)
    {
        $folderPath = str_replace("\\", "/", ROOT) . $folderPath;
        if (is_dir($folderPath)) {
            // Remove all files within the folder
            $files = glob($folderPath . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    if (!unlink($file)) {
                        return false;
                    }
                }
            }
            return true;
        } else return false;
    }

    public static function deleteFileFromFolder($folderPath, $fileName)
    {
        if(!$fileName) {
            return true;
        }
        $folderPath = str_replace("\\", "/", ROOT) . $folderPath;
        $fileToDelete = $folderPath . $fileName;

        if (file_exists($fileToDelete)) {
            if (!unlink($fileToDelete)) {
                return false;
            }
        } else return false;
        return true;
    }

    public static function renameFolder($folderPath, $newName)
    {
        $folderPath = str_replace("\\", "/", ROOT) . $folderPath;
        // Check if the folder exists
        if (is_dir($folderPath)) {
            // Get the parent directory of the folder
            $parentDirectory = dirname($folderPath);

            // Build the new path with the new name
            $newPath = $parentDirectory . DIRECTORY_SEPARATOR . $newName;


            // Attempt to change folder permissions
            if (chmod($folderPath, 0777)) {
                // Use the rename function to rename the folder
                if (rename($folderPath, $newPath)) {
                    // Restore folder permissions (adjust as needed)
                    chmod($newPath, 0755);
                    return true;
                }
            }
        } else return false;
        return false;
    }
}