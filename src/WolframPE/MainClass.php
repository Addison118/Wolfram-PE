<?php
namespace WolframPE;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class MainClass extends PluginBase implements Listener
{

  public $apiKey;

  public function onEnable()
  {
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $config = new Config($this->getDataFolder() . "config.yml", CONFIG::YAML, array(
        "apiKey" => "API_KEY",
    ));
    $config->save();
    $this->apiKey = $config->get("apiKey");
  }

  public function onCommand(CommandSender $sender, Command $command, $label, array $args)
  {
    if($command == "wa") {
      if($this->apiKey == "API_KEY") {
        $sender->sendMessage("[WA] " . TextFormat::AQUA . "API key has not been set");
      } else {
        $sender->sendMessage("Gathering results for " . $args);
        $engine = new \WolframAlphaEngine($this->apiKey);
        $response = $engine->getResults($args);
        foreach ($response->getPods() as $pod) {
          $sender->sendMessage("[WA] " . TextFormat::AQUA . $pod);
        }
      }
    }
  }

  public function onDisable()
  {
    $this->getConfig()->save();
  }
}
