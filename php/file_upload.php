<?php
function handleFileUpload($fileKey) {
    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] === UPLOAD_ERR_NO_FILE) {
        // No file was uploaded
        return ['success' => false, 'error' => 'No file was uploaded.', 'noFile' => true];
    }

    if ($_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
        // A file was uploaded but an error occurred
        return ['success' => false, 'error' => 'File upload failed.', 'noFile' => false];
    }

    $file = $_FILES[$fileKey];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION); // Improved file extension check
    $fileActualExt = strtolower($fileExt);

    $allowed = ['jpg', 'jpeg', 'png']; // Allowed file types

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 2000000) {
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileDestination = './uploads/' . $fileNameNew;
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    return ['success' => true, 'filePath' => $fileDestination];
                } else {
                    return ['success' => false, 'error' => 'There was an error uploading the file.', 'noFile' => false];
                }
            } else {
                return ['success' => false, 'error' => 'File is too big.', 'noFile' => false];
            }
        } else {
            return ['success' => false, 'error' => 'There was an error uploading the file.', 'noFile' => false];
        }
    } else {
        return ['success' => false, 'error' => 'Invalid file type. Only JPG, JPEG, and PNG are allowed. Uploaded file type: ' . strtoupper($fileActualExt), 'noFile' => false];
    }
}
?>