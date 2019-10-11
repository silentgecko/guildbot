<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 26.03.2018
 * Time: 19:40
 */

namespace GuildBot\Helper;

use Discord\DiscordCommandClient;
use Google\Spreadsheet\CellEntry;
use GuildBot\Model\Guild;
use GuildBot\Model\GuildQuery;
use GuildBot\Model\SheetConfig;
use GuildBot\Model\SheetConfigQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;

class MemberCommand
{

    /**
     * @param DiscordCommandClient $discord
     *
     * @throws \Exception
     */
    public static function registerSheetCommand(DiscordCommandClient $discord)
    {
        $discord->registerCommand(
            'sheet',
            function ($message, $args) use ($discord) {

                if (Helper::checkGuildChannel($message) === false) {
                    return false;
                }

                if (Helper::checkGuildMember($message) === false) {
                    return false;
                }

                $guildDb = GuildQuery::create()
                    ->findPk($message->author->guild_id);
                if ($guildDb->getSheetId() !== '') {
                    $message->reply(
                        "Here's your Sheet: https://docs.google.com/spreadsheets/d/{$guildDb->getSheetId()}/edit"
                    );
                } else {
                    $message->reply("You don't have a sheet configured yet. Please use !help config sheet");
                }

                return true;

            },
            [
                'description' => 'Links you your actual Spreadsheet.',
            ]
        );
    }

    /**
     * @param DiscordCommandClient $discord
     * @param array                $discordConfig
     *
     * @return \Discord\CommandClient\Command
     * @throws \Exception
     */
    public static function registerMemberCommand(DiscordCommandClient $discord, array $discordConfig)
    {
        $memberCommand = $discord->registerCommand(
            'member',
            function ($message, $args) use ($discord, $discordConfig) {

                if (Helper::checkGuildChannel($message) === false) {
                    return false;
                }

                if (Helper::checkGuildAdmin($message, $discord) === false) {
                    return false;
                }

                $errorReply = "You need to tell me what i should do. Try {$discordConfig['prefix']}help member";
                if (empty($args)) {
                    $message->reply($errorReply);

                    return false;
                }
                if ($message->mentions->count() <= 0) {
                    $message->reply("You forgot to highlight someone. Try {$discordConfig['prefix']}help member");

                    return false;
                }

                $users = [];
                foreach ($message->mentions as $uId => $mention) {
                    $users[$uId] = $uId;
                }
                $users = array_unique($users);

                $google = new Google($discord->logger);
                $google->connect();

                $guildDb = GuildQuery::create()
                    ->findPk($message->author->guild_id);

                $sheet     = $google->getSheet($guildDb->getSheetId());
                $workSheet = $google->getWorkSheet($sheet, $guildDb->getWorkSheetTitle());
                $listFeed  = $workSheet->getListFeed();

                switch ($args[0]) {
                    case 'add':
                        foreach ($listFeed->getEntries() as $entry) {
                            $sheetData = $entry->getValues();

                            // check four duplicates
                            foreach ($users as $userId) {
                                $search = array_search($userId, $sheetData);
                                if ($search !== false) {
                                    unset($users[$sheetData[$search]]);
                                    $message->reply("<@{$sheetData[$search]}> is already in the sheet, skipped.");
                                }
                            }
                        }

                        // return if no members left to reduce load
                        if (empty($users)) {
                            return false;
                        }

                        $sheetConfigs = $guildDb->getSheetConfigs();
                        $insertData   = [];

                        foreach ($sheetConfigs as $sheetConfig) {
                            $insertData[$sheetConfig->getField()] = $sheetConfig->getDefaultValue();
                        }

                        // insert new members
                        foreach ($users as $userId) {
                            // replace discord id with real value
                            $insertData['discordid'] = $userId;

                            $listFeed->insert($insertData);
                            $message->channel->sendMessage("Added Member <@$userId> with default values.");
                        }
                        $message->channel->sendMessage("Done adding Members.");

                        break;
                    case 'rm':
                    case 'remove':
                        $sheetConfigs = $guildDb->getSheetConfigs();
                        // define columns array (0 = A, 1 = B and so on)
                        $columns = range('A', 'ZZ');

                        // we have to use cellfeed here, because listfeed will stop on empty rows
                        foreach (
                            $workSheet->getCellFeed()
                                ->getEntries() as $cell
                        ) {

                            // skip title row first
                            if ($cell->getRow() === 1) {
                                continue;
                            }

                            if ($cell->getColumn() !== 1) {
                                continue;
                            } else {
                                // aight, we found a matching pair, now we check if we really want to update this
                                // we can assume, that column 1 / A is always Discord ID
                                foreach ($users as $userId) {
                                    $discord->logger->debug("checking cell: {$cell->getTitle()} for UserId: {$userId}");
                                    if ($userId == $cell->getContent()) {
                                        $rowToDelete = $cell->getRow();
                                        $discord->logger->debug("Row to del: {$rowToDelete} - UserId: {$userId}");

                                        // now clear all configured cells
                                        foreach ($sheetConfigs as $sheetConfig) {
                                            $columnToDelete = array_search($sheetConfig->getColumn(), $columns) + 1;
                                            $discord->logger->debug(
                                                "Row to del: {$rowToDelete} - Column to del: {$columnToDelete} - UserId: {$userId}"
                                            );
                                            $workSheet->getCellFeed()
                                                ->editCell($rowToDelete, $columnToDelete, '');
                                        }
                                        $message->reply("Removed <@{$userId}> from sheet.");
                                        unset($users[$userId]);
                                    }
                                }
                            }
                        }

                        if (empty($users) === false) {
                            $userStr = '<@' . implode('>, <@', $users) . '>';
                            $message->reply("Couldn't find the following users in your sheet: {$userStr}");
                        }
                        break;
                    default:
                        $message->reply($errorReply);

                        return false;
                        break;
                }

                return true;
            },
            [
                'description' => "Add/Remove Members from/to the spreadsheet.",
                'usage'       => "[add/rm|remove] @user1 @user2 .... @userN
You can also do it manually, just copy the ID from your members and add them to the A Column (DiscordID).

**Important**: Google API is slow, some actions will need some time. GuildBot will always respond to you when he has finished his tasks.",
            ]
        );

        return $memberCommand;
    }

    /**
     * @param DiscordCommandClient $discord
     * @param array                $discordConfig
     *
     * @throws \Exception
     */
    public static function registerUpdateCommand(DiscordCommandClient $discord, array $discordConfig)
    {
        $discord->registerCommand(
            'update',
            function ($message, $args) use ($discord, $discordConfig) {

                if (Helper::checkGuildChannel($message) === false) {
                    return false;
                }

                if (Helper::checkGuildMember($message) === false) {
                    return false;
                }

                if (empty($args)) {
                    $message->reply("You need to give me some fields. Try {$discordConfig['prefix']}help update");
                }

                /** @var Guild $guildDb */
                $guildDb = GuildQuery::create()
                    ->findPk($message->author->guild_id);

                //connect to google api
                $google = new Google($discord->logger);
                $google->connect();

                $dataToUpdate = [];
                $errorFields  = [];

                foreach ($args as $fieldValue) {
                    list($field, $value) = explode(':', $fieldValue);

                    $field               = strtolower($field);
                    $errorFields[$field] = $field;

                    foreach ($guildDb->getSheetConfigs() as $sheetConfig) {
                        if ($field == $sheetConfig->getField()) {
                            $dataToUpdate[$field] = $value;
                            unset($errorFields[$field]);
                        }
                    }
                }

                if (empty($errorFields) === false) {
                    $errorReply = implode(',', $errorFields);
                    $message->reply("NOT udpated this fields: {$errorReply} - i don't know this fields");
                }

                $workSheet   = $google->getSheet($guildDb->getSheetId())
                    ->getWorksheetFeed()
                    ->getByTitle($guildDb->getWorkSheetTitle());
                $cellFeed    = $workSheet->getCellFeed();
                $cellEntries = $cellFeed->getEntries();

                $sheetConfigs = $guildDb->getSheetConfigs();
                // define columns array (0 = A, 1 = B and so on)
                $columns = range('A', 'ZZ');

                $userId = $message->author->id;

                // we have to use cellfeed here, because listfeed will stop on empty rows
                foreach ($cellEntries as $cell) {

                    // skip title row first
                    if ($cell->getRow() === 1) {
                        continue;
                    }

                    if ($cell->getColumn() !== 1) {
                        continue;
                    } else {
                        // aight, we found a matching pair, now we check if we really want to update this
                        // we can assume, that column 1 / A is always Discord ID
                        if ($userId == $cell->getContent()) {
                            $rowToUpdate = $cell->getRow();

                            $newDataStr = '';
                            // now get all configured columns and check if we have to update it
                            foreach ($sheetConfigs as $sheetConfig) {
                                // do nothing on not given fields
                                if (isset($dataToUpdate[$sheetConfig->getField()]) === false) {
                                    continue;
                                }

                                $columnToUpdate = array_search($sheetConfig->getColumn(), $columns) + 1;
                                $discord->logger->debug(
                                    "Row to update: {$rowToUpdate} - Column to update: {$columnToUpdate} - UserId: {$userId}"
                                );
                                $cellFeed->editCell(
                                    $rowToUpdate,
                                    $columnToUpdate,
                                    $dataToUpdate[$sheetConfig->getField()]
                                );

                                $newDataStr .= "{$sheetConfig->getField()}: {$dataToUpdate[$sheetConfig->getField()]}\n";

                                // unset to reply error if needed
                                unset($dataToUpdate[$sheetConfig->getField()]);
                            }
                            $message->reply("Updated this data for you:\n{$newDataStr}");
                        }
                    }
                }

                return true;

            },
            [
                'description' => "Update your own data in your guildsheet.",
                'usage'       => "fieldName1:fieldValue1 ... fieldNameN:fieldValueN
Don't forget the colon between field name and value, and do not add spaces around the colon or inside values. Spaces are atm not supported. For e.g.: !update AP:275 FirstName:Flooki Class:BestWizardEu
fieldnames will be mapped to lowercase, so don't worry if you don't write it right. For e.g. !update FIRSTNAME:275 is the same as firstname:Flooki
The order of fields doesn't matter
Possible fields to update:
{fieldList}",
            ]
        );
    }

    /**
     * @param DiscordCommandClient $discord
     * @param array                $discordConfig
     *
     * @throws \Exception
     */
    public static function registerReminderCommand(DiscordCommandClient $discord, array $discordConfig)
    {
        $discord->registerCommand(
            'reminder',
            function ($message, $args) use ($discord, $discordConfig) {
                Propel::disableInstancePooling();

                if (Helper::checkGuildChannel($message) === false) {
                    return false;
                }

                if (Helper::checkGuildAdmin($message, $discord) === false) {
                    return false;
                }

                if (empty($args)) {
                    $message->reply("You need to give me one field. Try {$discordConfig['prefix']}help reminder");
                }

                /** @var Guild $guildDb */
                $guildDb = GuildQuery::create()->findPk($message->author->guild_id);

                // get desired field
                /** @var SheetConfig $desiredField */
                $desiredField = null;
                foreach ($guildDb->getSheetConfigs() as $sheetConfig) {
                    if ($args[0] == $sheetConfig->getField()) {
                        $desiredField = $sheetConfig;
                        break;
                    }
                }
                /** @var SheetConfig $discordIdField */
                $discordIdField = null;
                foreach ($guildDb->getSheetConfigs() as $sheetConfig) {
                    if ('discordid' == $sheetConfig->getField()) {
                        $discordIdField = $sheetConfig;
                        break;
                    }
                }

                //connect to google api
                $google = new Google($discord->logger);
                $google->connect();

                $workSheet   = $google->getSheet($guildDb->getSheetId())
                    ->getWorksheetFeed()
                    ->getByTitle($guildDb->getWorkSheetTitle());
                $cellFeed    = $workSheet->getCellFeed();
                $cellEntries = $cellFeed->toArray();

                // define columns array (0 = A, 1 = B and so on)
                $columns = range('A', 'ZZ');

                $userToRemind = [];

                /** @var CellEntry $cell */
                foreach ($cellEntries as $rowInt => $rowData) {
                    // skip title row first
                    if ($rowInt === 1) {
                        continue;
                    }

                    $column = array_search($desiredField->getColumn(), $columns) + 1;
                    $discordIdColumn = array_search($discordIdField->getColumn(), $columns) + 1;

                    if (isset($rowData[$column]) === false
                        || $rowData[$column] === ''
                        || ($desiredField->getType() === 'int' && $rowData[$column] == 0)
                    ) {
                        $userToRemind[] = $rowData[$discordIdColumn];
                    }
                }
                $userToRemind = array_unique($userToRemind);
                $userToRemindStr = '<@' . implode('>, <@', $userToRemind) . '>';

                $message->channel->sendMessage(
                    "Hey you slackers! Jeah... i meant you {$userToRemindStr} !"
                    . "\nGet your ass up and fill out the field {$args[0]} via `!update {$args[0]}:{$desiredField->getDefaultValue()}` ... !"
                    . "\nDo it... Do it **NOW!** ",
                    false
                );
                return true;

            },
            [
                'description' => "Reminder for the slackers",
                'usage'       => "fieldName
Will send reminders to everyone who has not filled this field. We will call this command the \"slacker command\". :)
Possible fields to remind:
{fieldList}",
            ]
        );
    }
}