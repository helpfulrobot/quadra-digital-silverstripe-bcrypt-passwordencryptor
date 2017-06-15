# Silverstripe bcrypt password encryptor

A custom built password encryptor for Silverstripe that uses php's password_hash and bcrypt with customisable encryption cost

## Requirements

This module should work with Silverstripe 3.1 and above (possibly including 4) but it has only been tested on 3.5.

## Installation

### Composer

Install module:
```sh
composer require Quadra-Digital/silverstripe-bcrypt-passwordencryptor 1.0
```

### Manually

Clone the repo on the root of your project.

## Configuration

Set *bcrypt* as the algorithm you want to use on your config:
```yml
Security:
  password_encryption_algorithm: bcrypt
```

This will enable the module and all newly created members will use bcrypt as their password hashing algorhythm.

For existing members to benefit, their ```Member::$PasswordEncryption``` property will need to be manually changed in the database from (for example) 'blowfish' to 'bcrypt'. This would not be neccersary if the password encryption process made use of the *current* algorhythm, defined in config, instead of the *last used* algorhythm stored in the members DB record, as per [this issue](https://github.com/silverstripe/silverstripe-framework/issues/6770) against framework.

They will then need to have their password reset either by an admin or using the 'password reset' process, using the 'change password' process will not work as when their current password is checked it will be hashed using the new 'bcrypt' algorhythm instead of the original (for example) 'blowfish' algorhythm, causing the check to fail.

Optionally set a custom encryption cost for your application (default is 15, must be between 4 and 31):
```yml
PasswordEncryptor_bcrypt:
  encryption_cost: 18
```

## License
This module uses the BSD-3-Clause license. See the [LICENSE.md](LICENSE.md) file for the full license.

## Copyright
Copyright (c) 2017, [Quadrahedron Limited](https://www.quadradigital.co.uk) All rights reserved.

## Contact
This module is built and open-sourced by [Quadra Digtial](https://www.quadradigital.co.uk) if you have any queries regarding usage, licensing, bugs or improvements - please use one of the appropriate contacts below or open an issue on [Github](https://github.com/Quadra-Digital/silverstripe-bcrypt-passwordencryptor).

### Technical
Leonardo Melo <leonardo.melo@quadradigital.co.uk>

### Administrative
Peter Foster <peter.foster@quadradigital.co.uk>

