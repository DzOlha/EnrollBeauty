<?php

namespace Src\Helper\Http;

class HttpCode
{
    /**
     * @return int
     *
     * Is used for specifying successful result of the HTTP request
     *
     * [request has been successfully processed]
     */
    public static function ok(): int
    {
        return 200;
    }

    /**
     * @return int
     *
     * Is used to indicate the successful creation
     * of some resource as a result of HTTP request
     *
     * [resources has been created]
     */
    public static function created(): int
    {
        return 201;
    }

    /**
     * @return int
     *
     * Indicates the success of HTTP request like 200 code, but signifies
     * that there is no content to return in the response body
     *
     * [returned empty array of resources]
     */
    public static function noContent(): int
    {
        return 204;
    }

    /**
     * @return int
     *
     * Is used for indication of the bad request parameters
     *
     * [missing request fields]
     */
    public static function badRequest(): int
    {
        return 400;
    }

    /**
     * @return int
     *
     * Is used to indicate the needs of authentication
     * to get access to the requested data
     *
     * [no user session was found]
     */
    public static function unauthorized(): int
    {
        return 401;
    }

    /**
     * @return int
     *
     * Is used to indicate the absence of permission
     * to get access to the requested data
     *
     * OR
     *
     * to indicate that the resource that is trying to be created
     * already exists in database
     *
     * [no permission, resource already exists]
     */
    public static function forbidden(): int
    {
        return 403;
    }

    /**
     * @return int
     *
     * Is used to indicate the absence of the requested resource
     *
     * OR
     *
     * to indicate that some error occurred while processing request
     *
     * [no resource, error processing request]
     */
    public static function notFound(): int
    {
        return 404;
    }

    /**
     * @return int
     *
     * Is used to indicate the wrong method for a request

     * [not allowed method used]
     */
    public static function methodNotAllowed(): int
    {
        return 405;
    }


    /**
     * @return int
     *
     * Is used to indicate the invalid entity to process in a request
     *
     * [data validation error]
     */
    public static function unprocessableEntity(): int
    {
        return 422;
    }

    /**
     * @return int
     *
     * Is used to indicate the error from third-party services
     * used by the system
     *
     * [email sending error]
     */
    public static function badGateway(): int
    {
        return 502;
    }
}