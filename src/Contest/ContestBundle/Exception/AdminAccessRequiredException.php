<?php

namespace ContestBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AdminAccessRequiredException extends HttpException
{
    /**
     * @param \Exception $previous The previous exception
     * @param int        $code     The internal exception code
     * @param array      $headers
     */
    public function __construct(\Exception $previous = null, int $code = 0, array $headers = array())
    {
        $message = 'Do wybranej czynności potrzebne są uprawnienia administratora!';
        
        parent::__construct(403, $message, $previous, $headers, $code);
    }
}