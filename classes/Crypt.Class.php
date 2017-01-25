<?php

    /**
     * Basic Cryptography for Login and Password-Management
     */
    class Crypt
    {        
        const SALT = "CASM-Tool-Permission-System";
        const HASH_ALGO = "sha256";

        public static function hashStringWithSalt($source) 
        {
            return hash(Crypt::HASH_ALGO, $source.Crypt::SALT);
        }
    }     

?>