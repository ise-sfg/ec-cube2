<?php

require __DIR__.'/autoload.php';

out('update permissions...');
updatePermissions();

$app = createApplication();
initializeDatabase($app);
out('Remove the web installer: `rm html/install.php`', 'info');

exit(0);

/**
 * @return \Eccube\Application
 */
function createApplication()
{
    $app = \Eccube\Application::getInstance();
    $app['debug'] = true;
    $app->initDoctrine();
    $app->initSecurity();
    $app->register(new \Silex\Provider\FormServiceProvider());
    $app->register(new \Eccube\ServiceProvider\EccubeServiceProvider());
    $app->boot();
    return $app;
}

function initializeDatabase(\Eccube\Application $app)
{
    // Get an instance of your entity manager
    $entityManager = $app['orm.em'];

    $pdo = $entityManager->getConnection()->getWrappedConnection();

    // Clear Doctrine to be safe
    $entityManager->getConnection()->getConfiguration()->setSQLLogger(null);
    $entityManager->clear();
    gc_collect_cycles();

    // Schema Tool to process our entities
    $tool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
    $classes = $entityManager->getMetaDataFactory()->getAllMetaData();


    // Drop all classes and re-build them for each test case
    out('Dropping database schema...', 'info');
    $tool->dropSchema($classes);
    out('Creating database schema...', 'info');
    $tool->createSchema($classes);
    out('Database schema created successfully!', 'success');
    $config = new \Doctrine\DBAL\Migrations\Configuration\Configuration($app['db']);

    $config->setMigrationsNamespace('DoctrineMigrations');

    $migrationDir = __DIR__.'/src/Eccube/Resource/doctrine/migration';
    $config->setMigrationsDirectory($migrationDir);
    $config->registerMigrationsFromDirectory($migrationDir);

    $migration = new \Doctrine\DBAL\Migrations\Migration($config);
    $migration->migrate();
    out('Database migration successfully!', 'success');

    $login_id = getenv('ADMIN_USER');
    $login_password = getenv('ADMIN_PASS');
    $passwordEncoder = new \Eccube\Security\Core\Encoder\PasswordEncoder($app['config']);
    $salt = \Eccube\Util\Str::random(32);
    $encodedPassword = $passwordEncoder->encodePassword($login_password, $salt);
    out('Creating admin accounts...', 'info');
    $sql = "INSERT INTO dtb_member (member_id, login_id, password, salt, work, del_flg, authority, creator_id, rank, update_date, create_date,name,department) VALUES (2, :login_id, :admin_pass , :salt , '1', '0', '0', '1', '1', current_timestamp, current_timestamp,'管理者', 'EC-CUBE SHOP');";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array(
            ':login_id' => $login_id,
            ':admin_pass' => $encodedPassword,
            ':salt' => $salt
        )
    );
    $stmt->closeCursor();

//    $shop_name = $app['config']['shop_name'];
    $shop_name = getenv("SHOP_NAME");
//    $admin_mail = $app['config']['admin_mail'];
    $admin_mail = getenv("ADMIN_MAIL");
    $sql = "INSERT INTO dtb_base_info (id, shop_name, email01, email02, email03, email04, update_date, option_product_tax_rule) VALUES (1, :shop_name, :admin_mail1, :admin_mail2, :admin_mail3, :admin_mail4, current_timestamp, 0)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array(
            ':shop_name' => $shop_name,
            ':admin_mail1' => $admin_mail,
            ':admin_mail2' => $admin_mail,
            ':admin_mail3' => $admin_mail,
            ':admin_mail4' => $admin_mail,
        )
    );
    $stmt->closeCursor();
}


/**
 * @link https://github.com/composer/windows-setup/blob/master/src/php/installer.php
 */
function out($text, $color = null, $newLine = true)
{
    $styles = array(
        'success' => "\033[0;32m%s\033[0m",
        'error' => "\033[31;31m%s\033[0m",
        'info' => "\033[33;33m%s\033[0m"
    );
    $format = '%s';
    if (isset($styles[$color])) {
        $format = $styles[$color];
    }
    if ($newLine) {
        $format .= PHP_EOL;
    }
    printf($format, $text);
}
function updatePermissions()
{
    $finder = \Symfony\Component\Finder\Finder::create();
    $finder
        ->in('html')->notName('.htaccess')
        ->in('app')->notName('console');

    $verbose = true;
    foreach ($finder as $content) {
        $permission = $content->getPerms();
        // see also http://www.php.net/fileperms
        if (!($permission & 0x0010) || !($permission & 0x0002)) {
            $realPath = $content->getRealPath();
            if ($verbose) {
                out(sprintf('%s %s to ', $realPath, substr(sprintf('%o', $permission), -4)), 'info', false);
            }
            $permission = !($permission & 0x0020) ? $permission += 040 : $permission; // g+r
            $permission = !($permission & 0x0010) ? $permission += 020 : $permission; // g+w
            $permission = !($permission & 0x0004) ? $permission += 04 : $permission;  // o+r
            $permission = !($permission & 0x0002) ? $permission += 02 : $permission;  // o+w
            $result = chmod($realPath, $permission);
            if ($verbose) {
                if ($result) {
                    out(substr(sprintf('%o', $permission), -4), 'info');
                } else {
                    out('failure', 'error');
                }
            }
        }
    }
}
