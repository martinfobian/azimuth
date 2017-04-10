<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 10.04.17
 * Time: 11:08
 */

namespace Fbnio;


class ReferencePoint
{
    protected $x;
    protected $y;
    protected $z;
    protected $radius;

    /**
     * ReferencePoint constructor.
     * @param $x
     * @param $y
     * @param $z
     * @param $radius
     */
    public function __construct($x, $y, $z, $radius)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
        $this->radius = $radius;
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     * @return ReferencePoint
     */
    public function setX($x)
    {
        $this->x = $x;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     * @return ReferencePoint
     */
    public function setY($y)
    {
        $this->y = $y;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getZ()
    {
        return $this->z;
    }

    /**
     * @param mixed $z
     * @return ReferencePoint
     */
    public function setZ($z)
    {
        $this->z = $z;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * @param mixed $radius
     * @return ReferencePoint
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;
        return $this;
    }
}
