<?php

return [
    // Mostra a página "Em construção" ao público (o painel /admin continua funcionando).
    'em_construcao' => (bool) env('PORTAL_EM_CONSTRUCAO', false),

    // E-mail que recebe aviso a cada novo pedido de informação. Vazio = sem aviso.
    'email_pedidos' => env('PORTAL_EMAIL_PEDIDOS'),

    // Prazo de resposta informado ao cidadão (a LAI usa 20 dias, prorrogáveis por mais 10).
    'prazo_resposta_dias' => 20,

    // Exibe o widget VLibras (tradução para Libras do Governo Federal).
    'vlibras' => env('PORTAL_VLIBRAS', true),
];
