<?php

return [
    'admin' => [
        'excluded_bundles' => [
            Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle::class,
        ],
    ],
    'website' => [
        'excluded_bundles' => [
            Symfony\Bundle\SecurityBundle\SecurityBundle::class,
            Sulu\Bundle\AdminBundle\SuluAdminBundle::class,
            Sulu\Bundle\CollaborationBundle\SuluCollaborationBundle::class,
            Sulu\Bundle\PreviewBundle\SuluPreviewBundle::class,
        ],
    ],
];
