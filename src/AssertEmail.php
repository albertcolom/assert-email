<?php

namespace albertcolom\Assert;

class AssertEmail
{
    static public function rfc2822($email, $message = '')
    {
        $user   = '[a-zA-Z0-9_\-\.\+\^!#\$%&*+\/\=\?\`\|\{\}~\']+';
        $domain = '(?:(?:[a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.?)+';
        $ipv4   = '[0-9]{1,3}(\.[0-9]{1,3}){3}';
        $ipv6   = '[0-9a-fA-F]{1,4}(\:[0-9a-fA-F]{1,4}){7}';

        if (!preg_match("/^$user@($domain|(\[($ipv4|$ipv6)\]))$/", $email)) {
            throw new \InvalidArgumentException(
                sprintf($message ?: 'Invalid email "%s"', $email)
            );
        }

        return true;
    }

    static public function domainsAllowed($email, array $domains, $message = '')
    {
        $domain = self::getDomainFromEmail($email);

        if (!in_array($domain, $domains)) {
            throw new \InvalidArgumentException(
                sprintf($message ?: 'Domain is not allowed "%s"', $email)
            );
        }

        return true;
    }

    static public function dns($email, $message = '')
    {
        $domain = self::getDomainFromEmail($email);

        if(!checkdnsrr($domain, "MX")) {
            throw new \InvalidArgumentException(
                sprintf($message ?: 'Invalid email "%s"', $email)
            );
        }

        return true;
    }

    static function temporalMail($email, $message = '')
    {
        $domain = self::getDomainFromEmail($email);

        $temporal = file(
            __DIR__.'/../resources/temporal-mail-domain.txt',
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );

        if (in_array($domain, $temporal)) {
            throw new \InvalidArgumentException(
                sprintf($message ?: 'Temporal email is not allowed "%s"', $email)
            );
        }

        return true;
    }

    static private function getDomainFromEmail($email)
    {
        self::rfc2822($email);
        return substr(strrchr($email, "@"), 1);
    }

    private function __construct()
    {
    }
}
