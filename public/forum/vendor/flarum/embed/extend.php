<?php
/*
 * This file is part of Flarum.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */
use Flarum\Extend;
use Flarum\Frontend\Document;
use Psr\Http\Message\ServerRequestInterface as Request;

return [
    (new Extend\Frontend('forum'))
        ->route(
            '/embed/{id:\d+(?:-[^/]*)?}[/{near:[^/]*}]',
            'embed.discussion',
            function (Document $document, Request $request) {
                // Add the discussion content to the document
                resolve(Flarum\Forum\Content\Discussion::class)($document, $request);
                resolve(Flarum\Frontend\Content\Assets::class)->forFrontend('embed')($document, $request);

                // Remove header/navigation elements
                $document->head[] = '<style>
                    .App-header, .DiscussionPage-nav { display: none !important; }
                    .App-content { padding-top: 0 !important; }
                    .Container--narrow { max-width: none !important; padding: 0 !important; }
                </style>';
            }
        ),
    (new Extend\Frontend('embed'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less')
];
