<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 26.03.2018
 * Time: 20:10
 */

namespace GuildBot;

use Discord\DiscordCommandClient;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;
use GuildBot\Helper\ConfigCommand;
use GuildBot\Helper\GeneralCommand;
use GuildBot\Helper\HelpCommand;
use GuildBot\Helper\Helper;
use GuildBot\Helper\MemberCommand;
use GuildBot\Model\AnnouncementQuery;
use GuildBot\Model\Guild;
use GuildBot\Model\GuildQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class GuildBot
{
    /**
     * @var DiscordCommandClient
     */
    private $discord;

    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    private $discordConfig;

    /**
     * GuildBot constructor.
     *
     * @param DiscordCommandClient $discord
     * @param array                $config
     * @param array                $discordConfig
     *
     * @throws \Exception
     */
    /**
     * GuildBot constructor.
     *
     * @param DiscordCommandClient $discord
     * @param array                $config
     * @param array                $discordConfig
     *
     * @throws \Exception
     */
    public function __construct(DiscordCommandClient $discord, array $config, array $discordConfig)
    {
        $this->discord = $discord;
        $this->config = $config;
        $this->discordConfig = $discordConfig;

        Helper::setAdmin($config['admin']);
        $this->addListeners();

        HelpCommand::registerHelpCommand($this->discord, $this->discordConfig);
        GeneralCommand::registerPingCommand($this->discord);
        GeneralCommand::registerGuildBotCommand($this->discord);

        ### Sheet Command
        MemberCommand::registerSheetCommand($this->discord);

        ### Member Command
        MemberCommand::registerMemberCommand($this->discord, $this->discordConfig);

        ### Update Command
        MemberCommand::registerUpdateCommand($this->discord, $this->discordConfig);

        ### Reminder Command
        MemberCommand::registerReminderCommand($this->discord, $this->discordConfig);

        ### Config Command
        $configCommand = ConfigCommand::registerConfigCommand($this->discord, $this->discordConfig);

        ### Config role
        ConfigCommand::registerConfigRoleSubCommand($configCommand, $this->discord, $this->discordConfig);

        ### Config Channel
        ConfigCommand::registerConfigChannelSubCommand($configCommand, $this->discord, $this->discordConfig);

        ### Config Sheet
        ConfigCommand::registerConfigSheetSubCommand($configCommand, $this->discord, $this->discordConfig);

        ### Config Field
        ConfigCommand::registerConfigFieldSubCommand($configCommand, $this->discord, $this->discordConfig);

        ### Config Cleanup
        ConfigCommand::registerConfigCleanupSubCommand($configCommand, $this->discord, $this->discordConfig);
    }

    /**
     * @return DiscordCommandClient
     */
    public function getDiscord()
    {
        return $this->discord;
    }

    /**
     *
     */
    public function addListeners()
    {
        $this->addOnJoinListener();
        $this->addOnLeaveListener();
        $this->addBroadCastListener();
        $this->addDebugListener();
    }

    /**
     *
     */
    public function addDebugListener()
    {
        if ($this->config['debug']) {
            $this->discord->on(
                Event::MESSAGE_CREATE,
                function ($message) {
                    /** @var $message Message */
                    $reply = $message->timestamp->format('d/m/y H:i:s') . ' - DEBUG: '; // Format the message timestamp.
                    $reply .= $message->channel->guild->name . ' - ';
                    $reply .= '#' . $message->channel->name . ' - ';
                    $reply .= $message->author->username . ' - '; // Add the message author's username onto the string.
                    $reply .= $message->content; // Add the message content.

                    $this->discord->logger->debug($reply);

                }
            );
        }
    }

    /**
     *
     */
    public function addBroadCastListener()
    {
        $this->discord->on(
            Event::MESSAGE_CREATE,
            function ($message) {
                /** @var $message Message */

                $broadcasts = AnnouncementQuery::create()
                    ->joinWithGuild(Criteria::LEFT_JOIN)
                    ->orderByCreatedAt(Criteria::ASC)
                    ->findByBroadcastedAt(null);

                // get only active guilds
                $guilds = GuildQuery::create()
                    ->findByActive(true);

                if ($broadcasts->count() > 0) {
                    $this->discord->logger->info('Triggered broadcasting Message to Guilds');
                }

                foreach ($broadcasts as $broadcast) {

                    foreach ($guilds as $guild) {

                        if ($broadcast->getGuild() !== null && $broadcast->getGuild()
                                                                   ->getId() !== $guild->getId()) {
                            $this->discord->logger->info('Skipped broadcast to Guild: ' . $guild->getName());
                            continue;
                        }

                        // prepare broadcast Message
                        $broadcastMessage = $broadcast->getMessage();

                        $roles = array_unique(array_merge($guild->getAdminRoles(), $guild->getMemberRoles()));

                        $rolesStr       = ' <@&' . implode('> <@&', $roles) . '> ';
                        $adminRolesStr  = ' <@&' . implode('> <@&', $guild->getAdminRoles()) . '> ';
                        $memberRolesStr = ' <@&' . implode('> <@&', $guild->getMemberRoles()) . '> ';

                        $broadcastMessage = str_replace('{roles}', $rolesStr, $broadcastMessage);
                        $broadcastMessage = str_replace('{admin}', $adminRolesStr, $broadcastMessage);
                        $broadcastMessage = str_replace('{member}', $memberRolesStr, $broadcastMessage);

                        $channels = $guild->getChannels();

                        $dGuild = $this->discord->guilds->get('id', $guild->getId());

                        /** @var $dChannel Channel */
                        if (empty($channels) === false) {
                            foreach ($channels as $channel) {
                                $dChannel = $dGuild->channels->get('id', $channel);
                                $dChannel->sendMessage($broadcastMessage);
                                $broadcast->setBroadcastedAt(new \DateTime('now'));
                                $broadcast->save();
                                $this->discord->logger->info(
                                    "Broadcast sent successfully to " . $dGuild->name . " / " . $dChannel->name
                                );
                            }
                        } else {
                            foreach ($dGuild->channels as $dChannel) {
                                if ($dChannel->type === 0 && empty($dChannel->permission_overwrites) && $dChannel->is_private === false) {
                                    $dChannel->sendMessage($broadcastMessage);

                                    $broadcast->setBroadcastedAt(new \DateTime('now'));
                                    $broadcast->save();
                                    $this->discord->logger->info(
                                        "Broadcast sent successfully to " . $dGuild->name . " / " . $dChannel->name
                                    );
                                    break;
                                }
                            }
                        }

                    }
                }

            }
        );
    }

    /**
     *
     */
    public function addOnJoinListener() {
        $this->discord->on(
            Event::GUILD_CREATE,
            function ($guild) {
                /** @var $guild \Discord\Parts\Guild\Guild */

                // heck if the guild is active
                $guildDb = GuildQuery::create()
                    ->findPk($guild->id);

                if ($guildDb === null) {
                    $guildDb = new Guild;
                    $guildDb->setId($guild->id)
                        ->setName($guild->name);
                    $guildDb->save();
                    $this->discord->logger->info("Joined and activated Server " . $guild->name);
                }

                $guildDb->setName($guild->name)
                    ->setActive(true);

                $guildDb->save();

                $this->discord->logger->info("Updated Server " . $guild->name);

            }
        );
    }

    /**
     *
     */
    public function addOnLeaveListener()
    {
        $this->discord->on(
            Event::GUILD_DELETE,
            function ($guild) {
                /** @var $guild \Discord\Parts\Guild\Guild */

                // heck if the guild is active
                $guildDb = GuildQuery::create()
                    ->findPk($guild->id);

                $guildDb->setName($guild->name)
                    ->setActive(false);

                // deactivate broadcasts that are still in queue. should not happen, but make it safe.
                $broadcasts = AnnouncementQuery::create()
                    ->findByGuildId($guildDb->getId());
                foreach ($broadcasts as $broadcast) {
                    $broadcast->setBroadcastedAt(new \DateTime('now'));
                }

                $guildDb->save();
                $this->discord->logger->info("Deactivated Server: " . $guild->name);

            }
        );
    }
}