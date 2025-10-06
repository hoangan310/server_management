<?php

namespace App\Enums;

enum AdminPermissionsEnum: string
{
    case ACCESS_DASHBOARD = 'access dashboard';
    case IMPERSONATE = 'impersonate';
        // Users
    case VIEW_USERS = 'view users';
    case CREATE_USERS = 'create users';
    case UPDATE_USERS = 'update users';
    case DELETE_USERS = 'delete users';

        // Roles
    case VIEW_ROLES = 'view roles';
    case CREATE_ROLES = 'create roles';
    case UPDATE_ROLES = 'update roles';
    case DELETE_ROLES = 'delete roles';

        // Permissions
    case VIEW_PERMISSIONS = 'view permissions';
    case CREATE_PERMISSIONS = 'create permissions';
    case UPDATE_PERMISSIONS = 'update permissions';
    case DELETE_PERMISSIONS = 'delete permissions';

        // Categories
    case VIEW_CATEGORIES = 'view categories';
    case CREATE_CATEGORIES = 'create categories';
    case UPDATE_CATEGORIES = 'update categories';
    case DELETE_CATEGORIES = 'delete categories';
}
