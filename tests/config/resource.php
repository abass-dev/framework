<?php
/**
 * Fichier de configuration de la classe réssource
 */
return [
    /**
     * Store location
     */
    'disk' =>[
        'mount' => 'storage',
        'path' => [
            'storage' => __DIR__.'/../data/storage',
            'public' => __DIR__.'/../data/public',
        ]
    ],

    /**
     * Repertoire de log
     */
    'log' => __DIR__.'/../data/logs',

    /**
     * Repertoure de cache
     */
    'cache' => __DIR__.'/../data/cache',

    /**
     * FTP configuration
     */
    'ftp' => [
        'hostname' => 'demo.wftpserver.com',
        'password' => 'demo-user',
        'username' => 'demo-user',
        'port'     => 21,
        'root' => '', // Le dossier de base du serveur
        'tls' => false, // A `true` pour activer une connection sécurisé.
        'timeout' => 90 // Temps d'attente de connection
    ],

    /**
     * S3 configuration
     */
    's3' => [
        'credentials' => [
            'key'    => '',
            'secret' => '',
        ],
        'bucket' => '',
        'region' => '',
        'version' => 'latest'
    ]
];
