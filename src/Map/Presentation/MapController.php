<?php declare(strict_types=1);

namespace App\Map\Presentation;

use App\Map\Infrastructure\MapSize;

final class MapController
{
    public function generate()
    {
        $map = $this->generator->generate(new MapSize(8, 8));

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
