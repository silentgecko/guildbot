<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 26.03.2018
 * Time: 19:28
 */

namespace GuildBot\Helper;

use Discord\DiscordCommandClient;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Embed\Footer;
use GuildBot\Model\GuildQuery;

class HelpCommand
{
    /**
     * @param DiscordCommandClient $discord
     * @param array                $discordConfig
     *
     * @throws \Exception
     */
    public static function registerHelpCommand(DiscordCommandClient $discord, array $discordConfig) {
        $discord->registerCommand(
            'help',
            function ($message, $args) use ($discordConfig, $discord) {

                if (Helper::checkGuildChannel($message) === false) {
                    return false;
                }

                if (count($args) > 0) {
                    $command    = $discord->getCommand($args[0]);
                    $subCommand = null;

                    if (isset($args[1]) && isset($command->subCommands[$args[1]])) {
                        $subCommand = $command->subCommands[$args[1]];
                    }

                    if (is_null($command)) {
                        return "The command {$args[0]} does not exist.";
                    }

                    $sheetConfigs = GuildQuery::create()->findPk($message->author->guild_id)->getSheetConfigs();
                    $fieldList = '';
                    foreach ($sheetConfigs as $sheetConfig) {
                        if ($sheetConfig->getField() !== 'discordid') {
                            $fieldList .= "\"{$sheetConfig->getField()}\", Default Value: \"{$sheetConfig->getDefaultValue()}\", Type: \"{$sheetConfig->getType()}\"\n";
                        }
                    }

                    $response = "```md
<{$discordConfig['prefix']}{$command->command} " . (is_null($subCommand) === false ? $subCommand->command : '') . ">
=================
Description:
------------
" . (is_null($subCommand) === false ? $subCommand->description : $command->description) . "

Usage:
------
{$discordConfig['prefix']}{$command->command} " . (is_null($subCommand) === false ? $subCommand->command : '') . " " . (is_null($subCommand) === false ? $subCommand->usage : str_replace('{fieldList}', $fieldList, $command->usage)) . "
";

                    if (count($command->subCommands) > 0 && is_null($subCommand)) {
                        $response .= "Subcommands:\r\n";
                        $response .= "------------\r\n";

                        foreach ($command->subCommands as $subCommand) {
                            /** @var $subCommand Command */

                            $response .= "{$discordConfig['prefix']}{$command->command} {$subCommand->command} {$subCommand->usage}\r\n";
                        }
                    }

                    if (count($command->aliases) > 0) {
                        $response .= "\r\nAliases:\r\n";

                        foreach ($command->aliases as $alias => $command) {
                            if ($command != $args[0]) {
                                continue;
                            }

                            $response .= "- {$alias}\r\n";
                        }
                    }

                    $response .= '```';

                    $message->channel->sendMessage($response);

                    return true;
                }

                $response = "```md\r\n";
                $response .= "GuildBot - powered by PanicMode - [Manual](https://www.panic-mode.de/guides/technik/guildbot-manual/) (en)\r\n";
                $response .= "===============================\r\n";
                $response .= "Easy way of Guildmanagement inside Discord - combined with Google Spreadsheet\r\n";

                foreach ($discord->commands as $command) {
                    $response .= "<{$discordConfig['prefix']}{$command->command}> {$command->description}\r\n";
                }

                $response .= "Run <{$discordConfig['prefix']}help [command]> to get more information about a specific function.\r\n\r\n";
                $response .= "© admin#5413 - If you need any help, join our [Discord Server](discord.gg/PY2dQjE). And Read [the Manual!](https://www.panic-mode.de/guides/technik/guildbot-manual/)!\r\n";

                $response .= '```';
                $message->channel->sendMessage($response);
            },
            [
                'description' => 'Provides a list of commands available.',
                'usage'       => 'Helpception?!',
            ]
        );

        $discord->registerCommand(
            'helpNew',
            function ($message, $args) use ($discordConfig, $discord) {

                if (Helper::checkGuildChannel($message) === false) {
                    return false;
                }

                if (count($args) > 0) {
                    $command    = $discord->getCommand($args[0]);
                    $subCommand = null;

                    if (isset($args[1]) && isset($command->subCommands[$args[1]])) {
                        $subCommand = $command->subCommands[$args[1]];
                    }

                    if (is_null($command)) {
                        return "The command {$args[0]} does not exist.";
                    }

                    $sheetConfigs = GuildQuery::create()->findPk($message->author->guild_id)->getSheetConfigs();
                    $fieldList = '';
                    foreach ($sheetConfigs as $sheetConfig) {
                        if ($sheetConfig->getField() !== 'discordid') {
                            $fieldList .= "\"{$sheetConfig->getField()}\", Default Value: \"{$sheetConfig->getDefaultValue()}\", Type: \"{$sheetConfig->getType()}\"\n";
                        }
                    }

                    $title = "{$discordConfig['prefix']}{$command->command} " . (is_null($subCommand) === false ? $subCommand->command : '') . "";
                    $description = (is_null($subCommand) === false ? $subCommand->description : $command->description);
                    $usage = "{$discordConfig['prefix']}{$command->command} " . (is_null($subCommand) === false ? $subCommand->command : '') . " " . (is_null($subCommand) === false ? $subCommand->usage : str_replace('{fieldList}', $fieldList, $command->usage)) . "";
                    $subCommands = "";

                    if (count($command->subCommands) > 0 && is_null($subCommand)) {

                        foreach ($command->subCommands as $subCommand) {
                            /** @var $subCommand Command */
                            $subCommands .= "{$discordConfig['prefix']}{$command->command} {$subCommand->command} {$subCommand->usage}\r\n";
                        }
                    }

                    /** @var Message $message */
                    /** @var Embed $embed */
                    $embed = $discord->factory(Embed::class);
                    $footer = $discord->factory(Footer::class);
                    $footer->fill([
                        'text' => '© @admin#5413',
                    ]);
                    $embed->fill(
                        [
                            'title' => 'GuildBot Help: ' . $title,
                            'description' => "**Description:**\r\n" . $description . "\r\n\r\n"
                                             . "**Usage:**\r\n" . $usage . "\r\n\r\n"
                                             . (strlen($subCommands) > 0 ? "**Subcommands:**\r\n" . $subCommands . "\r\n\r\n" : ''),
                            'url' => 'https://www.panic-mode.de/guides/technik/guildbot-manual/',
                            'timestamp' => false,
                            'color' => '39893',
                            'footer' => $footer,
                        ]
                    );

                    $message->channel->sendMessage('', false, $embed);

                    return true;
                }

                $description = "Easy way of Guildmanagement inside Discord - combined with Google Spreadsheet\r\n\r\n";
                $description .= "**Commands:**\r\n";

                foreach ($discord->commands as $command) {
                    $description .= "{$discordConfig['prefix']}{$command->command} - {$command->description}\r\n";
                }

                $description .= "Run \"{$discordConfig['prefix']}help [command]\" to get more information about a specific function.\r\n\r\n";
                $description .= "© @admin#5413 - If you need any help, join our [Discord Server](https://discord.gg/PY2dQjE) - And read [the Manual!](https://www.panic-mode.de/guides/technik/guildbot-manual/)\r\n";

                /** @var Message $message */
                /** @var Embed $embed */
                $embed = $discord->factory(Embed::class);
                $footer = $discord->factory(Footer::class);
                $footer->fill([
                    'text' => '© @admin#5413',
                ]);
                $embed->fill(
                    [
                        'title' => 'GuildBot - powered by PanicMode',
                        'description' => $description,
                        'url' => 'https://www.panic-mode.de/guides/technik/guildbot-manual/',
                        'timestamp' => false,
                        'color' => '39893',
                        'footer' => $footer,
                    ]
                );

                $message->channel->sendMessage('', false, $embed);
            },
            [
                'description' => 'Provides a list of commands available.',
                'usage'       => 'Helpception?!',
            ]
        );
    }
}