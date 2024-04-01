<h1 align="center">WP Unit Plugin</h1>

Create WP Unit Plugin For unit tests.

# PHP Unit configuration command.

- For setup PHP Unit follow below link.
https://make.wordpress.org/core/handbook/testing/automated-testing/phpunit/

Install PHPUnit 
- We are going to install PHPUnit using Composer, LocalWP should come with Composer installed, to install PHPUnit run. Go to plugin directory and from cmd run following command.
```bash
composer require --dev phpunit/phpunit
```

Install PHPUnit Polyfills
- PHPUnit Polyfills are required to create PHPUnit tests in WordPress. 
```bash
composer require --dev yoast/phpunit-polyfills
```

- After Successful setup PHP Unit please follow below steps
1. Open the terminal or command prompt (CMD)
2. Navigate to \wp-content\plugins\sample-plugin directory.
3. Run below command for create a plugin this will also add the test files that we need.

```bash
wp scaffold plugin sample-plugin(plugin-name)
```

4. if you wish to add the test files to an existing plugin you should run below code.

```bash
wp scaffold plugin-tests sample-plugin(plugin-name)
```

5. Run below command in our plugin root folder for Installing WP tests. If you are using windows bash command may be not work from cmd. Try from gitbash.

```bash
bash bin/install-wp-tests.sh <db-name> <db-user> <db-pass> [db-host] [wp-version] [skip-database-creation]
```
e.g. bash bin/install-wp-tests.sh wordpress_test root '' localhost

6. Run below in our plugin root folder for run all test.
```bash
phpunit
```

If you want to run particular file test then you can write class name after phpunit like below
```bash
phpunit --filter ApplicationVersionFormatTest
```

If you are using windows, phpunit will not work, Try following command
vendor\bin\phpunit

For More details about installation and How PHPunit works, Checkout this blog
https://dev.to/eliehanna/how-to-run-phpunit-in-a-wordpress-plugin-on-windows-using-localwp-1414

If you are using windows then you do do the following configuration in ABSPATH
Change below line 
define( 'ABSPATH', 'temp/wordpress/' );
to below
define( 'ABSPATH', 'C:/Users/username/AppData/Local/Temp/wordpress/' );