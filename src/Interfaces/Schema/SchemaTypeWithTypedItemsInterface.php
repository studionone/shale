<?php declare(strict_types=1);
namespace Schale\Interfaces\Schema;

interface SchemaTypeWithTypedItemsInterface extends SchemaTypeInterface
{
    public function getItemType(): SchemaTypeInterface;

    public function setItemType(SchemaTypeInterface $newItemType);
}
