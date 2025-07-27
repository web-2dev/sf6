<?php

namespace App\Doctrine;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\PreUpdate;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[AsEntityListener(event: Events::prePersist, entity: Livre::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Livre::class)]
class LivreEntityListener
{
    public function prePersist(Livre $l): void
    {
        $this->setUrlValue($l);
    }

    public function preUpdate(Livre $l): void
    {
        $this->setUrlValue($l);
    }

    public function setUrlValue(Livre $l): void
    {
        $slugger = new AsciiSlugger();
        if (!$l->getUrl()) {
            $l->setUrl( $slugger->slug( $l->getTitre() )->lower() );
        }
    }
}
