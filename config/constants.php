<?php
    return [
        'auth' => [
            'HTTP_UNAUTHORIZED' => 'Unauthorized',
            'HTTP_FORBIDDEN' => 'Forbidden',
            'HTTP_NOT_FOUND' => 'Not Found',
            'HTTP_BAD_REQUEST' => 'Bad Request',
            'HTTP_REGISTER_SUCCESS' => 'Register success',
            'HTTP_LOGIN_SUCCESS' => 'Login success',
            'HTTP_LOGOUT_SUCCESS' => 'Logout success',
        ],
        'request_OTP' => [
            'user_not_found' => 'User not found',
            'code_expired' => 'OTP expired',
            'code_invalid' => 'Code invalid',
            'success_verify' => 'Successfully verified',
            'user_was_verified' => 'User was verified',
            'register_otp_success' => 'Register OTP success',
        ],
        'forgot_password' => [
            'email_not_found' => 'Email not found',
            'success' => 'Password reset link sent',
            'expired' => 'Link expired',
            'no_longer_valid' => 'Link no longer valid',
            'success_reset' => 'Password reset successfully',
        ],
        'user' => [
            'get_info_success' => 'Get info success',
            'get_all_info_success' => 'Get all info success',
            'delete_success' => 'Delete success',
            'update_success' => 'Update success',
        ]
    ];
