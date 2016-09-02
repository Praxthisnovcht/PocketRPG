<?php

namespace PocketRPG\commands;

use PocketRPG\Main;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\Player;
use pocketmine\Server;

class PartyCommands extends PluginBase implements CommandExecutor {
  
  public $plugin;
  
  public function __construct(Main $plugin) {
    $this->plugin = $plugin;
  }
  public function getPlugin() {
    return $this->plugin;
  }
  
  public function onCommand(CommandSender $p, Command $cmd, $label, array $args) {
    if($cmd->getName() == "party") {
      switch(strtolower($args[0])) {
        
        case "invite":
            @mkdir($this->getDataFolder () . "plugins/PocketRPG/party/");
            @file_put_contents ($this->getDataFolder () . "plugins/PocketRPG/party/" . $p->getName () . ".yml", yaml_emit([
              "Pending" => array (),
              "Allies" => array ()
            ]));
            $party = new Config ($this->getDataFolder () . "plugins/PocketRPG/party/" . $p->getName () . ".yml");
            $target = $this->getPlugin ()->getServer ()->getPlayer($args [1]);
            if ($target instanceof Player) {
              $player = $party->get("Pending", []);
              $player[] = $target->getName ();
              $party->set("Pending", $player);
              $party->save ();
              $p->sendMessage (TF::GREEN . "A request has been sent to " . $target->getName () . "!");
              $target->sendMessage (TF::GREEN . "The Player " . $p->getName () . " has invited you to his/her party!\n" . TF::GREEN . "/party accept " . $p->getName () . TF::AQUA . ": to accept.\n" . TF::GREEN . "/party reject " . $p->getName () . TF::AQUA . ": to reject.");
            } else {
              $p->sendMessage (TF::RED . "That player is not online!");
            }
        return true;
        break;
        
        case "accept":
          if (file_exists ($this->getDataFolder () . "plugin/PocketRPG/party/" . $args [1] . ".yml")) {
            $party = new Config ($this->getDataFolder () . "plugins/PocketRPG/party/" . $args [1] . ".yml");
            if (in_array ($p->getName (), $quest->get ("Pending", array ()))) {
              $p->sendMessage(TF::GREEN . "You have joined the party of " . $args [1] . "!");
              $player = $party->get("Allies", []);
              $player[] = $p->getName ();
              $party->set("Allies", $player);
              $party->save ();
              $pending = $party->get("Pending");
              unset($pending[array_search($p->getName (), $pending)]);
              $party->set("Pending", $pending);
              $party->save();
            }
          }
        return true;
        break;
        
        case "reject":
          
        return true;
        break;
        
        case "leave":
          
        return true;
        break;
        
        case "help":
          
        return true;
        break;
      }
    }
  }
}