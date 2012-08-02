<?php
/**
 * @author Lusitanian <alusitanian@gmail.com>
 * Released under the MIT license.
 */

namespace OAuth\Common\Storage;

use OAuth\Common\Token\TokenInterface;
use OAuth\Common\Exception\TokenNotFoundException;

class Session implements StorageInterface
{
    /**
     * @var string
     */
    protected $sessionVariableName;

    /**
     * @param bool $startSession Whether or not to start the session upon construction.
     * @param string $sessionVariableName the variable name to use within the _SESSION superglobal
     */
    public function __construct($startSession = true, $sessionVariableName = 'lusitanian_oauth_token')
    {
        if( $startSession ) {
            session_start();
        }

        $this->sessionVariableName = $sessionVariableName;
    }

    /**
     * @return \OAuth\Common\Token\TokenInterface
     * @throws TokenNotFoundException
     */
    public function retrieveAccessToken()
    {
        if( isset( $_SESSION[$this->sessionVariableName] ) ) {
            return $_SESSION[$this->sessionVariableName];
        }

        throw new TokenNotFoundException('Token not found in session, are you sure you stored it?');
    }

    /**
     * @param \OAuth\Common\Token\TokenInterface $token
     */
    public function storeAccessToken(TokenInterface $token)
    {
        $_SESSION[$this->sessionVariableName] = $token;
    }

    public function  __destruct()
    {
        session_write_close();
    }
}
