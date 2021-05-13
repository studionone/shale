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
     * @return mixed
     */
    public function setItemType(SchemaTypeInterface $newItemType);
}
