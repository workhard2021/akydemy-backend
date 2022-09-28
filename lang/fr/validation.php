<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ' :attribute doit être accepté.',
    'accepted_if' => ' :attribute doit être accepté lorsque :other vaut :value.',
    'active_url' => " :attribute n'est pas une URL valide.",
    'after' => ' :attribute doit être une date après:date.',
    'after_or_equal' => ' :attribute doit être une date postérieure ou égale à :date.',
    'alpha' => ' :attribute ne doit contenir que des lettres.',
    'alpha_dash' => ' :attribute ne doit contenir que des lettres, des chiffres, des tirets et des traits de soulignement.',
    'alpha_num' => ' :attribute ne doit contenir que des lettres et des chiffres.',
    'array' => ' :attribute doit être un tableau.',
    'before' => ' :attribute doit être une date avant :date.',
    'before_or_equal' => ' :attribute doit être une date antérieure ou égale à :date.',
    'between' => [
        'array' => ' :attribute doit être entre :min and :max items.',
        'file' => ' :attribute doit être entre :min and :max kilobytes.',
        'numeric' => ' :attribute doit être entre :min and :max.',
        'string' => ' :attribute doit être entre :min and :max characters.',
    ],
    'boolean' => ' :attribute le champ doit être vrai ou faux.',
    'confirmed' => ' :attribute la confirmation ne correspond pas.',
    'current_password' => 'Le mot de passe est incorrect.',
    'date' => " :attribute la date n'est pas valide.",
    'date_equals' => ' :attribute doit être une date égale à :date.',
    'date_format' => ' :attribute ne correspond pas au format :format.',
    'declined' => 'The :attribute doit être refusé.',
    'declined_if' => 'The :attribute must be declined when :other is :value.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'doesnt_start_with' => 'The :attribute may not start with one of the following: :values.',
    'email' => ' :attribute doit être une adresse e-mail valide.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'enum' => 'Element selectioné :attribute est invalide.',
    'exists' => 'Element selectioné :attribute est invalide.',
    'file' => ' :attribute doit être un fichier.',
    'filled' => ' :attribute le champ doit avoir une valeur.',
    'gt' => [
        'array' => 'The :attribute must have more than :value items.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'numeric' => 'The :attribute must be greater than :value.',
        'string' => 'The :attribute must be greater than :value characters.',
    ],
    'gte' => [
        'array' => 'The :attribute must have :value items or more.',
        'file' => 'The :attribute must be greater than or equal to :value kilobytes.',
        'numeric' => 'The :attribute must be greater than or equal to :value.',
        'string' => 'The :attribute must be greater than or equal to :value characters.',
    ],
    'image' => 'The :attribute doit être une image.',
    'in' => ' :attribute est invalide.',
    'in_array' => " :attribute n'existe pas dans :other.",
    'integer' => 'The :attribute doit être un entier.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'array' => 'The :attribute must have less than :value items.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'numeric' => 'The :attribute must be less than :value.',
        'string' => 'The :attribute must be less than :value characters.',
    ],
    'lte' => [
        'array' => 'The :attribute must not have more than :value items.',
        'file' => 'The :attribute must be less than or equal to :value kilobytes.',
        'numeric' => 'The :attribute must be less than or equal to :value.',
        'string' => 'The :attribute must be less than or equal to :value characters.',
    ],
    'mac_address' => 'The :attribute must be a valid MAC address.',
    'max' => [
        'array' => ' :attribute ne doit pas avoir plus de :max élements.',
        'file' => ' :attribute ne doit pas être supérieur à :max kilobytes.',
        'numeric' => ' :attribute ne doit pas être supérieur à :max.',
        'string' => ' :attribute ne doit pas être supérieur à :max characters.',
    ],
    'mimes' => ' :attribute doit être un fichier de type: :values.',
    'mimetypes' => ' :attribute doit être un fichier de type: :values.',
    'min' => [
        'array' => ' :attribute doit avoir au moins :min élements.',
        'file' => ' :attribute doit être au moins :min kilobytes.',
        'numeric' => ' :attribute doit être au moins :min.',
        'string' => ' :attribute doit être au moins :min caractères.',
    ],
    'multiple_of' => ' :attribute doit être un multiple de :value.',
    'not_in' => 'Element selectioné :attribute est invalide.',
    'not_regex' => ' :attribute format est invalide.',
    'numeric' => ' :attribute doit être un nombre.',
    'password' => [
        'letters' => 'The :attribute must contain at least one letter.',
        'mixed' => 'The :attribute must contain at least one uppercase and one lowercase letter.',
        'numbers' => 'The :attribute must contain at least one number.',
        'symbols' => 'The :attribute must contain at least one symbol.',
        'uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'present' => 'The :attribute field must be present.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_array_keys' => 'The :attribute field must contain entries for: :values.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'array' => 'The :attribute doit contenir :size élements.',
        'file' => ' :attribute doit être :size kilobytes.',
        'numeric' => ' :attribute doit être :size.',
        'string' => ' :attribute doit être :size caractères.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => ' :attribute doit être une chaîne de caractère.',
    'timezone' => ' :attribute doit être un fuseau horaire valide.',
    'unique' => ' :attribute a déjà été pris.',
    'uploaded' => ' :attribute échec du téléchargement.',
    'url' => ' :attribute doit être une URL valide.',
    'uuid' => ' :attribute doit être un UUID valide.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
