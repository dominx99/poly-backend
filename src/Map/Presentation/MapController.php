<?php declare(strict_types=1);

namespace App\Map\Presentation;

use App\Map\Infrastructure\MapSize;
use App\Map\Infrastructure\MapGenerator;
use Slim\Http\Response;

final class MapController
{
    private $generator;

    public function __construct(MapGenerator $generator)
    {
        $this->generator = $generator;
    }

    public function generate()
    {
        $map = $this->generator->generate(new MapSize(32, 16));

        $fields = $map->getFields();
        $newFields = [];

        foreach ($fields as $field) {
            $newFields[] = [
                'x' => $field['position']->getXPos(),
                'y' => $field['position']->getYPos(),
                'type' => $field['block']->get(),
            ];
        }

        return (new Response())->withJson([
            'fields' => $newFields,
        ]);
    }
}
