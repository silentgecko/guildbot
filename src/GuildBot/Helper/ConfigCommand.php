<?php

namespace GuildBot\Helper;

use Discord\CommandClient\Command;
use Discord\DiscordCommandClient;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Author;
use GuildBot\Model\Guild;
use GuildBot\Model\GuildQuery;
use GuildBot\Model\SheetConfig;
use GuildBot\Model\SheetConfigQuery;
use Propel\Runtime\Propel;

class ConfigCommand
{
    /**
     * @param DiscordCommandClient $discord
     * @param array                $discordConfig
     *
     * @return \Discord\CommandClient\Command
     * @throws \Exception
     */
    public static function registerConfigCommand(DiscordCommandClient $discord, array $discordConfig)
    {
        $configCommand = $discord->registerCommand(
            'config',
            function ($message, $args) use ($discord, $discordConfig) {

                if (Helper::checkGuildChannel($message) === false) {
                    return false;
                }

                if (Helper::checkGuildAdmin($message, $discord) === false) {
                    return false;
                }

                # it should response - but doesnt do. stupid thing.
                if (empty($args) || in_array($args[2], ['role', 'channel', 'sheet', 'cleanup']) === false) {
                    $message->reply("You need to tell me what i should do. Try {$discordConfig['prefix']}help config");
                }

                return true;

            },
            [
                'description' => 'Config Guildbot for your needs. Use <!help config role/channel/sheet/cleanup> for further description.',
                'usage'       => '[role/channel/sheet/cleanup]',
            ]
        );

        return $configCommand;
    }

    /**
     * @param Command              $configCommand
     * @param DiscordCommandClient $discord
     * @param array                $discordConfig
     *
     * @throws \Exception
     */
    public static function registerConfigRoleSubCommand(
        Command $configCommand,
        DiscordCommandClient $discord,
        array $discordConfig
    ) {
        $configCommand->registerSubCommand(
            'role',
            function ($message, $args) use ($discordConfig, $discord) {
                Propel::disableInstancePooling();

                if (Helper::checkGuildChannel($message) === false) {
                    return false;
                }

                if (Helper::checkGuildAdmin($message, $discord) === false) {
                    return false;
                }

                if (empty($args)) {
                    $message->reply(
                        "You need to tell me what i should do. Try {$discordConfig['prefix']}help config role"
                    );

                    return false;
                }

                /** @var $message Message */
                /** @var Author $author */
                $author = $message->author;

                /** @var Guild $guildDb */
                $guildDb = GuildQuery::create()
                    ->findPk($author->guild_id);

                $exampleStr = '!config role admin/member/list add/rm|remove @role @role2';
                $admin      = false;

                // check for mentioned roles
                if (empty($message->getMentionRolesAttribute())) {
                    $message->reply('You\'ve not highlighted any roles. use: ' . $exampleStr);

                    return false;
                }

                switch ($args[0]) {
                    case 'admin':
                        $admin = true;
                        break;

                    case 'member':
                        $admin = false;
                        break;
                    case 'list':
                        $adminRolesStr  = ' <@&' . implode('> <@&', $guildDb->getAdminRoles()) . '> ';
                        $memberRolesStr = ' <@&' . implode('> <@&', $guildDb->getMemberRoles()) . '> ';

                        $message->reply(
                            "here are your configured Roles:\r\nAdmin Roles: {$adminRolesStr}\r\nMember Roles:{$memberRolesStr}"
                        );

                        return true;
                        break;
                    default:
                        $message->reply('Please add what role you want to configure: ' . $exampleStr);
                        break;
                }

                switch ($args[1]) {
                    case 'add':
                        foreach ($message->mention_roles as $role) {
                            if ($admin && $guildDb->hasAdminRole($role->id) === false) {
                                $guildDb->addAdminRole($role->id);
                            }
                            if ($guildDb->hasMemberRole($role->id) === false) {
                                $guildDb->addMemberRole($role->id);
                            }
                        }
                        $guildDb->save();
                        $message->reply('Added Role(s) successfully.');
                        break;

                    case 'rm':
                    case 'remove':
                        foreach ($message->mention_roles as $role) {
                            if ($admin && $guildDb->hasAdminRole($role->id)) {
                                $guildDb->removeAdminRole($role->id);
                            }
                            if ($guildDb->hasMemberRole($role->id)) {
                                $guildDb->removeMemberRole($role->id);
                            }
                        }
                        $guildDb->save();
                        $message->reply('Role(s) successfully removed.');
                        break;

                    default:
                        $message->reply('You forgot to write what you want to do: add/rm|remove (' . $exampleStr . ')');
                        break;
                }

            },
            [
                'description' => "Role configuration for your Discord Server. You can configure which Roles can get access to admin functionality and which roles get member access (this is needed for the !update command)
Admin Roles will automatically be added/removed to/from the list of Member Roles.",
                'usage'       => "[admin/member/list] [add/rm|remove] [@role1] [@role2] [...] [@roleN]",
            ]
        );
    }

    /**
     * @param Command              $configCommand
     * @param DiscordCommandClient $discord
     * @param array                $discordConfig
     *
     * @throws \Exception
     */
    public static function registerConfigChannelSubCommand(
        Command $configCommand,
        DiscordCommandClient $discord,
        array $discordConfig
    ) {
        $configCommand->registerSubCommand(
            'channel',
            function ($message, $args) use ($discordConfig, $discord) {
                Propel::disableInstancePooling();

                if (Helper::checkGuildChannel($message) === false) {
                    return false;
                }

                if (Helper::checkGuildAdmin($message, $discord) === false) {
                    return false;
                }

                if (empty($args)) {
                    $message->reply(
                        "You need to tell me what i should do. Try {$discordConfig['prefix']}help config channel"
                    );

                    return false;
                }

                /** @var $message Message */
                /** @var Author $author */
                $author = $message->author;

                /** @var Guild $guildDb */
                $guildDb = GuildQuery::create()
                    ->findPk($author->guild_id);

                $exampleStr = '!config channel add/rm|remove/list #textchannel1 #textchannel2';

                // check for mentioned roles
                if (empty($args[1]) && $args[0] !== 'list') {
                    $message->reply('You\'ve to give me a channel. use: ' . $exampleStr);

                    return false;
                }

                /** @var \Discord\Parts\Guild\Guild $dGuild */
                $dGuild = $discord->guilds->get('id', $author->guild_id);

                $channelList = $args;
                unset($channelList[0]);

                foreach ($channelList as $idx => $channel) {
                    $channelList[$idx] = str_replace(['<', '>', '#'], '', $channel);
                }

                switch ($args[0]) {
                    case 'add':
                        foreach ($channelList as $channel) {
                            if ((int)$channel > 0) {
                                if ($guildDb->hasChannel($channel) === false) {
                                    $guildDb->addChannel($channel);
                                }

                                $guildDb->save();
                                $message->reply("Added Channel <#{$channel}> successfully.");
                            } else {
                                $dChannel       = $discord->factory(Channel::class);
                                $dChannel->name = $channel;
                                $dChannel->type = 'text';

                                $dGuild->channels->save($dChannel)
                                    ->then(
                                        function ($dChannel) use ($dGuild, $discord, $guildDb, $message) {
                                            $discord->logger->info('Created text channel #' . $dChannel->name);
                                            $guildDb->addChannel($dChannel->id);
                                            $guildDb->save();
                                            $message->reply("Added Channel <#{$dChannel->id}> successfully.");
                                        }
                                    )
                                    ->otherwise(
                                        function ($dChannel) use ($dGuild, $message) {
                                            $message->reply(
                                                "Could not create channel #{$dChannel->name} - I need atleast the permission to manage channels"
                                            );
                                        }
                                    );
                            }
                        }
                        break;

                    case 'rm':
                    case 'remove':
                        foreach ($channelList as $channel) {
                            if ((int)$channel > 0 && $guildDb->hasChannel($channel)) {
                                $guildDb->removeChannel($channel);
                            }
                        }
                        $guildDb->save();
                        $message->reply(
                            'Channel(s) successfully removed from my access list. You can remove the channel now.'
                        );
                        break;
                    case 'list':
                        $channelStr = ' <#' . implode('> <#', $guildDb->getChannels()) . '> ';

                        $message->reply("here are your configured Channels: {$channelStr}");

                        return true;
                        break;
                    default:
                        $message->reply('You forgot to write what you want to do: add/rm|remove (' . $exampleStr . ')');
                        break;
                }

            },
            [
                'description' => "Configure the channel(s) that Guildbot is allowed to be used in. Non existing Channels will be created, if Guildbot has the \"manage channels\" permission.",
                'usage'       => "[add/rm|remove/list] [#channel1] [#channel2] [...] [#channelN]",
            ]
        );
    }

    /**
     * @param Command              $configCommand
     * @param DiscordCommandClient $discord
     * @param array                $discordConfig
     *
     * @throws \Exception
     */
    public static function registerConfigFieldSubCommand(Command $configCommand, DiscordCommandClient $discord, array $discordConfig)
    {
        $configCommand->registerSubCommandAlias(
            'field',
            'config sheet field'
        );
    }

    /**
     * @param Command              $configCommand
     * @param DiscordCommandClient $discord
     * @param array                $discordConfig
     *
     * @throws \Exception
     */
    public static function registerConfigSheetSubCommand(Command $configCommand, DiscordCommandClient $discord, array $discordConfig)
    {
        $configCommand->registerSubCommand(
            'sheet',
            function ($message, $args) use ($discordConfig, $discord) {
                Propel::disableInstancePooling();

                if (Helper::checkGuildChannel($message) === false) {
                    return false;
                }

                if (Helper::checkGuildAdmin($message, $discord) === false) {
                    return false;
                }

                if (empty($args)) {
                    $message->reply("You need to tell me what i should do. Try {$discordConfig['prefix']}help config sheet");

                    return false;
                }

                $exampleStr = '!config sheet field/create/update/rename - use !help config sheet';

                /** @var $message Message */
                /** @var Author $author */
                $author = $message->author;

                /** @var Guild $guildDb */
                $guildDb = GuildQuery::create()
                    ->findPk($author->guild_id);

                /** @var \Discord\Parts\Guild\Guild $dGuild */
                $dGuild = $discord->guilds->get('id', $author->guild_id);

                //connect to google api
                $google = new Google($discord->logger);
                $google->connect();

                // define columns array (0 = A, 1 = B and so on)
                $columns = range('A', 'ZZ');

                switch ($args[0]) {
                    case 'field':
                        $errorReply = "I don't know what to do. Use {$discordConfig['prefix']}config sheet field add/rm|remove [fieldName:fieldType:fieldDefault:fieldColumn] - Minimum Required is **fieldName**";

                        if (isset($args[2]) === false && $args[1] !== 'list') {
                            $message->reply($errorReply);

                            return false;
                        }

                        switch ($args[1]) {
                            case 'add':
                                try {
                                    $fieldDefinitions = explode(':', $args[2]);
                                    if (empty($fieldDefinitions) === true) {
                                        $message->reply($errorReply);

                                        return false;
                                    }
                                    $discord->logger->debug('Trying to add field', $fieldDefinitions);

                                    $fieldName    = preg_replace("/[^ \w]+/", "", strtolower($fieldDefinitions[0]));
                                    if (trim($fieldName) === '') {
                                        $message->reply($errorReply);

                                        return false;
                                    }

                                    $fieldType    = (isset($fieldDefinitions[1]) ? $fieldDefinitions[1] : 'string');
                                    $fieldDefault = (isset($fieldDefinitions[2]) ? $fieldDefinitions[2] : '');
                                    $fieldColumn  = (isset($fieldDefinitions[3]) ? $fieldDefinitions[3] : '');

                                    $sheetConfigs = SheetConfigQuery::create()
                                        ->findByArray(
                                            [
                                                'Guild' => $guildDb,
                                                'field' => $fieldName,
                                            ]
                                        );

                                    if ($sheetConfigs->count() > 0) {
                                        $message->reply(
                                            "Field {$fieldDefinitions[0]} already exist in your configuration"
                                        );

                                        return false;
                                    }
                                    $discord->logger->debug('Field not found in DB', ['field' => $fieldName]);

                                    $sheet     = $google->getSheet($guildDb->getSheetId());
                                    $discord->logger->debug('$sheet', ['id' => $guildDb->getSheetId(), 'sheet' => $sheet]);
                                    $workSheet = $google->getWorkSheet($sheet, $guildDb->getWorkSheetTitle());
                                    $discord->logger->debug('workSheet', ['name' => $workSheet->getTitle()]);

                                    // get current cellfeed to check which cell is empty next
                                    $cellFeed = $workSheet->getCellFeed();
                                    $discord->logger->debug('cellFeed', ['name' => $cellFeed]);

                                    // check if we should just set the fieldColumn, and if not, calculate it
                                    if ($fieldColumn === '') {
                                        // get all cells in sheet
                                        $allCells   = $cellFeed->getEntries();
                                        $lastColumn = 1;
                                        foreach ($allCells as $cell) {
                                            if ($cell->getColumn() >= $lastColumn) {
                                                $lastColumn = $cell->getColumn();
                                            }
                                        }
                                        // add the column
                                        $newColumn = $lastColumn + 1;
                                    } else {
                                        $newColumn = array_search($fieldColumn, $columns) + 1;
                                    }
                                    $cellFeed->editCell(1, $newColumn, $fieldDefinitions[0]);

                                    $discord->logger->debug('edited Cell', [$newColumn, $fieldDefinitions[0]]);

                                    // save to db
                                    $sheetConfig = new SheetConfig;
                                    $sheetConfig->setField($fieldName)
                                        ->setType($fieldType)
                                        ->setDefaultValue($fieldDefault)
                                        ->setColumn($columns[$newColumn - 1]);
                                    $guildDb->addSheetConfig($sheetConfig);
                                    $discord->logger->debug('store into db', [$sheetConfig]);

                                    $guildDb->save();

                                    $message->reply("successfully added field {$fieldDefinitions[0]}");
                                } catch (\Exception $e) {
                                    $discord->logger->error($e->getTraceAsString());
                                    $discord->logger->error($e->getCode());
                                    $discord->logger->error($e->getMessage());
                                }

                                break;
                            case 'rm':
                            case 'remove':

                                $sheetConfigs = SheetConfigQuery::create()
                                    ->findByArray(
                                        [
                                            'Guild' => $guildDb,
                                            'field' => $args[2],
                                        ]
                                    );

                                if ($sheetConfigs->count() > 0) {
                                    $guildDb->removeSheetConfig($sheetConfigs->getFirst());
                                    $guildDb->save();
                                    $message->reply("Successfully removed \"{$args[2]}\"");
                                } else {
                                    $message->reply("Could not find field \"{$args[2]}\"");
                                }

                                break;
                            case 'list':
                                $sheetConfigs = $guildDb->getSheetConfigs();

                                $sheetConfigStr = '';
                                foreach ($sheetConfigs as $sheetConfig) {
                                    $sheetConfigStr .= $sheetConfig->getField() . ': Type: ' . $sheetConfig->getType(
                                        ) . ', Default: ' . $sheetConfig->getDefaultValue(
                                        ) . ', Column: ' . $sheetConfig->getColumn() . "\r\n";
                                }
                                $message->reply("Your configuration\r\n" . $sheetConfigStr);

                                break;
                            default:

                                break;
                        }
                        break;
                    case 'create':
                        if ($guildDb->getSheetId() !== null) {
                            $newSheet = $google->copySheet($guildDb->getSheetId());
                            $guildDb->setSheetId($newSheet->getSpreadsheetId());
                            $guildDb->save();
                            $message->reply(
                                "Copied the old and created a new sheet for you: {$newSheet->getSpreadsheetUrl()}\r\nDon't forget to make it private again if needed. I deleted the old sheet for you."
                            );
                            $discord->logger->info('Copied sheet for Guild ' . $dGuild->name);
                        } else {
                            $title = '';
                            if (isset($args[1])) {
                                $titleArr = $args;
                                unset($titleArr[0]);
                                $title = implode(' ', $titleArr);
                            }
                            if ($title === '') {
                                $title = $dGuild->name . ' Sheet';
                            }

                            $newSheet = $google->createSheet($title);
                            $guildDb->setSheetId($newSheet->getSpreadsheetId());

                            $defaultWorkSheetTitle = 'Member Management';
                            $sheet                 = $google->getSheet($newSheet->getSpreadsheetId());
                            $workSheet             = $google->renameWorkSheet($sheet, 'Sheet1', $defaultWorkSheetTitle);
                            $guildDb->setWorkSheetTitle($defaultWorkSheetTitle);

                            // update DB
                            $sheetConfig = new SheetConfig;
                            $sheetConfig->setField('discordid')
                                ->setType('int')
                                ->setDefaultValue(0)
                                ->setColumn('A');
                            $guildDb->addSheetConfig($sheetConfig);
                            $guildDb->save();

                            // Add Default Discord ID field to sheet
                            $cellFeed = $workSheet->getCellFeed();
                            $cellFeed->editCell(1, 1, 'DiscordID');

                            $message->reply("Created a new sheet for you: {$newSheet->getSpreadsheetUrl()}");
                            $discord->logger->info('Created sheet for Guild ' . $dGuild->name);
                        }
                        break;

                    case 'update':
                        $extraMessage = "\nIf you're using your own sheet on initial Guildbot usage, first rename your 
                            Worksheet (the small tab on the bottom) to \"Member Management\" - this is the default 
                            Worksheet Name Guildbot is using. After that, you can execute the \"!config sheet rename 
                            worksheet\" command to change the name back to your desired one";

                        $defaultWorkSheetTitle = 'Member Management';
                        $workSheetTitle        = $guildDb->getWorkSheetTitle();

                        $guildDb->setSheetId($args[1]);
                        $guildDb->save();
                        $message->reply(
                            "Sheet ID Updated. Link: https://docs.google.com/spreadsheets/d/{$guildDb->getSheetId()}/edit."
                            . ($workSheetTitle === $defaultWorkSheetTitle ? $extraMessage : '')
                        );
                        $discord->logger->info('Updated sheet for Guild ' . $dGuild->name);
                        break;

                    case 'rename':
                        $errorReply = "I don't know what to rename. Use {$discordConfig['prefix']}config sheet rename sheet/worksheet [title]";

                        if (isset($args[2]) === false) {
                            $message->reply($errorReply);

                            return false;
                        }
                        $sheet = $google->getSheet($guildDb->getSheetId());

                        $newTitleArr = $args;
                        unset($newTitleArr[0]);
                        unset($newTitleArr[1]);
                        $newTitle = implode(' ', $newTitleArr);

                        switch ($args[1]) {
                            case 'sheet':
                                $oldTitle = $sheet->getTitle();

                                $google->renameSheet($guildDb->getSheetId(), $newTitle);

                                $message->reply("done, renamed your sheet from \"{$oldTitle}\" to \"{$newTitle}\"");
                                break;
                            case 'tab':
                            case 'worksheet':
                                $oldTitle = $guildDb->getWorkSheetTitle();

                                $newTitleArr = $args;
                                unset($newTitleArr[0]);
                                unset($newTitleArr[1]);
                                $newTitle = implode(' ', $newTitleArr);

                                $workSheet = $google->renameWorkSheet($sheet, $oldTitle, $newTitle);
                                $guildDb->setWorkSheetTitle($newTitle);
                                $guildDb->save();
                                $message->reply("done, renamed worksheet from \"{$oldTitle}\" to \"{$newTitle}\"");
                                break;
                            default:
                                $message->reply($errorReply);
                                break;
                        }
                        break;
                    default:
                        $message->reply(
                            'You forgot to write what you want to do: field/create/update/rename (' . $exampleStr . ')'
                        );
                        break;
                }

                return true;

            },
            [
                'description' => "[create] [title] will create a new blank sheet for you, when you don't have one - if you have one, it will duplicate it. But it will not copy the permissions, you've to set them manually again.
[update] [sheetid] i will use this sheet.
[rename] [sheet/worksheet] [new title] i will rename the sheet/worksheet title for you (Worksheet is the tab inside the Sheet).
[field] [add/rm/remove] fieldName:fieldType:fieldDefault:fieldColumn add or remove fields to your sheet. possible fieldTypes: int|string. fieldColumn will be autofilled if not given. for e.g. you already have Column A+B+C, the GuildBot will append a new field to column D. Removing fields will only remove it from GuildBot's internal list, not from the sheet itself.
Only fields, that you configured here are updateable from members via GuildBot. All other fields in your sheet will be ignored.

**Important**: Google API is slow, some actions will need some time. GuildBot will always respond to you when he has finished his tasks.
 
**Important**: The very first column in your sheet always has to be \"DiscordID\"
lower or uppercase doesn't matter. So make sure, that the Cell A1 contains \"DiscordID\". When GuildBot created the sheet for you, he will automatically add this field to your Sheet Field Config and to the sheet.
If you want to use your very own sheet, then please add DiscordID as sheet config with this command: \"!config sheet field DiscordID:int:0:A\" (and make sure Column A is empty in your sheet ;)) - otherwise Guildbot wouldn't work.",
                'usage'       => "[field/create/update/rename] [sheetid/sheet|worksheet/[add/rm|remove]] [fieldData]",
            ]
        );
    }

    /**
     * @param Command              $configCommand
     * @param DiscordCommandClient $discord
     * @param array                $discordConfig
     *
     * @throws \Exception
     */
    public static function registerConfigCleanupSubCommand(Command $configCommand, DiscordCommandClient $discord, array $discordConfig)
    {
        $configCommand->registerSubCommand(
            'cleanup',
            function ($message, $args) use ($discordConfig, $discord) {
                Propel::disableInstancePooling();

                if (Helper::checkGuildChannel($message) === false) {
                    return false;
                }

                if (Helper::checkGuildAdmin($message, $discord) === false) {
                    return false;
                }

                if (empty($args)) {
                    $message->reply("You need to tell me what i should do. Try {$discordConfig['prefix']}help config sheet");

                    return false;
                }

                $exampleStr = '!config cleanup activate/deactivate';

                /** @var $message Message */
                /** @var Author $author */
                $author = $message->author;

                /** @var Guild $guildDb */
                $guildDb = GuildQuery::create()
                    ->findPk($author->guild_id);

                /** @var \Discord\Parts\Guild\Guild $dGuild */
                $dGuild = $discord->guilds->get('id', $author->guild_id);

                $google = new Google($discord->logger);
                $google->connect();

                switch ($args[0]) {
                    case 'activate':
                        $message->channel->sendMessage('Work in progress....');
                        break;

                    case 'deactivate':
                        $message->channel->sendMessage('Work in progress....');
                        break;
                    default:
                        $message->reply('You forgot to write what you want to do: activate/deactivate (' . $exampleStr . ')');
                        break;
                }

                return true;

            },
            [
                'description' => "will activate/deactivate the bot channel cleanup. When active, Guildbot will delete every message after 60 seconds.",
                'usage'       => "[activate/deactivate]",
            ]
        );
    }
}