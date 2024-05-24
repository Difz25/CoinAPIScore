<?php
declare(strict_types = 1);

namespace Difz25\CoinAPIScore\Listener;

use Ifera\ScoreHud\event\TagsResolveEvent;
use Difz25\CoinApiScore\Main;
use pocketmine\event\Listener;
use function count;
use function explode;
use function strval;

/**
 * @property $eco
 * @property Main $plugin
 */
class TagResolveListener implements Listener{

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function onTagResolve(TagsResolveEvent $event): void {
        $tag = $event->getTag();
        $player = $event->getPlayer();
        $tags = explode('.', $tag->getName(), 2);
        $value = "";

        if($tags[0] !== 'coins' || count($tags) < 2){
            return;
        }

        switch($tags[1]){
            case "coins":
                $value = (string) "" . $this->plugin->Format($this->eco->myCoin($player));
                break;
        }

        $tag->setValue(value: strval($value));
    }
}