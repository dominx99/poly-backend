<?php declare(strict_types=1);

namespace App\Map\Presentation;

use App\Map\Infrastructure\MapSize;
use App\Map\Infrastructure\MapGenerator;
use Slim\Http\Response;
use Slim\Http\Request;
use App\System\System;
use App\Map\Application\FindMap;

final class MapController
{
    private $generator;

    /**
     * @var \App\System\System
     */
    private $system;

    public function __construct(MapGenerator $generator, System $system)
    {
        $this->generator = $generator;
        $this->system    = $system;
    }

    public function show(Request $request)
    {
        $route   = $request->getAttribute('route');
        $worldId = $route->getArgument('worldId');

        $map = $this->system->execute(new FindMap($worldId));

        return $map->toResponse();
    }

    public function generate()
    {
        $map = $this->generator->generate(new MapSize(32, 16));

        $fields    = $map->getFields();
        $newFields = [];

        foreach ($fields as $field) {
            $newFields[] = [
                'x'    => $field['position']->getXPos(),
                'y'    => $field['position']->getYPos(),
                'type' => $field['block']->get(),
            ];
        }

        return (new Response())->withJson([
            'fields' => $newFields,
        ]);
    }
}
