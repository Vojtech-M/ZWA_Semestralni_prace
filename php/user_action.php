<?php
/*
* This file contains the user actions.
*/

/**
 * Handle user form
 * @param array $postData
 */
function handleUserAction($postData) {
    $action = $postData['action'];

    switch ($action) {
        case 'add':
        case 'update':
            handleUserForm($postData);
            break;
        case 'delete':
            $id = $postData['id'];
            deleteUser($id);
            loadUsers();
            break;

        case 'update_self':
            handleUpdateSelf($postData);
            break;

        case 'delete_reservation':
            $id = $postData['id'];
            deleteReservation($id);
            break;

        case 'edit_reservation':
            handleEditReservation($postData);
            break;
        case 'add_admin':
            $userId = $postData['user_id'];
            updateUserRoleToAdmin($userId);
            break;
    }
}
?>