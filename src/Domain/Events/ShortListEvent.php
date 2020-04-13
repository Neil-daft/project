<?php
declare(strict_types=1);

namespace App\Domain\Events;

use App\Entity\ShortList;
use Symfony\Contracts\EventDispatcher\Event;

class ShortListEvent extends Event
{
    const NAME = 'shortList.accept';

    /** @var \App\Entity\ShortList  */
    private $shortList;

    public function __construct(ShortList $shortList)
    {
        $this->shortList = $shortList;
    }

    public function getShortList(): ShortList
    {
        return $this->shortList;
    }
}
