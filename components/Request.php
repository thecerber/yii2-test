<?php
namespace app\components;

class Request extends \yii\web\Request
{
    /**
     * Грубый костыль, чтобы исправить баг фреймворка с некорректным обращением:
     *
     * ```php
     *  $auth_token = $this->getHeaders()->get('HTTP_AUTHORIZATION')
     * ```
     *
     * @see https://github.com/yiisoft/yii2/issues/18315
     * @inheritdoc
     */
    public function getAuthCredentials()
    {
        $username = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
        $password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null;
        if ($username !== null || $password !== null) {
            return [$username, $password];
        }

        /*
         * Apache with php-cgi does not pass HTTP Basic authentication to PHP by default.
         * To make it work, add the following line to to your .htaccess file:
         *
         * RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
         */
        $auth_token = $this->getHeaders()->get('Authorization');
        if ($auth_token !== null && strncasecmp($auth_token, 'basic', 5) === 0) {
            $parts = array_map(function ($value) {
                return strlen($value) === 0 ? null : $value;
            }, explode(':', base64_decode(mb_substr($auth_token, 6)), 2));

            if (count($parts) < 2) {
                return [$parts[0], null];
            }

            return $parts;
        }

        return [null, null];
    }
}
