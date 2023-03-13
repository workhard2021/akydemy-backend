<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Messages Subscription 
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during Subscription for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */
    'title_admin'=> "Nouveau abonnement au module :title",
    'message_admin' => "Bonjour :name, un utilisateur demande un abonnement, Veuillez vous connecter pour traiter sa demande. Merci!",
    'type' => ':type',
    'title'=> "Processus d'abonnement au module :title",
    'message' => 'Bonjour, votre demande d’abonnement est en cours de Traitement. L’équipe '.config('app.name').' vous contactera très rapidement pour finaliser le processus',

    'valide-title' => 'Activation du module',
    'studiant-valide-message' => 'Bonjour, Nous vous informons de la confirmation de votre abonnement au Module :title . L’équipe '.config('app.name').' vous remercie de votre Confiance. Avec '.config('app.name').' Apprenez en toute simplicité et perfectionnez votre niveau.',
    'teacher-valide-message' => 'Bonjour Cher Formateur, Nous vous informons de l’abonnement du compte :studiant_mail au Module :title . L’équipe '.config('app.name').' vous remercie de votre professionnalisme',

    'invalide-title' => 'Désactivation du module',
    'studiant-invalide-message' => 'Bonjour, Nous vous informons de votre désabonnement au Module :title. Veuillez contacter la plateforme pour plus de détails',
    'teacher-invalide-message' => 'Bonjour Cher Formateur, Nous vous informons du désabonnement du compte :studiant_mail au Module :title. Veuillez contacter la plateforme pour plus de détails.',
];
