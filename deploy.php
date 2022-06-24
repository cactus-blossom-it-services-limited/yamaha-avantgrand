<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@github.com:cactus-blossom-it-services-limited/yamaha-avantgrand.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('indigo.cactusblossomitservices.com')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/yamaha-avantgrand');

// Tasks

task('build', function () {
    cd('{{release_path}}');
    run('npm run build');
});

after('deploy:failed', 'deploy:unlock');
