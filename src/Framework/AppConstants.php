<?php

namespace Framework;

class AppConstants {
    //status
    const STATUS_NOTLOGIN = 330;

    // error code
    const CODE_REQUEST_SUCCESS = 200;

    const CODE_TOKEN_INVALID        = 2001;

    const CODE_EMAIL_NOT_VALID = 3001;
    const MESSAGE_EMAIL_NOT_VALID = 'Email không hợp lệ';

    const CODE_EMAIL_NOT_EXIST = 3005;
    const MESSAGE_EMAIL_NOT_EXIST = 'Email không tồn tại';

    const CODE_PASSWORD_NOT_MATCHED = 4001;
    const MESSAGE_PASSWORD_NOT_MATCHED = 'Mật khẩu không đúng';

    const CODE_USER_NOT_EXIST = 5001;
    const MESSAGE_USER_NOT_EXIST = 'Người dùng không tồn tại trong hệ thống';

    //========================================================================
    //error code update user param
    const CODE_INFO_NOT_ALLOW_UPDATE = 6001;
    const MESSAGE_INFO_NOT_ALLOW_UPDATE = '%s không được phép thay đổi.';

    const CODE_INFO_UPDATE_NOT_VALID = 6002;
    const MESSAGE_INFO_UPDATE_NOT_VALID = 'Thông tin %s không hợp lệ.';

    const CODE_INFO_UPDATE_EMPTY = 6003;
    const MESSAGE_INFO_UPDATE_EMPTY = 'Thông tin %s không được trống.';

    const CODE_INFO_UPDATE_OVER_LIMITATION = 6004;
    const MESSAGE_INFO_UPDATE_OVER_LIMITATION = 'Thông tin %s đã vượt quá %d ký tự.';

    const CODE_INFO_DATE_UPDATE_NOT_VALID = 6005;
    const MESSAGE_INFO_DATE_UPDATE_NOT_VALID = 'Thông tin %s không đúng định dạng năm-tháng-ngày.';
}