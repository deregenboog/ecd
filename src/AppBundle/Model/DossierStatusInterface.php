<?php

namespace AppBundle\Model;

interface DossierStatusInterface
{
    public function getEntityClass(): string;

    public function getOpenClass(): string;

    public function getClosedClass(): string;

    public function getDatum(): ?\DateTime;

    public function setDatum(?\DateTime $datum): void;

    public function getEntity();

    public function setEntity($entity): void;

    /**
     * @return void Maps the specific entity to the main 'entity' and the classes. (ie Slaper to Entity, openClass and closedClass etc.)
     */
    public function mapClasses(): void;

    public function isAangemeld(): bool;

    public function isAfgesloten(): bool;

    public function isOpen(): bool;

    public function isClosed(): bool;



}
