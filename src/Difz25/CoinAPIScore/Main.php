<?php

namespace Difz25\CoinAPIScore;


use {
    Difz25\CoinAPIScore\Listener\TagResolveListener,
    pocketmine\plugin\PluginBase,
    Ifera\ScoreHud\scoreboard\ScoreTag,
    pocketmine\event\Listener,
    pocketmine\scheduler\ClosureTask,
    Ifera\ScoreHud\event\PlayerTagsUpdateEvent,
    Ifera\ScoreHud\ScoreHud,
    onebone\coinapi\CoinAPI,
    pocketmine\plugin\Plugin
};

/**
 * @property Plugin|null $eco
 */
class Main extends PluginBase implements Listener
{


    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->eco = CoinAPI::getInstance();
        if ($this->eco == null) {
            $this->getLogger()->alert("CoinAPI not found!");
        }
        if (class_exists(ScoreHud::class)) {
            $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(
                closure: function (): void {
                    foreach ($this->getServer()->getOnlinePlayers() as $player) {
                        if (!$player->isOnline()) {
                            continue;
                        }

                        (new PlayerTagsUpdateEvent($player, [
                            new ScoreTag("coins.count", $this->Format($this->eco->myCoin($player)))
                        ]))->call();
                    }
                }
            ), 1);
            $this->getServer()->getPluginManager()->registerEvents(new TagResolveListener($this), $this);
        }
    }

        public function Format($num): string
        {
            if(!is_numeric($num)) return  'IDR 0';
        $format = number_format((int) $num, 0, ',', '.');
        return 'IDR' . $format;
    }
}