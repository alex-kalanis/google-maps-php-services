<?php

namespace kalanis\google_maps\Remote;


use Psr\Http\Message\ResponseInterface;


/**
 * Process response to usable format
 */
class Response
{
    /**
     * @param ResponseInterface $response
     * @param bool $wantInnerResult
     * @return array<string, mixed>|string
     */
    public function process(ResponseInterface $response, bool $wantInnerResult): array|string
    {
        $message = $response->getBody()->getContents();
        $result = @json_decode($message, true);

        // Error Handler
        if (empty($result) && !empty($message)) {
            // Error message directly in content
            return $message;

        } elseif (200 !== $response->getStatusCode()) {
            // status code passed something
            return $result;

        }

        $result = (array) $result;
        if (isset($result['error_message'])) {
            // Error message Checker (200 situation from Google Maps API)
            return $result;
        }

        // `results` parsing from Google Maps API, while pass parsing on error
        return isset($result['results']) && $wantInnerResult ? $result['results'] : $result;
    }
}
