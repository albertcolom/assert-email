<?php

namespace albertcolom\Assert;

class AssertEmail
{
    static public function valid($email, $message = '')
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
        $domain =  substr(strrchr($email, "@"), 1);

        if (!preg_match('/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/', $domain )) {
            throw new \InvalidArgumentException(sprintf('Incorrect domain name "%s"', $domain));
        }

        return $domain;
    }

    private function __construct()
    {
    }
}
