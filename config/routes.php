<?php
/**
 * Routes Configuration
 * Cấu hình routing cho ứng dụng
 */

return [
    'GET' => [
        // Trang chủ
        '/' => 'DashboardController@index',
        '/dashboard' => 'DashboardController@index',
        
        // Authentication
        '/login' => 'AuthController@showLogin',
        '/register' => 'AuthController@showRegister',
        '/logout' => 'AuthController@logout',
        
        // Employee Management
        '/employees' => 'EmployeeController@index',
        '/employees/create' => 'EmployeeController@create',
        '/employees/{id}' => 'EmployeeController@show',
        '/employees/{id}/edit' => 'EmployeeController@edit',
        
        // Leave Management
        '/leaves' => 'LeaveController@index',
        '/leaves/create' => 'LeaveController@create',
        '/leaves/{id}' => 'LeaveController@show',
        '/leaves/{id}/edit' => 'LeaveController@edit',
        '/leaves/calendar' => 'LeaveController@calendar',
        '/leaves/approve' => 'LeaveController@approve',
        
        // Device Management
        '/devices' => 'DeviceController@index',
        '/devices/borrow' => 'DeviceController@borrow',
        '/devices/return' => 'DeviceController@return',
        '/devices/approve' => 'DeviceController@approve',
        '/devices/{id}' => 'DeviceController@show',
        '/devices/borrow/process' => 'DeviceController@borrowDevice',
        '/devices/return/process' => 'DeviceController@returnDevice',
        
        // Room Management
        '/rooms' => 'RoomController@index',
        '/rooms/booking' => 'RoomController@booking',
        '/rooms/calendar' => 'RoomController@calendar',
        '/rooms/approve' => 'RoomController@approve',
        '/rooms/{id}' => 'RoomController@show',
        
        // API Routes
        '/api/employees' => 'ApiController@getEmployees',
        '/api/leaves' => 'ApiController@getLeaves',
        '/api/devices' => 'ApiController@getDevices',
        '/api/rooms' => 'ApiController@getRooms',
    ],
    'POST' => [
        // Authentication
        '/login' => 'AuthController@login',
        '/register' => 'AuthController@register',
        
        // Employee Management
        '/employees' => 'EmployeeController@store',
        '/employees/{id}' => 'EmployeeController@update',
        '/employees/{id}/delete' => 'EmployeeController@destroy',
        
        // Leave Management
        '/leaves' => 'LeaveController@store',
        '/leaves/{id}' => 'LeaveController@update',
        '/leaves/{id}/approve' => 'LeaveController@approveLeave',
        '/leaves/{id}/reject' => 'LeaveController@rejectLeave',
        
        // Device Management
        '/devices' => 'DeviceController@store',
        '/devices/{id}' => 'DeviceController@update',
        '/devices/{id}/borrow' => 'DeviceController@borrowDevice',
        '/devices/{id}/return' => 'DeviceController@returnDevice',
        '/devices/{id}/approve' => 'DeviceController@approveBorrow',
        '/devices/{id}/reject' => 'DeviceController@rejectBorrow',
        
        // Room Management
        '/rooms' => 'RoomController@store',
        '/rooms/{id}' => 'RoomController@update',
        '/rooms/{id}/book' => 'RoomController@bookRoom',
        '/rooms/{id}/cancel' => 'RoomController@cancelBooking',
        '/rooms/{id}/approve' => 'RoomController@approveBooking',
        '/rooms/{id}/reject' => 'RoomController@rejectBooking',
        
        // API Routes
        '/api/upload' => 'ApiController@uploadFile',
    ],
];
