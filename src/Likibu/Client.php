<?php

namespace Likibu;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    /**
     * Default configuration
     * @var array
     */
    private $defaults = [
        'host' => 'http://api.likibu.com',
    ];
    
    /**
     * Configuration
     * @var array
     */
    private $conf = [];
    
    /**
     * Your API key
     * @var string
     */
    private $key = false;
    
    /**
     *  
     * @param string $key
     * @param array $conf
     */
    public function __construct($key, $conf = []) 
    {
        $this->key = $key;
        $this->conf = array_merge($this->defaults, $conf);
    }
    
    /**
     * Pings API
     * 
     * @return string
     */
    public function ping()
    {
        return $this->getResponse( sprintf('/ping/?%s', $this->buildParameters()));
    }
    
    /**
     * Search likibu's rooms. 
     * 
     * @deprecated since version 2.0
     * @param array $params 
     *      - where (mandatory - string) : where in the world should it search for rooms?
     *      - culture (mandatory - string) : give me the results in which language? (supported : fr, en, es, it, de)
     *      - currency (mandatory - string) : give me the prices in which currency? (3 letters ISO 4217 currency codes : EUR, USD, GBP, ...)
     *      - checkin (DEPRECATED - optional - string) : checkin date, yyyy-mm-dd format
     *      - checkout (DEPRECATED - optional - string) : checkout date, yyyy-mm-dd format
     *      - guests (optional - integer) : accommodates how many guests?
     *      - price_min (optional - integer) : minimum price
     *      - price_max (optional - integer) : maximum price
     *      - privacy_type (optional - string) : entire_apartment, private_room, shared_room (comma-separated if you want to filter on multiple values : ex = privacy_type => "shared_room,private_room" to get all offers that are not entire_apartment)
     *      - type (optional - string) : apartment, house, villa, lodge, boat, castle, bnb, caravan, hotel, room (comma-separated if you want to filter on multiple values)
     *      - amenities (optional - string) : ac, accessible, balcony, breakfast, dishwasher, elevator, events, family, fireplace, gym, jacuzzi, kitchen, net, parking, pets, pool, smoke, tv, washer_dryer (comma-separated if you want to filter on multiple values)
     *      - page (optional - integer - default: 1) 
     *      - per_page (optional - integer - default: 24) 
     * 
     * @return array
     *      - totalResults (integer)
     *      - page (integer)
     *      - totalPages (integer)
     *      - priceMin (integer)
     *      - priceMax (integer)
     *      - facets : { filter_type_1: { filter_value_1: resultsCount, filter_value_2: resultsCount, ... }, filter_type_2 : { filter_value_1: resultsCount, ... }, ... }
     *          => { privacy_type: { entire_apartment: 512, private_room: 42, shared_room: 2}, amenities: { tv: 400, net: 400, jacuzzi: 12, ...}, ... }
     *      - results : @todo
     */
    public function search($params = [])
    {
        if (isset($params['checkin']) || isset($params['checkout'])) {
            trigger_error('Likibu\\Client::search is deprecated when searching rooms with checkin and checkout dates. Please use real-time search instead, with Likibu\\Client::initSearch, Likibu\\Client::pollSearch, Likibu\\Client::getSearch', E_USER_DEPRECATED);
        }
        
        return $this->getResponse(
            sprintf('/rooms/?%s', $this->buildParameters($params))
        );
    }
    
    /**
     * Initializes a room search.
     * 
     * @param array $params 
     *      - where (mandatory - string) : where in the world should it search for rooms?
     *      - culture (mandatory - string) : give me the results in which language? (supported : fr, en, es, it, de)
     *      - currency (mandatory - string) : give me the prices in which currency? (3 letters ISO 4217 currency codes : EUR, USD, GBP, ...)
     *      - checkin (optional - string) : checkin date, yyyy-mm-dd format
     *      - checkout (optional - string) : checkout date, yyyy-mm-dd format
     *      - guests (optional - integer) : accommodates how many guests?
     * 
     * @return array
     *      - search_id (string)
     *      - bbox (array)
     *      - where (string)
     *      - checkin (string)
     *      - checkout (string)
     *      - country (string)
     *      - guests (string)
     *      - partners (string)
     *      - search_status_url (string)
     *      - search_results_url (string)
     */
    public function initSearch($params = [])
    {
        return $this->getResponse(
            sprintf('/search/?%s', $this->buildParameters([])), 
            'post', 
            $this->buildParameters($params, true)
        );
    }
    
    /**
     * Checks the status of the search. 
     * Searches on small destinations with only a few accommodations can only take a few milliseconds. 
     * Searching on big cities or entire regions can take 15 seconds.
     * You need to periodically call this method until the search is complete. Every 200ms for example.
     * 
     * @param type $search_id
     * @return array
     *      - search_id (string)
     *      - bbox (array)
     *      - where (string)
     *      - checkin (string)
     *      - checkout (string)
     *      - country_code (string)
     *      - status (array) ['is_complete' => (bool), 'partners' => []]
     */
    public function pollSearch($search_id)
    {
        return $this->getResponse(
            sprintf('/search/%s/status?%s', $search_id, $this->buildParameters([]))
        );
    }
    
    /**
     * Fetches search's results
     * 
     * @param string $search_id
     * @param array $filters
     *      - sort (optional - string) : sort results by : [reco, price_asc, price_desc] (default : reco)
     *      - price_min (optional - integer) : minimum price
     *      - price_max (optional - integer) : maximum price
     *      - privacy_type (optional - string) : entire_apartment, private_room, shared_room (comma-separated if you want to filter on multiple values : ex = privacy_type => "shared_room,private_room" to get all offers that are not entire_apartment)
     *      - type (optional - string) : apartment, house, villa, lodge, boat, castle, bnb, caravan, hotel, room (comma-separated if you want to filter on multiple values)
     *      - amenities (optional - string) : ac, accessible, balcony, breakfast, dishwasher, elevator, events, family, fireplace, gym, jacuzzi, kitchen, net, parking, pets, pool, smoke, tv, washer_dryer (comma-separated if you want to filter on multiple values)
     *      - page (optional - integer - default: 1) 
     *      - per_page (optional - integer - default: 24) 
     */
    public function getSearch($search_id, $filters)
    {
        return $this->getResponse(
            sprintf('/search/%s?%s', $search_id, $this->buildParameters($filters))
        );
    }
    
    /**
     * @param string $id
     * @param array $params
     *      - culture - string - mandatory
     *      - currency - string - mandatory
     * @return array
     */
    public function getOffer($id, $params = [])
    {
        return $this->getResponse(
            sprintf('/rooms/%s?%s', $id, $this->buildParameters($params))
        );
    }
    
    /**
     * @param string $id
     * @param array $params
     *      - culture - string - mandatory
     * @return array
     */
    public function getDestination($id, $params = [])
    {
        return $this->getResponse(
            sprintf('/destinations/%s?%s', $id, $this->buildParameters($params))
        );
    }
    
    /**
     * Get destinations matching a string query
     * Useful for autocompletion
     * 
     * @param string $query Term to search for
     * @param string $culture Which language to return the destinations names
     * @param int $size How many destinations you want
     * @return array
     */
    public function getDestinationsPredictions($query, $culture, $size = 10)
    {
        return $this->getResponse(
            sprintf('/destinations/search?%s', $this->buildParameters([
                'q' => $query,
                'culture' => $culture,
                'size' => $size,
            ]))
        );
    }
    
    /**
     * Calls backend using http client
     * 
     * @param string $endpoint
     * @return array
     */
    private function getResponse($endpoint, $method = 'get', $data = [])
    {
        $http_client = $this->getClient();
        $url = $this->conf['host'] . $endpoint;
        
        try {
            if ('post' === $method) {
                $response = $http_client->post($url, [
                    'form_params' => $data,
                ]);
            } elseif ('get' === $method) {
                $response = $http_client->get($url);
            } else {
                throw new \InvalidArgumentException('$method must be one of : [get, post]');
            }
            
            $return = json_decode($response->getBody(true), true);
        } catch (\Exception $e) {
            $return = [];
        }
        
        return $return;
    }
    
    /**
     * 
     * @return GuzzleClient
     */
    private function getClient()
    {
        $http_client = new GuzzleClient([
            'user-agent' => 'Likibu/1.0 (+http://www.likibu.com)',
        ]);
        
        return $http_client;
    }
    
    /**
     * 
     * @param array $raw
     * @return string Query string
     */
    private function buildParameters($raw = [], $as_array = false)
    {
        $params = array_filter(array_merge([
            'key' => $this->key,
        ], $raw));
        
        return $as_array ? $params : http_build_query($params);
    }
}
