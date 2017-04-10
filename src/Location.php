<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 10.04.17
 * Time: 10:59
 */

namespace Fbnio;


class Location
{
    protected $latitude;
    protected $longitude;
    protected $elevationInMeters;

    /**
     * ReferencePoint constructor.
     * @param $latitude
     * @param $longitude
     * @param $elevationInMeters
     */
    public function __construct($latitude, $longitude, $elevationInMeters = 0)
    {
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
        $this->setElevationInMeters($elevationInMeters);
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     * @return ReferencePoint
     */
    public function setLatitude($latitude)
    {
        $this->checkAngle($latitude, 90);

        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     * @return ReferencePoint
     */
    public function setLongitude($longitude)
    {
        $this->checkAngle($longitude, 180);

        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getElevationInMeters()
    {
        return $this->elevationInMeters;
    }

    /**
     * @param mixed $elevationInMeters
     * @return ReferencePoint
     */
    public function setElevationInMeters($elevationInMeters)
    {
        $this->checkElevation($elevationInMeters);

        $this->elevationInMeters = $elevationInMeters;
        return $this;
    }

    protected function checkAngle($angle, $limit)
    {
//        if (is_nan($angle) || ($angle < -$angle) || ($angle > $limit)) {
//            throw new \Exception('Invalid argument: ' . $angle);
//        }
    }

    protected function checkElevation($elevationInMeters)
    {
//        if (is_nan($elevationInMeters)) {
//            throw new \Exception('Invalid argument: ' . $elevationInMeters);
//        }
    }
}