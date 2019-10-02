<?php

$config = [
    // Parser Erp
    'uniondata' => [
        // Converter Type
        'cobranca' => [
            // Parser Mode
            'default' => [ // inadinplencia
                'cut_top' => 6,
                'cut_bottom' => 0,
                'concat_every' => 1 // 1 = Disable concatenation
            ],
            'acordo' => [
                'cut_top' => 4,
                'cut_bottom' => 3,
                'concat_every' => 2
            ]
        ],
        'cadastro' => [
            'default' => [
                'cut_top' => 3,
                'cut_bottom' => 1,
                'concat_every' => 5
            ]
        ]
    ],
    // Parser Erp
    'carsoft' => [
        // Converter Type
        'cobranca' => [
            // Parser Mode
            'default' => [ // inadimplencia
                'cut_top' => 6,
                'cut_bottom' => 0,
                'concat_every' => 1
            ],
        ],
        'cadastro' => [
            'default' => [
                'cut_top' => 3,
                'cut_bottom' => 1,
                'concat_every' => 5
            ]
        ]
    ],
];