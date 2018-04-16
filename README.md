IdP installer module
====================

The _IdP installer_ module provides a single authentication module:

* `IdP installer module`: pre-configures a basic simpleSAMLphp identity provider.

This module creates a basic configuration for a SAML2int IdP.

Installation
------------

Once you have installed SimpleSAMLphp, installing this module is very simple. Just execute the following
command in the root of your SimpleSAMLphp installation:

```
composer.phar require rediris-es/simplesaml-module-idpinstaller:dev-master
```

where `dev-master` instructs Composer to install the `master` branch from the Git repository. See the
[releases](https://github.com/rediris-es/simplesamlphp-module-idpinstaller/releases) available if you
want to use a stable version of the module.

Web server configuration
------------------------

After installation and assuming SimpleSAMLphp is installed under  `/var/sso.example.com`,
you must configure,your Apache server like this:

````
<VirtualHost *:443>
    ServerName sso.example.com
    DocumentRoot /var/www/sso.example.com

    # configuration generated using https://mozilla.github.io/server-side-tls/ssl-config-generator/
    SSLEngine on
    SSLCertificateFile      /path/to/signed_certificate
    SSLCertificateChainFile /path/to/intermediate_certificate
    SSLCertificateKeyFile   /path/to/private/key
    SSLCACertificateFile    /path/to/all_ca_certs

    # modern configuration, tweak to your needs
    SSLProtocol             all -SSLv2 -SSLv3 -TLSv1
    SSLCipherSuite          ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-DSS-AES128-GCM-SHA256:kEDH+AESGCM:ECDHE-RSA-AES128-SHA256:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA:ECDHE-ECDSA-AES128-SHA:ECDHE-RSA-AES256-SHA384:ECDHE-ECDSA-AES256-SHA384:ECDHE-RSA-AES256-SHA:ECDHE-ECDSA-AES256-SHA:DHE-RSA-AES128-SHA256:DHE-RSA-AES128-SHA:DHE-DSS-AES128-SHA256:DHE-RSA-AES256-SHA256:DHE-DSS-AES256-SHA:DHE-RSA-AES256-SHA:!aNULL:!eNULL:!EXPORT:!DES:!RC4:!3DES:!MD5:!PSK
    SSLHonorCipherOrder     on

    # HSTS (mod_headers is required) (15768000 seconds = 6 months)
    Header always add Strict-Transport-Security "max-age=15768000"

    Alias / /var/sso.example.com/www

    <Directory /var/sso.example.com/www>
        Options -Indexes FollowSymLinks
        AllowOverride None
        Order deny,allow
        Allow from all
    </Directory>

    <Location />
        Options FollowSymLinks
	  AllowOverride None
        Order deny,allow
        Allow from all
    </Location>
</VirtualHost>

````

Usage
-----

After the module has been installed and the web server configured, the administrator will
point his browser to this URL to start the IdP configuration process:

````
https://sso.example.com/module.php/idpinstaller/
````

Post-install
------------

The installer creates a basic IdP configuration, that needs some further configuration. 
Depending on the backend to be used, you'll have to also configure the auth sources section
to fit your needs.

TODO: links to LDAP, PDO, CAS documentation in SimpleSAMLphp.


