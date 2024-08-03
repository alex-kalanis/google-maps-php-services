<?php

namespace kalanis\google_maps\Services;


use kalanis\google_maps\ServiceException;
use Psr\Http\Message\RequestInterface;


/**
 * Roads Service
 *
 * @see https://developers.google.com/maps/documentation/roads
 */
class Roads extends AbstractService
{
    /**
     * Roads lookup
     * @param array<int, float[]>|string|null $path
     * @param array<string, string|int|float> $params Query parameters
     * @throws ServiceException
     * @return RequestInterface
     */
    public function snapToRoads(array|string|null $path = null, array $params = []): RequestInterface
    {
        if (is_array($path)) {
            $positions = [];
            foreach ($path as $key => $eachPathArray) {
                $positions[] = implode(',', $eachPathArray);
            }
            $params['path'] = implode('|', $positions);
        } elseif (is_string($path)) {
            $params['path'] = $path;
        } else {
            throw new ServiceException('Unknown path format. Pass array of arrays of floats or the string itself.');
        }

        if (isset($params['interpolate'])) {
            if ($params['interpolate']) {
                $params['interpolate'] = 'true';
            } else {
                unset($params['interpolate']);
            }
        }

        return $this->getWithDefaults(
            'https://roads.googleapis.com/v1/snapToRoads',
            $this->queryParams($params)
        );
    }
}
