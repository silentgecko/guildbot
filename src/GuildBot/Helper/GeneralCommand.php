<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 26.03.2018
 * Time: 19:39
 */

namespace GuildBot\Helper;

use Discord\DiscordCommandClient;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Embed\Footer;

class GeneralCommand
{
    /**
     * @param DiscordCommandClient $discord
     *
     * @throws \Exception
     */
    public static function registerPingCommand(DiscordCommandClient $discord)
    {
        $discord->registerCommand(
            'ping',
            function ($message, $args) {

                if (Helper::checkGuildChannel($message) === false) {
                    return false;
                }

                /** @var $message Message */
                $message->reply('pong!');
            },
            [
                'description' => 'Pingcheck',
                'usage'       => 'am i still alive...?',
            ]
        );
    }

    /**
     * @param DiscordCommandClient $discord
     *
     * @throws \Exception
     */
    public static function registerGuildBotCommand(DiscordCommandClient $discord)
    {
        $discord->registerCommand(
            'guildbot',
            function ($message, $args) use ($discord) {

                if (Helper::checkGuildChannel($message) === false) {
                    return false;
                }

                /** @var $message Message */

                /** @var Message $message */
                /** @var Embed $embed */
                $embed  = $discord->factory(Embed::class);
                $footer = $discord->factory(Footer::class);
                $footer->fill(
                    [
                        'text' => '© @admin#5413',
                    ]
                );
                $embed->fill(
                    [
                        'title'       => 'GuildBot - powered by PanicMode',
                        'description' => "Here is your [Bot Invite Link]({$discord->application->getInviteURLAttribute()})",
                        'url'         => $discord->application->getInviteURLAttribute(),
                        'timestamp'   => false,
                        'color'       => '39893',
                        'footer'      => $footer,
                    ]
                );

                $message->channel->sendMessage(
                    "If you want to use my functionality on your own Discord Server / Guild, please invite me to your server.\r\nBut keep in mind: You need to be an admin to invite me to your server.",
                    false,
                    $embed
                );
            },
            [
                'description' => 'Invite this Bot to your Server',
                'usage'       => '',
            ]
        );
    }

}