<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/npm.php';
require 'contrib/rsync.php';

// Config

set('application', 'yamaha-avantgrand');
set('repository', 'git@github.com:cactus-blossom-it-services-limited/yamaha-avantgrand.git');
set('ssh_multiplexing', true);  // Speed up deployment
//set('default_timeout', 1000);

set('rsync_src', function () {
    return '/var/www/cactusblossomitservices.com'; // If your project isn't in the root, you'll need to change this.
});

// Configuring the rsync exclusions.
// You'll want to exclude anything that you don't want on the production server.
add('rsync', [
    'exclude' => [
        '.git',
        '/vendor/',
        '/node_modules/',
        '.github',
        'deploy.php',
    ],
]);

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Set up a deployer task to copy secrets to the server.
// Grabs the dotenv file from the github secret
task('deploy:secrets', function () {
    file_put_contents('/var/www/cactusblossomitservices.com/.env', getenv('DOT_ENV'));
    upload('.env', get('deploy_path') . '/shared');
});

// Hosts

host('prod')
		->setHostname('indigo.cactusblossomitservices.com') // Hostname or IP address
    ->set('remote_user', 'root')
    ->set('deploy_path', '/var/www/cactusblossomitservices.com');

// Tasks

desc('Start of Deploy the application');

task('deploy', [
    'deploy:prepare',
    'rsync',                // Deploy code & built assets
    'deploy:secrets',       // Deploy secrets
    'deploy:vendors',
    'deploy:shared',        //
    'artisan:storage:link', //
    'artisan:view:cache',   //
    'artisan:config:cache', // Laravel specific steps
    'artisan:migrate',      //
    'artisan:queue:restart',//
    'deploy:publish',       //
]);

desc('End of Deploy the application');
