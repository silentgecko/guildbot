#!/usr/bin/php5
<?php

namespace GuildBot;

use Discord\DiscordCommandClient;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Includes the Composer autoload file
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../generated-conf/config.php';

$config = [
    'app_name'          => '',
    'app_client_id'     => '',
    'app_client_secret' => '',
    'app_bot_user'      => '',
    'app_bot_id'        => '',
    'app_bot_token'     => '',
    'google'            => [
        'api_key' => '',
    ],
    'debug'             => false,
    'admin'             => '', # bot admin discord userid, gets auto access on every server the bot is running. for maintenance only
];

$logLevel = Logger::INFO;
if ($config['debug']) {
    $logLevel = Logger::DEBUG;
}

$dbLogger = new Logger('defaultLogger');
$dbLogger->pushHandler(new StreamHandler('/var/log/discord/db.log', $logLevel));

$serviceContainer->setLogger('defaultLogger', $dbLogger);

$discordLogger = new Logger('discordLogger');
$discordLogger->pushHandler(new StreamHandler('/var/log/discord/guildbot.log', $logLevel));

$discordConfig = [
    'token'              => $config['app_bot_token'],
    'prefix'             => '!',
    'description'        => 'I will take care of your Guild Management, with Attendance Reminders, Attendance settings, etc via Google Spreadsheets.',
    'defaultHelpCommand' => false,
    'discordOptions'     => [
        'pmChannels' => true,
        'logger'     => $discordLogger,
        'logging'    => true,
    ],
];

// disable Propel cache


$discordLogger->info('Guildbot booting...');
$discord = new DiscordCommandClient($discordConfig);

### Guild Join
$guildBot = new GuildBot($discord, $config, $discordConfig);



// Now we will run the ReactPHP Event Loop!
$guildBot->getDiscord()->run();


