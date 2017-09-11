<?php

namespace FantasyHunger;

use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\level\Level;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\level\particle\HeartParticle;

class Main extends PluginBase implements Listener {

  public function onEnable(){
    if(!file_exists($this->getDataFolder() . "config.yml")){
      $this->getLogger()->notice("Creating configuration file...");
      @mkdir($this->getDataFolder());
     	file_put_contents($this->getDataFolder()."config.yml", $this->getResource("config.yml"));
      $this->getLogger()->notice("Successfully created config!");
   	}
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->getLogger()->notice("FantasyHunger Enabled!");
  }
  
  public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
	  if(!$sender instanceof Player){
		$sender->sendMessage("§5>§c Please run this command in-game.");  
		  return false;
	  }
    switch($command->getName()){
       	case "hunger":{
            if(isset($args[0])){
            	switch($args[0]){
			case "on":{
                  		$hunger = $this->getConfig()->get("Hunger_Disabled");
                 		$world = $sender->getLevel()->getName();
                  
                 		if(in_array($world, $hunger)){
                    			$array = $this->getConfig()->get("Hunger_Disabled");
					$rm = $sender->getLevel()->getName();
					$config = [];
					foreach($array as $value) {
						if($value != $rm) {
							$config[] = $value;
						}
					}
					$this->getConfig()->set("Hunger_Disabled", $config);
					$this->getConfig()->save();
                    			$sender->sendMessage("§5>§d You've sucessfully Enabled Hunger on level " . $world);
                  		}else{
                  			 $sender->sendMessage("§5>§c Hunger is already enabled on level " . $world);
                  		}
                	}
                 	break;
                	case "off":{
                  		$hunger = $this->getConfig()->get("Hunger_Disabled");
                  		$world = $sender->getLevel()->getName();
                  
                  		if(in_array($world, $hunger)){
                    			$sender->sendMessage("§5>§c Hunger is already disabled on level " . $world);
                    			break;
                  		}	
                 		$array = $this->getConfig()->get("Hunger_Disabled");
				$config = $array;
				$config[] = $sender->getLevel()->getName();
				$this->getConfig()->set("Hunger_Disabled", $config);
				$this->getConfig()->save();
                 		$sender->sendMessage("§5>§d You've sucessfully Disabled Hunger on level " . $world);
                	}
              	}
            	}else{
             		$sender->sendMessage("§l§dUsage§5>§r§b /hunger <on|off>"); 
              		return false;
            	}
           	 return true;
          }
	    case "feed":{
		if ($sender instanceof Player) {
        		if (count($args) != 0) {
				$name = $args[0];
				$pl = $this->plugin->getServer()->getPlayer($name);
               		 if($pl instanceof Player){ 
				 $pl->setFood(20);
				 $pl->getLevel()->addParticle(new HeartParticle($player->add(0, 2), 4));
				 $sender->sendMessage("§l§5>§r§b You've successfully feed " . $pl);
				 $player->sendMessage("§l§5>§b You've been fed!");
				 return true;
			 }else{
				$sender->sendMessage("§l§dNotice§5>§r§c That player isn't online!");
				 return true;
			 }
	    }else{
		$sender->sendMessage("§l§5>§b You've been fed!");
		$pl->setFood(20);
		$pl->getLevel()->addParticle(new HeartParticle($player->add(0, 2), 4));	
		return true;
	     }
		}else{
			$sender->sendMessage("§l§5>§c You cannot feed the console!");
			return true;
		}
	    }
    }
  }
	
	
	public function onExhaust(PlayerExhaustEvent $event){
		$player = $event->getPlayer();
		$world = $player->getLevel()->getName();
		$hunger = $this->getConfig()->get("Hunger_Disabled");
		  if(in_array($world, $hunger)){
             		$event->setCancelled(true);
	   	 }
	}
}
