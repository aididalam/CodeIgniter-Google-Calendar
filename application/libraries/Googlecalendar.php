<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Google Calander Library
 * 
 *
 * @author      Aidid Alam
 * @package     Blade
 * @category    Libraries
 * @version     1.0.0
 * @url         https://github.com/aididalam/CodeInteger-Google-Calander-Library
 *
 */
class GoogleCalendar {
    /**
     * Veriable for Storing instance
     * 
     *
     */
    private $CI;
    private $service;
    private $json_path;
    private $calendarId;
    private $whereQuey = array();
    private $optParams = array(
        'singleEvents' => true,
    );
    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->config->load('gcalander');
        $this->json_path = $this->CI->config->item('calendar_json_path');
        $this->calendarId = $this->CI->config->item('calendarId');
        $this->__startService();
    }
    /**
     * Starting Google service
     * Privatekey,client email,client id from json
     *
     */
    public function __startService() {

        $json_string = file_get_contents($this->json_path, true);
        $json = json_decode($json_string, true);
        $type = $json['type'];
        $private_key = $json['private_key'];
        $client_email = $json['client_email'];
        $client_id = $json['client_id'];
        $scopes = array(Google_Service_Calendar::CALENDAR);

        $client = new Google_Client();
        $client->setScopes($scopes);
        $client->setAuthConfig(array(
            'type' => $type,
            'client_email' => $client_email,
            'client_id' => $client_id,
            'private_key' => $private_key
        ));

        $this->service = new Google_Service_Calendar($client);
    }

    /**
     * Option Setter
     *
     */


    /**
     * @param bool $bool
     * sets optParams['singleEvents']
     * return this class
     */
    public function singleEvents($bool) {
        $this->optParams['singleEvents'] = $bool;
        return $this;
    }

    /**
     * @param bool $bool
     * optParams['showDeleted']
     * return this class
     */
    public function showDeleted($bool) {
        $this->optParams['showDeleted'] = $bool;
        return $this;
    }

    /**
     * @param bool $bool
     * optParams['showHiddenInvitations']
     * return this class
     */
    public function showHiddenInvitations($bool) {
        $this->optParams['showHiddenInvitations'] = $bool;
        return $this;
    }

    /**
     * @param String $orderBy
     * optParams['orderBy']
     * return this class
     */
    public function orderBy($orderBy) {
        $this->optParams['orderBy'] = $orderBy;
        return $this;
    }

    /**
     * @param String $timeMin
     * optParams['timeMin']
     * return this class
     */
    public function timeMin($timeMin) {
        $this->optParams['timeMin'] = date($timeMin);
        return $this;
    }

    /**
     * @param String $timeMax
     * optParams['timeMax']
     * return this class
     */
    public function timeMax($timeMax) {
        $this->optParams['timeMax'] = date($timeMax);
        return $this;
    }
    /**
     * @param String $updatedMine
     * optParams['updatedMin']
     * return this class
     */
    public function updatedMin($updatedMin) {
        $this->optParams['updatedMin'] = date($updatedMin);
        return $this;
    }

    /**
     * @param int $updatedMine
     * optParams['updatedMin']
     * return this class
     */
    public function maxResults($max) {
        $this->optParams['maxResults'] = $max;
        return $this;
    }


    /**
     * @param String $key
     * @param String $value
     * Query Result
     * return this class
     */
    public function where($key, $value) {
        $this->whereQuey[$key] = $value;
        return $this;
    }

    /**
     * Return all Event List
     */

    public function getAll() {
        $results = $this->service->events->listEvents($this->calendarId, $this->optParams);
        return $results->getItems();
    }

    /**
     * @param String $id
     * return event
     */

    public function find($id) {
        $event = array();
        try {
            $event = $this->service->events->get($this->calendarId, $id);
        } catch (Exception $e) {
            show_error('Invalid id for find operation<br>' . json_encode($e));
        }
        return $event;
    }

    /**
     * Get Events after querying
     */
    public function get() {
        $results = $this->service->events->listEvents($this->calendarId, $this->optParams)->getItems();
        foreach ($this->whereQuey as $key => $value) {
            $results = $this->__search($results, $key, $value);
        }
        return $results;
    }

    /**
     * @param array $data
     * return newly inserted event
     */
    public function insert($data) {
        $event = new Google_Service_Calendar_Event($data);
        $event = $this->service->events->insert($this->calendarId, $event);
        return $event;
    }

    /**
     * @param string $id
     */
    public function delete($id) {
        $this->service->events->delete($this->calendarId, $id);
    }

    /**
     * @param Obj $event
     * return $event
     */
    public function update($event) {
        $updatedEvent = $this->service->events->update($this->calendarId, $event->getId(), $event);
        return $updatedEvent->getUpdated();
    }


    /**
     * For Querying events
     * @param array $array
     * @param array $key
     * @param array $value
     * return $event
     */
    function __search($array, $key, $value) {
        $array = $this->__convertToArray($array);
        $results = array();
        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, $this->__search($subarray, $key, $value));
            }
        }

        return $this->__convertToObject($results);
    }

    /**
     * For Converting object to Array
     * @param obj $obj
     * return $array
     */

    function __convertToArray($obj) {
        return json_decode(json_encode($obj), true);
    }

    /**
     * For Converting Array Object
     * @param array $arr
     * return $Obj
     */
    function __convertToObject($arr) {
        return json_decode(json_encode($arr), false);
    }
}
