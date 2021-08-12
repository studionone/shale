<?php

declare(strict_types=1);

namespace Shale\Interfaces\Schema;

/**
 * Interface SchemaTypeWithTypedItemsInterface
 *
 * @package Shale\Interfaces\Schema
 */
interface SchemaTypeWithTypedItemsInterface extends SchemaTypeInterface
{
    /**
     * @return SchemaTypeInterface
     */
    public function getItemType(): SchemaTypeInterface;

    /**
     * @param SchemaTypeInterface $newItemType
     * @return void
     */
    public function setItemType(SchemaTypeInterface $newItemType): void;
}
