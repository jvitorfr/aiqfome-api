<?php

namespace App\Enums;

enum AuditAction: string
{
    case CLIENT_LOGIN = 'CLIENT_LOGIN';
    case ADMIN_LOGIN = 'ADMIN_LOGIN';
    case CLIENT_LOGOUT = 'CLIENT_LOGOUT';
    case ADMIN_LOGOUT = 'ADMIN_LOGOUT';
    case CREATED_CLIENT = 'created_client';
    case EDITED_CLIENT = 'edited_client';
    case DELETED_CLIENT = 'deleted_client';

    case ADD_FAVORITE_PRODUCT  = 'add_favorite_product';
    case REMOVED_FAVORITE_PRODUCT = 'remove_favorite_product';

    case EDIT_FAVORITE_PRODUCT = 'edit_favorite_product';
    case EDIT_CLIENT_PRODUCT_BY_ADMIN = 'edit_client_product_by_admin';
}
