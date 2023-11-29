<?php

namespace App\Message;

final class SkyblockUpdateMessage
{
    public const UPDATE_ITEMS_ALL = 'item_all';
    public const UPDATE_ITEMS_NPC_PRICES_ALL = 'item_npc_prices';
    public const UPDATE_AUCTION_HOUSE = 'auction_house';
    public const UPDATE_ITEM_CATEGORIES = 'item_categories';
    public const UPDATE_COMPOST_VALUES = 'compost_values';

    private string $updateTarget;

    public function __construct(string $updateTarget) {
        $this->updateTarget = $updateTarget;
    }

    public function getUpdateTarget(): string {
        return $this->updateTarget;
    }
}
