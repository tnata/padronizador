<?php

$config = [
    'available' => [
        [
            'name' => 'cobranca',
            'class' => 'CobrancaConverter'
        ],
        [
            'name' => 'cadastro',
            'class' => 'CadastroConverter',
            'subtypes' => [
                'acordo',
                'inadinplencia',
            ]
        ]
    ]
];