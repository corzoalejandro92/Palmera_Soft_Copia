<?php

namespace  App\General;
trait CryptoAES {

    /**
     * Helper library for CryptoJS AES encryption/decryption
     * Allow you to use AES encryption on client side and server side vice versa
     *
     * @author BrainFooLong (bfldev.com)
     * @link https://github.com/brainfoolong/cryptojs-aes-php
     */

    /**
     * Decrypt data from a CryptoJS json encoding string
     *
     * @param mixed $passphrase
     * @param mixed $jsonString
     * @return mixed
     */
    static function cryptoJsAesDecrypt($passphrase){
        $passphrase = base64_decode(base64_decode(base64_decode($passphrase)));
        $passphrase = str_replace(config('app.secret_key'),'',$passphrase);
        return $passphrase;
    }

    /**
     * Encrypt value to a cryptojs compatiable json encoding string
     *
     * @param mixed $passphrase
     * @param mixed $value
     * @return string
     */
    static function cryptoJsAesEncrypt($passphrase, $value){
        $salt = openssl_random_pseudo_bytes(8);
        $salted = '';
        $dx = '';
        while (strlen($salted) < 48) {
            $dx = md5($dx.$passphrase.$salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $iv  = substr($salted, 32,16);
        $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
        $data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
        return json_encode($data);
    }
}
