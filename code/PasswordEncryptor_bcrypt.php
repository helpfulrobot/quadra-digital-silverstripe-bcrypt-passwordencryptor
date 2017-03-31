<?php

class PasswordEncryptor_bcrypt extends PasswordEncryptor {


    /**
     * Password hashing cost to be used by default.
     *
     * Higher costs will increase security, but also increase server load.
     *
     * The two digit cost parameter is the base-2 logarithm of the iteration
     * count for the underlying hashing algorithm.
     *
     * Must be in range 04-31, values outside this range will cause crypt() to fail.
     *
     * Can be overwriten in config.
     *
     * @var        integer
     */
    private static $encryption_cost = 15;

    static public function config() {
        error_log('Config::all ' . json_encode(Config::inst()->forClass(get_called_class())));
        error_log('Config::class): ' . json_encode(get_called_class()));
        return Config::inst()->forClass(get_called_class());
    }

    public static function set_encryption_cost($encryption_cost) {
        self::config()->encryption_cost = max(min(31, $encryption_cost), 4);
    }

    /**
     * @deprecated 4.0 Use the "PasswordEncryptor_bcrypt.encryption_cost" config setting instead
     * @return String
     */
    public static function get_encryption_cost() {
        $cost = self::config()->encryption_cost;
        if (empty($cost)) {
            $cost = self::$encryption_cost;
        }
        return max(min(31, $cost), 4);
    }

    /**
     * Return a string value stored in the {@link Member->Password} property.
     * The password should be hashed with {@link salt()} if applicable.
     *
     * @param String $password Cleartext password to be hashed
     * @param String $salt
     * @param Member $member (Optional)
     * @return String Maximum of 512 characters.
     */
    public function encrypt($password, $salt = null, $member = null)
    {

        error_log('PasswordEncryptor_bcrypt::encrypt');

        // password hash expects salt to be at least 22 characters long.
        if (empty($salt) || strlen($salt) < 22) {
            throw new PasswordEncryptor_EncryptionFailed('Could not encrypt password. Salt does not satisfy requirements: ' . $salt);
        }

        error_log('config: '. json_encode(self::config()->encryption_cost));

        $options = array(
            'cost' => $this->get_encryption_cost(),
            'salt' => $salt
        );

        $password = password_hash($password, PASSWORD_BCRYPT, $options);

        // Let's ensure that password_hash didn't return false, ie failed.
        if (!$password) {
            throw new PasswordEncryptor_EncryptionFailed(
                'Something went wrong, password hash returned false.'
            );
        }

        return $password;
    }

    /**
     * This usually just returns a strict string comparison,
     * but is necessary for retain compatibility with password hashed
     * with flawed algorithms - see {@link PasswordEncryptor_LegacyPHPHash} and
     * {@link PasswordEncryptor_Blowfish}
     */
    public function check($hash, $password, $salt = null, $member = null) {
        return $hash === $this->encrypt($password, $salt, $member);
    }
}
