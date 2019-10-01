<?php

$config = [
    'available' => [
        [
            'name' => 'cobranca',
            'class' => 'CobrancaConverter',
            'subtypes' => [
                'acordo' => [
                    'defaults' => [
                        'cut_top' => 4,
                        'cut_bottom' => 3,
                        'concat_every' => 2
                    ]
                ],
                'inadinplencia' => [ 
                    'defaults' => [
                        'cut_top' => 6,
                        'cut_bottom' => 0,
                        'concat_every' => 1 // 1 = Disable concatenation
                    ]
                ]
            ]
        ],
        [
            'name' => 'cadastro',
            'class' => 'CadastroConverter',
            'defaults' => [
                'cut_top' => 3,
                'cut_bottom' => 1,
                'concat_every' => 5
            ]

        ]
    ],
    'output_folder' => 'output/',
];