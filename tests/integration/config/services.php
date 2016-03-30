<?php

return [
    'engine.schema' => [
        'class' => 'Schale\Schema\Engine',
        'arguments' => [
            '@type_registry.schema',
            '@annotation_reader',
        ],
    ],

    'type_registry.schema' => [
        'class' => 'Schale\Schema\TypeRegistry',
        'arguments' => [
            '@string_primitive.type.schema',
            '@number_primitive.type.schema',
        ],
    ],

    'string_primitive.type.schema' => [
        'class' => 'Schale\Schema\Type\StringPrimitive',
        'arguments' => [],
    ],

    'number_primitive.type.schema' => [
        'class' => 'Schale\Schema\Type\NumberPrimitive',
        'arguments' => [],
    ],
];
