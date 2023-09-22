<?php

namespace Crater\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use com\zoho\api\authenticator\Token;

use com\zoho\crm\api\exception\SDKException;

use com\zoho\crm\api\UserSignature;

use com\zoho\api\authenticator\store\TokenStore;
use Illuminate\Support\Facades\Storage;

class ZohoToken implements TokenStore
{
    protected $encodeContents;

    function __construct()
    {
        if (Storage::exists('oauth_token.txt')) {
            $fileContents =  Storage::disk('local')->get('oauth_token.txt');
            $this->encodeContents = json_decode($fileContents, true);
        }
    }

    /**
        * @param user A UserSignature class instance.
        * @param token A Token (com\zoho\api\authenticator\OAuthToken) class instance.
        * @return A Token class instance representing the user token details.
        * @throws SDKException if any problem occurs.
    */
    public function getToken($user, $token)
    {
        // Add code to get the token
        return null;
    }

    /**
        * @param user A UserSignature class instance.
        * @param token A Token (com\zoho\api\authenticator\OAuthToken) class instance.
        * @throws SDKException if any problem occurs.
    */
    public function saveToken($user, $token)
    {
        // Add code to save the token
    }

    /**
        * @param token A Token (com\zoho\api\authenticator\OAuthToken) class instance.
        * @throws SDKException if any problem occurs.
    */
    public function deleteToken($token)
    {
        // Add code to delete the token
    }

  /**
   * @return array  An array of Token (com\zoho\api\authenticator\OAuthToken) class instances
   */
    public function getTokens()
    {
        //Add code to retrieve all the stored tokens
    }

    public function deleteTokens()
    {
        //Add code to delete all the stored tokens.
    }

    public function getRefreshToken(){
        $refresh_token = $this->encodeContents['refresh_token'];
        return $refresh_token;
    }

    public function saveAccessToken($content){

        Storage::disk('local')->put('oauth_access_token.txt', $content, 'public');
        return true;

    }
}
