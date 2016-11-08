<?php

namespace Likibu;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    /**
     * Default configuration
     * @var array
     */
    private $defaults = array(
        'host' => 'http://api.likibu.com',
    );
    
    /**
     * Configuration
     * @var array
     */
    private $conf = array();
    
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
    public function __construct($key, $conf = array()) 
    {
        $this->key = $key;
        $this->conf = array_merge($this->defaults, $conf);
    }
    
    /**
     * Search likibu's rooms. 
     * @param array $params 
     *      - where (mandatory - string) : where in the world should it search for rooms?
     *      - culture (mandatory - string) : give me the results in which language? (supported : fr, en, es, it, de)
     *      - currency (mandatory - string) : give me the prices in which currency? (3 letters ISO 4217 currency codes : EUR, USD, GBP, ...)
     *      - checkin (optional - string) : checkin date, yyyy-mm-dd format
     *      - checkout (optional - string) : checkout date, yyyy-mm-dd format
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
    public function search($params = array())
    {
        return $this->getResponse(
            sprintf('%s/rooms/?%s', $this->conf['host'], $this->buildParameters($params))
        );
    }
    
    /**
     * @param string $id
     * @param array $params
     *      - culture - string - mandatory
     *      - currency - string - mandatory
     * @return array
     */
    public function getOffer($id, $params = array())
    {
        return $this->getResponse(
            sprintf('%s/rooms/%s?%s', $this->conf['host'], $id, $this->buildParameters($params))
        );
    }
    
    /**
     * @param string $id
     * @param array $params
     *      - culture - string - mandatory
     *      - currency - string - mandatory
     * @return array
     */
    public function getDestination($id, $params = array())
    {
        return $this->getResponse(
            sprintf('%s/destinations/%s?%s', $this->conf['host'], $id, $this->buildParameters($params))
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
            sprintf('%s/destinations/search?%s', $this->conf['host'], $this->buildParameters(array(
                'q' => $query,
                'culture' => $culture,
                'size' => $size,
            )))
        );
    }
    
    /**
     * Calls backend using http client
     * @param string $url
     * @return array
     */
    private function getResponse($url)
    {
        $httpClient = $this->getClient();
        
        try {
            $response = $httpClient->get($url);
            
            $return = json_decode($response->getBody(true), true);
        } catch (\Exception $e) {
            $return = array();
        }
        
        return $return;
    }
    
    /**
     * 
     * @return GuzzleClient
     */
    private function getClient()
    {
        $httpClient = new GuzzleClient([
            'user-agent' => 'Likibu/1.0 (+http://www.likibu.com)',
        ]);
        
        return $httpClient;
    }
    
    /**
     * 
     * @param array $raw
     * @return string Query string
     */
    private function buildParameters($raw)
    {
        $params = array_map(function($value) {
            if (is_array($value)) {
                return implode(',', $value);
            }
            
            return $value;
        }, array_filter(array_merge(array(
            'key' => $this->key,
        ), $raw)));
        
        return http_build_query($params);
    }
}
