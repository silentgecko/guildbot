<?php

namespace GuildBot\Helper;

use Discord\DiscordCommandClient;
use Discord\Parts\Channel\Message;
use GuildBot\Model\Guild;
use GuildBot\Model\GuildQuery;
use Propel\Runtime\Propel;

class Helper
{
    /**
     * @var string
     */
    private static $admin;

    /**
     * @param Message $message
     *
     * @return bool
     */
    public static function checkGuildChannel(Message $message)
    {
        Propel::disableInstancePooling();
        $guild = GuildQuery::create()
            ->findPk($message->author->guild_id);

        if (count($guild->getChannels()) > 0 && in_array($message->channel->id, $guild->getChannels()) === false) {
            $message->reply(
                'Usage of Guildbot is not allowed in here. Please use <#' . implode('> <#', $guild->getChannels()) . '>'
            );

            return false;
        }

        return true;
    }

    /**
     * @param Message $message
     *
     * @return bool
     */
    public static function checkGuildAdmin(Message $message, DiscordCommandClient $discord)
    {
        $author = $message->author;
        Propel::disableInstancePooling();

        /** @var Guild $guildDb */
        $guildDb = GuildQuery::create()
            ->findPk($author->guild_id);

        $access = false;
        if ($author->id == self::getAdmin()) {
            $message->reply(
                'Ahh... mighty Admin. Hail Admin! Sure you\'ve access.'
            );
            return true;
        }

        if (empty($author->roles) === null) {

            $access = false;
        }
        $discord->logger->debug('Count Admin Roles' . count($guildDb->getAdminRoles()));
        if (count($guildDb->getAdminRoles()) <= 0) {
            // check author roles for admin access
            /** @var \Discord\Parts\Guild\Role $role */
            foreach ($author->roles as $role) {

                $discord->logger->debug('Debug no roles configured');
                $discord->logger->debug($role);
                $discord->logger->debug($role->permissions->administrator);

                if ($role->permissions->administrator === true) {
                    $access = true;

                    break 1;
                }
            }
        } else {
            $discord->logger->debug('Count Admin Roles' . count($guildDb->getAdminRoles()));
            $discord->logger->debug('Author Roles' . print_r($author->roles, true));
            foreach ($author->roles as $role) {

                $discord->logger->debug('Debug roles configured');
                $discord->logger->debug($role->id);
                $discord->logger->debug($guildDb->hasAdminRole($role->id));
                if ($guildDb->hasAdminRole($role->id) === true) {
                    $access = true;

                    break 1;
                }
            }
        }

        if ($access === false) {
            $message->reply(
                'You don\'t have permission to this command. Please ask an admin to add your Role to the List of admin roles or grant admin access to Discord server.'
            );

            return false;
        }

        return true;
    }

    /**
     * @param Message $message
     *
     * @return bool
     */
    public static function checkGuildMember(Message $message)
    {
        $author = $message->author;
        Propel::disableInstancePooling();

        /** @var Guild $guildDb */
        $guildDb = GuildQuery::create()
            ->findPk($author->guild_id);

        $access = false;
        if (empty($author->roles) === null) {

            $access = false;
        }

        if (count($guildDb->getMemberRoles()) <= 0) {

            // check author roles for admin access
            /** @var \Discord\Parts\Guild\Role $role */
            foreach ($author->roles as $role) {
                if ($role->permissions->administrator === true) {
                    $access = true;

                    break 1;
                }
            }
        } else {
            foreach ($author->roles as $role) {
                if ($guildDb->hasMemberRole($role->id) === true) {
                    $access = true;

                    break 1;
                }
            }
        }

        if ($access === false) {
            $message->reply(
                "You don't have permission to this command. Please ask an admin to add your Role to the List of admin roles or grant admin access to Discord server."
            );

            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public static function getAdmin()
    {
        return self::$admin;
    }

    /**
     * @param string $admin
     */
    public static function setAdmin($admin)
    {
        self::$admin = $admin;
    }
}