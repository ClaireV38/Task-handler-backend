<?php

return [

    /*
     * Le nom du disque sur lequel les fichiers seront stockés.
     * Tu peux le changer via l'environnement (MEDIA_DRIVER=ovh par exemple)
     */
    'disk_name' => env('FILESYSTEM_DISK', 'media'),

    /*
     * Taille maximale autorisée pour les uploads (en octets).
     */
    'max_file_size' => 1024 * 1024 * 20, // 20 MB

    /*
     * Configuration des conversions (si tu gères des images).
     */
    'image_driver' => env('IMAGE_DRIVER', 'gd'),

    /*
     * Empêche la suppression automatique des fichiers quand un média est supprimé
     */
    'preserve_original' => false,
];
