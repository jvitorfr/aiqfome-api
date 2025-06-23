<?php

namespace App\Enums;

enum AuditAction: string
{
    case CLIENT_LOGIN = 'client_login';
    case ADMIN_LOGIN = 'admin_login';
    case CLIENT_LOGOUT = 'client_logout';
    case ADMIN_LOGOUT = 'admin_logout';
    case CREATED_CLIENT = 'created_client';
    case EDITED_CLIENT = 'edited_client';
    case DELETED_CLIENT = 'deleted_client';

    case ADD_FAVORITE_PRODUCT  = 'add_favorite_product';
    case REMOVED_FAVORITE_PRODUCT = 'remove_favorite_product';

    case EDIT_FAVORITE_PRODUCT = 'edit_favorite_product';
    case EDIT_CLIENT_PRODUCT_BY_ADMIN = 'edit_client_product_by_admin';
}
