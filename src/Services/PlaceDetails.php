<?php

namespace kalanis\google_maps\Services;


use kalanis\google_maps\ServiceException;
use Psr\Http\Message\RequestInterface;


/**
 * Place Details service
 *
 * @see     https://developers.google.com/maps/documentation/places/web-service/details
 */
class PlaceDetails extends AbstractService
{
    /**
     * @var string[]
     */
    protected array $allowedFields = [
        // basic
        'address_components', 'adr_address', 'business_status', 'formatted_address', 'geometry', 'icon',
        'icon_mask_base_uri', 'icon_background_color', 'name', 'photo', 'place_id', 'plus_code', 'type',
        'url', 'utc_offset', 'vicinity', 'wheelchair_accessible_entrance',
        // contact
        'current_opening_hours', 'formatted_phone_number', 'international_phone_number', 'opening_hours',
        'secondary_opening_hours', 'website',
        // atmosphere
        'curbside_pickup', 'delivery', 'dine_in', 'editorial_summary', 'price_level', 'rating', 'reservable',
        'reviews', 'serves_beer', 'serves_breakfast', 'serves_brunch', 'serves_dinner', 'serves_lunch',
        'serves_vegetarian_food', 'serves_wine', 'takeout', 'user_ratings_total',
    ];

    /**
     * @var string[]
     */
    protected array $allowedSort = [
        'most_relevant', 'newest',
    ];

    /**
     * Place lookup
     *
     * @param string $placeId
     * @param string[] $fields which fields you want to get
     * @param string|null $region
     * @param bool $translateReviews
     * @param string|null $sortReviews
     * @param array<string, string|int|float> $params Query parameters
     * @throws ServiceException
     * @return RequestInterface
     */
    public function placeDetails(
        string  $placeId,
        array   $fields = [],
        ?string $region = null,
        bool    $translateReviews = true,
        ?string $sortReviews = null,
        array   $params = []
    ): RequestInterface
    {
        if (empty($placeId)) {
            throw new ServiceException('You must set where to look!');
        }

        // Main wanted id
        $params['place_id'] = $placeId;

        if (!empty($fields)) {
            $params['fields'] = implode(',', array_intersect($this->allowedFields, $fields));
        }

        if (!empty($region)) {
            $params['region'] = strtolower(substr($region, 0, 2));
        }

        if (!$translateReviews) {
            $params['reviews_no_translations'] = 'true';
        }

        if (!empty($sortReviews) && in_array(strtolower($sortReviews), $this->allowedSort)) {
            $params['reviews_sort'] = strtolower($sortReviews);
        }

        return $this->getWithDefaults(
            static::API_HOST . '/maps/api/place/details/json',
            $this->queryParamsLang($params),
        );
    }
}
