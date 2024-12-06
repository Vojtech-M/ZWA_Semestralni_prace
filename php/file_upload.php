<?php
function handleFileUpload($fileKey) {
    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'File upload failed or no file uploaded.'];
    }

    $file = $_FILES[$fileKey];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 2000000) {
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileDestination = './uploads/' . $fileNameNew;
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    return ['success' => true, 'filePath' => $fileDestination];
                } else {
                    return ['success' => false, 'error' => 'There was an error uploading the file.'];
                }
            } else {
                return ['success' => false, 'error' => 'File is too big.'];
            }
        } else {
            return ['success' => false, 'error' => 'There was an error uploading the file.'];
        }
    } else {
        return ['success' => false, 'error' => 'Invalid file type. Only JPG, JPEG, and PNG are allowed.'];
    }
}
?>