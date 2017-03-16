<?php 
	namespace geaking;
// §4✘ ↪ §


/************************Импорты******************/
	use pocketmine\level\particle\FlameParticle;
	use pocketmine\math\Vector3;
	use pocketmine\command\ConsoleCommandSender;
	use pocketmine\plugin\PluginManager;
	use pocketmine\item\enchantment\Enchantment;
	use pocketmine\level\Level;
	use pocketmine\event\player\PlayerItemHeldEvent;
	use pocketmine\event\player\PlayerInteractEvent;
	use pocketmine\level\sound\AnvilFallSound;
	use pocketmine\entity\Effect;
	use pocketmine\Player;
	use pocketmine\Server;
	use pocketmine\plugin\PluginBase;
	use pocketmine\event\Listener;
	use pocketmine\command\CommandSender;
	use pocketmine\command\Command;
	use pocketmine\lang\BaseLang;
    use pocketmine\item\Item;
    use pocketmine\inventory\PlayerInventory;
    use pocketmine\inventory\Inventory;
	use pocketmine\utils\Config;
	use onebone\economyapi\EconomyAPI;   
    /*************************Код*********************/
class crDonateCase extends PluginBase implements Listener {

			private $cfg;

			public function onEnable() {
			$this->getLogger()->info("§2Плагин включен.");	
			$this->getLogger()->info("§2Создатель vk.com/easymanfifa");	
			$this->PP = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
			$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");					
   			$this->getServer()->getPluginManager()->registerEvents($this, $this);
   			$case = [
   			"pricedonate" => "50000",
   			"pricemoney" => "5000"];
			$folder = $this->getDataFolder();
			@mkdir($folder);
			$this->cfg = new Config($folder . " config.yml", Config::YAML, $case);
			$this->cfg->save();   			
}
				public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {				
				switch ($cmd->getName()) { 
				case 'donatecase':
				if($args[0] == "help") {
				if($sender instanceof Player){
				$sender->sendMessage('§2§===========crDonateCase==========§r'); 
				$sender->sendMessage('§6§↪ Список кейсов - /donatecase list !§r'); 
				$sender->sendMessage('§6§↪ Купить кейсы - /donatecase donate/money !§r');					
				}else{
				$sender->sendMessage('§2§[crDonateCase] §4✘ Нельзя купить кейс через консоль !§r');	
				}
			}
				if($args[0] == "list") {
				if($sender instanceof Player){
				$pricedonate = $this->cfg->get('pricedonate');
				$pricemoney = $this->cfg->get('pricemoney');
				$sender->sendMessage('§2§===========crDonateCase==========§r'); 
				$sender->sendMessage('§6§↪ Донат-Кейс( ' .$pricedonate. ')!§r'); 
				$sender->sendMessage('§6§↪ Денежный-Кейс(' .$pricemoney. ')!§r'); 					
				}else{
				$sender->sendMessage('§2§[crDonateCase] §4✘ Нельзя купить кейсы через консоль !§r');		
				}
			}
				if($args[0] == "money") {
				if($sender instanceof Player){	
				if($sender->getGamemode() == 0){
				$money = $this->eco->mymoney($sender);
				$pricemoney = $this->cfg->get('pricemoney');						
				if($money >= $pricedonate){	
				$pricedonate = $this->cfg->get('pricedonate');
				$pricemoney = $this->cfg->get('pricemoney');							
                 $casemoney = Item::get(407, 0, 1);
                 $casemoney->setCustomName("§dДенежный-Кейс");
                 $casemoney->addEnchantment(Enchantment::getEnchantment(0)->setLevel(1));
                 $sender->getInventory()->addItem($casemoney);
               	 $this->eco->reduceMoney($sender, $pricemoney);
				$sender->sendMessage('§2§[crDonateCase] §6✔ Денежный-Кейс успешно куплен !§r');               	 
             }else{
				$pricemoney = $this->cfg->get('pricemoney');
				$sender->sendMessage('§2[crDonateCase] §4✘ Вам нужно ' .$pricemoney. ' монет чтобы купить этот кейс !§r');             	
             }
				}else{
				$sender->sendMessage('§2[crDonateCase] §4✘ Выключи режим креатива чтобы купить кейсы !§r');	
				}
			}else{
				$sender->sendMessage('§2[crDonateCase] §4✘ Нельзя купить кейсы через консоль !§r');					
			}
		}
				if($args[0] == "donate") {
				if($sender instanceof Player){	
				if($sender->getGamemode() == 0){
				$money = $this->eco->mymoney($sender);
				$pricedonate = $this->cfg->get('pricedonate');									
				if($money >= $pricedonate){
				$pricedonate = $this->cfg->get('pricedonate');
				$pricemoney = $this->cfg->get('pricemoney');									
                 $casedonate = Item::get(342, 0, 1);
                 $casedonate->setCustomName("§dДонат-Кейс");
                 $casedonate->addEnchantment(Enchantment::getEnchantment(0)->setLevel(1));
                 $sender->getInventory()->addItem($casedonate);
				$sender->sendMessage('§2[crDonateCase] §6✔ Донат-Кейс успешно куплен !§r');                    
               	 $this->eco->reduceMoney($sender, $pricedonate);
             }else{
				$pricedonate = $this->cfg->get('pricedonate');             	
				$sender->sendMessage('§2[crDonateCase] §4✘ Вам нужно ' .$pricedonate. ' монет чтобы купить этот кейс !§r');             	
             }
				}else{
				$sender->sendMessage('§2[crDonateCase] §4✘ Выключи режим креатива чтобы купить кейсы !§r');	
				}
			}else{
				$sender->sendMessage('§2[crDonateCase] §4✘ Нельзя купить кейсы через консоль !§r');					
			}
		}
		break;	
		}	
}


			/***********Событие при нажатие************/
		public function casedonate(PlayerInteractEvent $e){
					$p = $e->getPlayer();
					$level = $p->getLevel();
					$name = $p->getName();
					$b = $e->getBlock();
					$x = $b->getX();
					$y = $b->getY();
					$z = $b->getZ();	
					if(($e->getAction() == PlayerInteractEvent::RIGHT_CLICK_AIR) && ($p->getInventory()->getItemInHand()->getId() == 342 )){
					   $p->getInventory()->removeItem(Item::get(342,0,1));
					   $p->getLevel()->addSound(new AnvilFallSound($p));
  $rand = mt_rand(1,12);
   switch($rand){
   	case 1:
	$p = $e->getPlayer();
	$name = $p->getName();   	
	$p->sendMessage('§2[crDonateCase] §6 Вам ничего не выпало из Донат-Кейса !§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' ничего не выпало из Донат-Кейса !§r');
   	break;
   	case 2:
	$p = $e->getPlayer();
	$name = $p->getName();    	
	$p->sendMessage('§2[crDonateCase] §6 Вам выпал вип из Донат-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпал вип из Донат-Кейса ✔!§r');   
	$this->getServer()->dispatchCommand(new ConsoleCommandSender(), 'setgroup ' .$name. ' Vip'); 
   	break;
   	case 3:
	$p = $e->getPlayer();
	$name = $p->getName();    	
	$p->sendMessage('§2[crDonateCase] §6 Вам выпал флай из Донат-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпал флай из Донат-Кейса ✔!§r');   
	$this->getServer()->dispatchCommand(new ConsoleCommandSender(), 'setgroup ' .$name. ' Fly'); 
   	break; 
   	case 4:
	$p = $e->getPlayer();
	$name = $p->getName();    	
	$p->sendMessage('§2[crDonateCase] §6 Вам выпал делюкс из Донат-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпал делюкс из Донат-Кейса ✔!§r');  
	$this->getServer()->dispatchCommand(new ConsoleCommandSender(), 'setgroup ' .$name. ' Delux'); 
   	break; 
   	case 5:
	$p = $e->getPlayer();
	$name = $p->getName();    	
	$p->sendMessage('§2[crDonateCase] §6 Вам выпал флай из Донат-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпал флай из Донат-Кейса ✔!§r');   
	$this->getServer()->dispatchCommand(new ConsoleCommandSender(), 'setgroup ' .$name. ' Fly'); 	
   	break;   
   	case 6:
	$p = $e->getPlayer();
	$name = $p->getName();   	
	$p->sendMessage('§2[crDonateCase] §6 Вам ничего не выпало из Донат-Кейса ❌!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' ничего не выпало из Донат-Кейса ❌!§r');
   	break;
   	case 7:
	$p = $e->getPlayer();
	$name = $p->getName();    	
	$p->sendMessage('§2[crDonateCase] §6 Вам §4Экстра§6 выпало из Донат-Кейса O.O!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпало §4Экстра§6 из Донат-Кейса O.O!§r');
   	$level->addParticle(new FlameParticle(new Vector3($x+rand(0,1.00), $y+rand(0,1.00), $z+rand(0,1.00))));
   	$level->addParticle(new FlameParticle(new Vector3($x+rand(0,1.00), $y+rand(0,1.00), $z+rand(0,1.00))));    	   
	$this->getServer()->dispatchCommand(new ConsoleCommandSender(), 'setgroup ' .$name. ' Extra'); 	
   	break;   
   	case 8:
	$p = $e->getPlayer();
	$name = $p->getName();   	
	$p->sendMessage('§2[crDonateCase] §6 Вам ничего не выпало из Донат-Кейса ❌!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' ничего не выпало из Донат-Кейса ❌!§r');
   	break;
   	case 9:
	$p = $e->getPlayer();
	$name = $p->getName();   	
	$p->sendMessage('§2[crDonateCase] §6 Вам ничего не выпало из Донат-Кейса ❌!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' ничего не выпало из Донат-Кейса ❌!§r');
   	break;
   	case 10:
	$p = $e->getPlayer();
	$name = $p->getName();   	
	$p->sendMessage('§2[crDonateCase] §6 Вам ничего не выпало из Донат-Кейса !§r');  
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' ничего не выпало из Донат-Кейса !§r');
   	break;	
   	case 11:
	$p = $e->getPlayer();
	$name = $p->getName();    	
	$p->sendMessage('§2[crDonateCase] §6 Вам выпал премиум из Донат-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпал премиум из Донат-Кейса ✔!§r');   
	$this->getServer()->dispatchCommand(new ConsoleCommandSender(), 'setgroup ' .$name. ' Premium'); 
   	break; 
   	case 12:
	$p = $e->getPlayer();
	$name = $p->getName();   	
	$p->sendMessage('§2[crDonateCase] §6 Вам ничего не выпало из Донат-Кейса ❌!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' ничего не выпало из Донат-Кейса ❌!§r');
   	break;   	  	 	   	   		   	  	    	  	
   }
}					
}
		public function casemoney(PlayerInteractEvent $e){
					$p = $e->getPlayer();
					$name = $p->getName();
					$b = $e->getBlock();
					$x = $b->getX();
					$y = $b->getY();
					$z = $b->getZ();	
					if(($e->getAction() == PlayerInteractEvent::RIGHT_CLICK_AIR) && ($p->getInventory()->getItemInHand()->getId() == 407 )){
					   $p->getInventory()->removeItem(Item::get(407,0,1));
					   $p->getLevel()->addSound(new AnvilFallSound($p));
  $rand = mt_rand(1,20);
   switch($rand){
   	case 1:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 1337$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку' .$name. ' выпало 1337$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,1337);
   	break;
   	case 2:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 3547$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпало 3547$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,3547);
   	break;   
   	case 3:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 24687$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпало 24687$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,24687);
   	break;   
   	case 4:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 228$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпало 228$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,228);
   	break;   
   	case 5:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпал 1$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпал 1$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,1);
   	break;  
   	case 6:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 10000$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпало 10000$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,10000);
   	break;
   	case 7:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 36745$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпало 36745$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,36745);
   	break;  
   	case 8:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 54213$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпало 54213$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,54213);
   	break; 
   	case 9:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 123$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпало 123$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,123);
   	break;  
   	case 10:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 542$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпало 542$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,542);
   	break;
   	case 11:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 247$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку' .$name. ' выпало 247$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,247);
   	break;
   	case 12:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 6427$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпало 6427$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,6427);
   	break;   
   	case 13:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 24487$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпало 24487$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,24487);
   	break;   
   	case 14:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 228$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпало 228$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,228);
   	break;   
   	case 15:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпал 1$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпал 1$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,1);
   	break;  
   	case 16:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 100000$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпало 100000$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,100000);
   	break;
   	case 17:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 1$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпало 1$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,1);
   	break;  
   	case 18:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 54213$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпало 54213$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,54213);
   	break; 
   	case 19:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 123$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпало 123$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,123);
   	break;  
   	case 20:
	$p->sendMessage('§2[crDonateCase] §6 Вам выпало 5420$ из Денежного-Кейса ✔!§r');
   	$this->getServer()->broadcastMessage('§2[crDonateCase] §6 Игроку ' .$name. ' выпало 5420$ из Денежного-Кейса ✔!§r'); 
   	$this->eco->addMoney($p,5420);
   	break;     	   	 	  	 	
   }  	
}
}
}
?>