<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Lignes de langage de validation
    |--------------------------------------------------------------------------
    |
    | Les lignes suivantes contiennent les messages d'erreur par défaut utilisés par
    | la classe de validation. Certaines de ces règles ont plusieurs versions,
    | comme les règles de taille. N'hésitez pas à modifier ces messages ici.
    |
    */

    'accepted'             => 'Le champ :attribute doit être accepté.',
    'accepted_if'          => 'Le champ :attribute doit être accepté quand :other est :value.',
    'active_url'           => 'Le champ :attribute n\'est pas une URL valide.',
    'after'                => 'Le champ :attribute doit être une date postérieure au :date.',
    'after_or_equal'       => 'Le champ :attribute doit être une date postérieure ou égale au :date.',
    'alpha'                => 'Le champ :attribute ne peut contenir que des lettres.',
    'alpha_dash'           => 'Le champ :attribute ne peut contenir que des lettres, chiffres, tirets et underscores.',
    'alpha_num'            => 'Le champ :attribute ne peut contenir que des lettres et des chiffres.',
    'array'                => 'Le champ :attribute doit être un tableau.',
    'ascii'                => 'Le champ :attribute ne doit contenir que des caractères ASCII.',
    'before'               => 'Le champ :attribute doit être une date antérieure au :date.',
    'before_or_equal'      => 'Le champ :attribute doit être une date antérieure ou égale au :date.',
    'between'              => [
        'numeric' => 'La valeur de :attribute doit être comprise entre :min et :max.',
        'file'    => 'Le fichier :attribute doit avoir une taille entre :min et :max kilo-octets.',
        'string'  => 'Le champ :attribute doit contenir entre :min et :max caractères.',
        'array'   => 'Le tableau :attribute doit contenir entre :min et :max éléments.',
    ],
    'boolean'              => 'Le champ :attribute doit être vrai ou faux.',
    'confirmed'            => 'La confirmation du champ :attribute ne correspond pas.',
    'current_password'     => 'Le mot de passe est incorrect.',
    'date'                 => 'Le champ :attribute n\'est pas une date valide.',
    'date_equals'          => 'Le champ :attribute doit être une date égale à :date.',
    'date_format'          => 'Le champ :attribute ne correspond pas au format :format.',
    'decimal'              => 'Le champ :attribute doit contenir :decimal chiffres décimaux.',
    'declined'             => 'Le champ :attribute doit être refusé.',
    'declined_if'          => 'Le champ :attribute doit être refusé lorsque :other est :value.',
    'different'            => 'Les champs :attribute et :other doivent être différents.',
    'digits'               => 'Le champ :attribute doit contenir :digits chiffres.',
    'digits_between'       => 'Le champ :attribute doit contenir entre :min et :max chiffres.',
    'dimensions'           => 'Le champ :attribute a des dimensions d\'image invalides.',
    'distinct'             => 'Le champ :attribute contient une valeur en double.',
    'doesnt_end_with'      => 'Le champ :attribute ne doit pas se terminer par une des valeurs suivantes : :values.',
    'doesnt_start_with'    => 'Le champ :attribute ne doit pas commencer par une des valeurs suivantes : :values.',
    'email'                => 'Le champ :attribute doit être une adresse e-mail valide.',
    'ends_with'            => 'Le champ :attribute doit se terminer par une des valeurs suivantes : :values.',
    'enum'                 => 'Le champ :attribute sélectionné est invalide.',
    'exists'               => 'Le champ :attribute sélectionné est invalide.',
    'file'                 => 'Le champ :attribute doit être un fichier.',
    'filled'               => 'Le champ :attribute doit avoir une valeur.',
    'gt'                   => [
        'numeric' => 'La valeur de :attribute doit être supérieure à :value.',
        'file'    => 'Le fichier :attribute doit être plus grand que :value kilo-octets.',
        'string'  => 'Le champ :attribute doit contenir plus de :value caractères.',
        'array'   => 'Le tableau :attribute doit contenir plus de :value éléments.',
    ],
    'gte'                  => [
        'numeric' => 'La valeur de :attribute doit être supérieure ou égale à :value.',
        'file'    => 'Le fichier :attribute doit être supérieur ou égal à :value kilo-octets.',
        'string'  => 'Le champ :attribute doit contenir au moins :value caractères.',
        'array'   => 'Le tableau :attribute doit contenir :value éléments ou plus.',
    ],
    'image'                => 'Le champ :attribute doit être une image.',
    'in'                   => 'Le champ :attribute sélectionné est invalide.',
    'in_array'             => 'Le champ :attribute doit exister dans :other.',
    'integer'              => 'Le champ :attribute doit être un nombre entier.',
    'ip'                   => 'Le champ :attribute doit être une adresse IP valide.',
    'ipv4'                 => 'Le champ :attribute doit être une adresse IPv4 valide.',
    'ipv6'                 => 'Le champ :attribute doit être une adresse IPv6 valide.',
    'json'                 => 'Le champ :attribute doit être une chaîne JSON valide.',
    'lowercase'            => 'Le champ :attribute doit être en minuscules.',
    'lt'                   => [
        'numeric' => 'La valeur de :attribute doit être inférieure à :value.',
        'file'    => 'Le fichier :attribute doit être plus petit que :value kilo-octets.',
        'string'  => 'Le champ :attribute doit contenir moins de :value caractères.',
        'array'   => 'Le tableau :attribute doit contenir moins de :value éléments.',
    ],
    'lte'                  => [
        'numeric' => 'La valeur de :attribute doit être inférieure ou égale à :value.',
        'file'    => 'Le fichier :attribute doit être inférieur ou égal à :value kilo-octets.',
        'string'  => 'Le champ :attribute doit contenir au plus :value caractères.',
        'array'   => 'Le tableau :attribute ne doit pas contenir plus de :value éléments.',
    ],
    'mac_address'          => 'Le champ :attribute doit être une adresse MAC valide.',
    'max'                  => [
        'numeric' => 'La valeur de :attribute ne doit pas dépasser :max.',
        'file'    => 'Le fichier :attribute ne doit pas dépasser :max kilo-octets.',
        'string'  => 'Le champ :attribute ne doit pas contenir plus de :max caractères.',
        'array'   => 'Le tableau :attribute ne doit pas contenir plus de :max éléments.',
    ],
    'max_digits'           => 'Le champ :attribute ne doit pas avoir plus de :max chiffres.',
    'mimes'                => 'Le champ :attribute doit être un fichier de type : :values.',
    'mimetypes'            => 'Le champ :attribute doit être un fichier de type : :values.',
    'min'                  => [
        'numeric' => 'La valeur de :attribute doit être au moins de :min.',
        'file'    => 'Le fichier :attribute doit faire au moins :min kilo-octets.',
        'string'  => 'Le champ :attribute doit contenir au moins :min caractères.',
        'array'   => 'Le tableau :attribute doit contenir au moins :min éléments.',
    ],
    'min_digits'           => 'Le champ :attribute doit avoir au moins :min chiffres.',
    'multiple_of'          => 'Le champ :attribute doit être un multiple de :value.',
    'not_in'               => 'Le champ :attribute sélectionné est invalide.',
    'not_regex'            => 'Le format du champ :attribute est invalide.',
    'numeric'              => 'Le champ :attribute doit être un nombre.',
    'password'             => [
        'letters'       => 'Le champ :attribute doit contenir au moins une lettre.',
        'mixed'         => 'Le champ :attribute doit contenir au moins une majuscule et une minuscule.',
        'numbers'       => 'Le champ :attribute doit contenir au moins un chiffre.',
        'symbols'       => 'Le champ :attribute doit contenir au moins un symbole.',
        'uncompromised' => 'Le mot de passe indiqué a été compromis. Veuillez en choisir un autre.',
    ],
    'present'              => 'Le champ :attribute doit être présent.',
    'prohibited'           => 'Le champ :attribute est interdit.',
    'prohibited_if'        => 'Le champ :attribute est interdit quand :other vaut :value.',
    'prohibited_unless'    => 'Le champ :attribute est interdit sauf si :other est dans :values.',
    'prohibits'            => 'Le champ :attribute interdit la présence de :other.',
    'regex'                => 'Le format du champ :attribute est invalide.',
    'required'             => 'Le champ :attribute est obligatoire.',
    'required_array_keys'  => 'Le champ :attribute doit contenir des entrées pour :values.',
    'required_if'          => 'Le champ :attribute est obligatoire quand :other vaut :value.',
    'required_if_accepted' => 'Le champ :attribute est obligatoire quand :other est accepté.',
    'required_unless'      => 'Le champ :attribute est obligatoire sauf si :other est dans :values.',
    'required_with'        => 'Le champ :attribute est obligatoire lorsque :values est présent.',
    'required_with_all'    => 'Le champ :attribute est obligatoire lorsque :values sont présents.',
    'required_without'     => 'Le champ :attribute est obligatoire lorsque :values n\'est pas présent.',
    'required_without_all' => 'Le champ :attribute est obligatoire lorsque aucun de :values n\'est présent.',
    'same'                 => 'Les champs :attribute et :other doivent correspondre.',
    'size'                 => [
        'numeric' => 'La valeur de :attribute doit être :size.',
        'file'    => 'Le fichier :attribute doit avoir une taille de :size kilo-octets.',
        'string'  => 'Le champ :attribute doit contenir :size caractères.',
        'array'   => 'Le tableau :attribute doit contenir :size éléments.',
    ],
    'starts_with'          => 'Le champ :attribute doit commencer par une des valeurs suivantes : :values.',
    'string'               => 'Le champ :attribute doit être une chaîne de caractères.',
    'timezone'             => 'Le champ :attribute doit être un fuseau horaire valide.',
    'unique'               => 'La valeur du champ :attribute est déjà utilisée.',
    'uploaded'             => 'Le fichier :attribute n\'a pas pu être téléversé.',
    'uppercase'            => 'Le champ :attribute doit être en majuscules.',
    'url'                  => 'Le champ :attribute doit être une URL valide.',
    'ulid'                 => 'Le champ :attribute doit être un ULID valide.',
    'uuid'                 => 'Le champ :attribute doit être un UUID valide.',

    /*
    |--------------------------------------------------------------------------
    | Lignes de langage personnalisées pour la validation
    |--------------------------------------------------------------------------
    |
    | Ici, vous pouvez spécifier des messages de validation personnalisés pour les attributs en utilisant
    | la convention "attribute.rule" pour nommer les lignes. Cela permet de
    | spécifier rapidement un message de langage personnalisé spécifique à une règle d'attribut donnée.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'message personnalisé',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Attributs de validation personnalisés
    |--------------------------------------------------------------------------
    |
    | Les lignes suivantes sont utilisées pour remplacer les attributs par défaut
    | par quelque chose de plus convivial comme "Adresse e-mail" au lieu de "email".
    |
    */

    'attributes' => [],
];
