<?php
/**
 * Routes Configuration
 * Cấu hình routing cho ứng dụng
 */

return [
    // Trang chủ
    'GET' => [
        '/' => 'DashboardController@index',
        '/dashboard' => 'DashboardController@index',
    ],
    
    // Authentication
    'GET' => [
        '/login' => 'AuthController@showLogin',
        '/register' => 'AuthController@showRegister',
        '/logout' => 'AuthController@logout',
    ],
    'POST' => [
        '/login' => 'AuthController@login',
        '/register' => 'AuthController@register',
    ],
    
    // Employee Management
    'GET' => [
        '/employees' => 'EmployeeController@index',
        '/employees/create' => 'EmployeeController@create',
        '/employees/{id}' => 'EmployeeController@show',
        '/employees/{id}/edit' => 'EmployeeController@edit',
    ],
    'POST' => [
        '/employees' => 'EmployeeController@store',
        '/employees/{id}' => 'EmployeeController@update',
        '/employees/{id}/delete' => 'EmployeeController@destroy',
    ],
    
    // Leave Management
    'GET' => [
        '/leaves' => 'LeaveController@index',
        '/leaves/create' => 'LeaveController@create',
        '/leaves/{id}' => 'LeaveController@show',
        '/leaves/{id}/edit' => 'LeaveController@edit',
        '/leaves/calendar' => 'LeaveController@calendar',
        '/leaves/approve' => 'LeaveController@approve',
    ],
    'POST' => [
        '/leaves' => 'LeaveController@store',
        '/leaves/{id}' => 'LeaveController@update',
        '/leaves/{id}/approve' => 'LeaveController@approveLeave',
        '/leaves/{id}/reject' => 'LeaveController@rejectLeave',
    ],
    
    // Device Management
    'GET' => [
        '/devices' => 'DeviceController@index',
        '/devices/borrow' => 'DeviceController@borrow',
        '/devices/return' => 'DeviceController@return',
        '/devices/{id}' => 'DeviceController@show',
    ],
    'POST' => [
        '/devices' => 'DeviceController@store',
        '/devices/{id}' => 'DeviceController@update',
        '/devices/{id}/borrow' => 'DeviceController@borrowDevice',
        '/devices/{id}/return' => 'DeviceController@returnDevice',
    ],
    
    // Room Management
    'GET' => [
        '/rooms' => 'RoomController@index',
        '/rooms/booking' => 'RoomController@booking',
        '/rooms/calendar' => 'RoomController@calendar',
        '/rooms/{id}' => 'RoomController@show',
    ],
    'POST' => [
        '/rooms' => 'RoomController@store',
        '/rooms/{id}' => 'RoomController@update',
        '/rooms/{id}/book' => 'RoomController@bookRoom',
        '/rooms/{id}/cancel' => 'RoomController@cancelBooking',
    ],
    
    // API Routes
    'GET' => [
        '/api/employees' => 'ApiController@getEmployees',
        '/api/leaves' => 'ApiController@getLeaves',
        '/api/devices' => 'ApiController@getDevices',
        '/api/rooms' => 'ApiController@getRooms',
    ],
    'POST' => [
        '/api/upload' => 'ApiController@uploadFile',
    ],
];
