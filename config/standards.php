<?php

$config = [
    'standards' => [
        'cadastro' => [
            'fields' => [
                'unidade',
                'bloco',
                'fracao',
                'area',
                'abatimento',
                'proprietario_nome',
                'proprietario_telefone',
                'proprietario_celular',
                'proprietario_forma_de_entrega',
                'proprietario_cpf/cnpj',
                'proprietario_rg',
                'proprietario_email',
                'proprietario_endereco',
                'proprietario_complemento',
                'proprietario_cep',
                'proprietario_cidade',
                'proprietario_bairro',
                'proprietario_estado',
                'inquilino_nome',
                'inquilino_telefone',
                'inquilino_celular',
                'inquilino_forma_de_entrega',
                'inquilino_cpf/cnpj',
                'inquilino_rg',
                'inquilino_email',
            ],
            'discard' => [
                'Sistemas Union Data - www.uniondata.com.br',
                'Excellence Administradora de Condomínios Ltda',
                'Relatório de Condôminos Simples',
                'Condomínio:',
            ],
            'discard_equals' => [
                //
            ],
        ],
        'cobranca' => [
            'fields' => [
                'unidade',
                'bloco',
                'vencimento',
                'nosso_numero',
                'debitar_taxa_bancária',
                'data_de_competência',
                'atualização_monetária',
                'taxa_de_juros_(%)',
                'taxa_de_multa_(%)',
                'taxa_de_desconto_(%)',
                'cobrança_extraordinária',
                'data_crédito',
                'forma_de_cobrança',
                'data_liquidação',
                'valor_pago',
            ],
            'discard' => [
                'Processo:',
                'Gerado o Acordo',
                '"Vencto","","Recibo"',
                'Acordo Nro.',
                'Conta Bancária',
                'Relação de Recibos (Objeto do Acordo)',
                'Honorários:',
                'Adicional:',
                'TOTAL:',
            ],
            'discard_equals' => [
                '"","","","","","","","","","","","",""',
            ],
            'end_file_string' => 'Total do Condomínio',
            'array_fields' => [
                'RECEITA_APROPRIACAO' => [
                    'conta_categoria',
                    'complemento',
                    'valor',
                ]
            ]
        ]
    ]
];
