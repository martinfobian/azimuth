<?php

namespace Fbnio;

class Azimuth
{
    /**
     * ParseAngle : test if an angle is valid and between -limit to +limit]
     *
     * @param [float]  $angle value of the angle to check
     * @param [float] $limit value to use as a positive or negative boundary
     */
    protected function parseAngle($angle, $limit = 360) {
        if (is_nan($angle) || ($angle < -$limit) || ($angle > $limit)) {
            return null;
        } else {
            return $angle;
        }
    }

    /**
     * ParseElevation : test if an elevation is valid (check only if is a number for now)
     *
     * @param [float] $angle value of the angle to check
     */
    protected function parseElevation($angle)
    {
        if (is_nan($angle)) {
            return null;
        } else {
            return $angle;
        }
    }

    /**
     * ParseLocation : test if coordinates are valid, it should an array with at least "lat" and "long"
     *
     * @param array $coordinates an array containing at least "lon" and "lat" values
     */
    protected function parseLocation(array $coordinates) {
        //if (!isset($coordinates["lat"])) $coordinates["lat"] = 0;
        //if (!isset($coordinates["lon"])) $coordinates["lon"] = 0;
        if (!isset($coordinates["elv"])) $coordinates["elv"] = 0;

        $lat = $this->parseAngle($coordinates["lat"], 90.0);
        $location = null;
        if ($lat != null) {
            $lon = $this->parseAngle($coordinates["lon"], 180.0);
            if ($lon != null) {
                $elv = $this->parseElevation($coordinates["elv"]);
                if ($elv != null) {
                    $location = array('lat'=>$lat, 'lon'=>$lon, 'elv'=>$elv);
                }
            }
        }
        return $location;
    }

    /**
     * [EarthRadiusInMeters description]
     * @param [type] $latitudeRadians [description]
     */
    protected function getEarthRadiusInMeters($latitudeRadians) {
        // http://en.wikipedia.org/wiki/Earth_radius
        $a = 6378137.0;  // equatorial radius in meters
        $b = 6356752.3;  // polar radius in meters
        $cos = cos($latitudeRadians);
        $sin = sin($latitudeRadians);
        $t1 = $a * $a * $cos;
        $t2 = $b * $b * $sin;
        $t3 = $a * $cos;
        $t4 = $b * $sin;
        return sqrt(($t1*$t1 + $t2*$t2) / ($t3*$t3 + $t4*$t4));
    }

//    /**
//     * [LocationToPoint description]
//     * @param array $c [description]
//     */
//    protected function getReferencePoint(array $c) {
//        // Convert (lat, lon, elv) to (x, y, z).
//        $lat = $c["lat"] * pi() / 180.0;
//        $lon = $c["lon"] * pi() / 180.0;
//        $radius = $c["elv"] + $this->getEarthRadiusInMeters($lat);
//        $cosLon = cos($lon);
//        $sinLon = sin($lon);
//        $cosLat = cos($lat);
//        $sinLat = sin($lat);
//        $x = $cosLon * $cosLat * $radius;
//        $y = $sinLon * $cosLat * $radius;
//        $z = $sinLat * $radius;
//        return array('x'=>$x, 'y'=>$y, 'z'=>$z, 'radius'=>$radius);
//    }

    protected function getReferencePoint(Location $location)
    {
        $lat = $location->getLatitude() * pi() / 180.0;
        $lon = $location->getLongitude() * pi() / 180.0;
        $radius = $location->getElevationInMeters() + $this->getEarthRadiusInMeters($lat);
        $cosLon = cos($lon);
        $sinLon = sin($lon);
        $cosLat = cos($lat);
        $sinLat = sin($lat);
        $x = $cosLon * $cosLat * $radius;
        $y = $sinLon * $cosLat * $radius;
        $z = $sinLat * $radius;
        return new ReferencePoint($x, $y, $z, $radius);
    }

//    /**
//     * [Distance description]
//     * @param array $ap [description]
//     * @param array $bp [description]
//     */
//    protected function getDistance (array $ap, array $bp) {
//        $dx = $ap["x"] - $bp["x"];
//        $dy = $ap["y"] - $bp["y"];
//        $dz = $ap["z"] - $bp["z"];
//        return sqrt($dx*$dx + $dy*$dy + $dz*$dz);
//    }

//    protected function getDistance(ReferencePoint $a, ReferencePoint $b)
//    {
//        $dx = $a->getX() - $b->getX();
//        $dy = $a->getY() - $b->getY();
//        $dz = $a->getZ() - $b->getZ();
//        return sqrt($dx * $dx + $dy * $dy + $dz * $dz);
//    }

    protected function getDistance(Location $a, Location $b)
    {
        $a = $this->getReferencePoint($a);
        $b = $this->getReferencePoint($b);

        $dx = $a->getX() - $b->getX();
        $dy = $a->getY() - $b->getY();
        $dz = $a->getZ() - $b->getZ();
        return sqrt($dx * $dx + $dy * $dy + $dz * $dz);
    }

    /**
     * @param Location $b
     * @param Location $a
     * @return ReferencePoint
     */
    protected function rotateGlobe(Location $b, Location $a)
    {
        $bradius = $this->getReferencePoint($b)->getRadius();

        // Get modified coordinates of 'b' by rotating the globe so that 'a' is at lat=0, lon=0.
        $b = clone $b;
        $b->setLongitude($b->getLongitude() - $a->getLongitude());
        $bRotated = $this->getReferencePoint($b);

        $alat = -1 * $a->getLatitude() * pi() / 180.0;
        $acos = cos($alat);
        $asin = sin($alat);

        $bx = ($bRotated->getX() * $acos) - ($bRotated->getZ() * $asin);
        $by = $bRotated->getY();
        $bz = ($bRotated->getX() * $asin) + ($bRotated->getZ() * $acos);

        return new ReferencePoint($bx, $by, $bz, $bradius);
    }

//    /**
//     * [RotateGlobe description]
//     * @param array  $b       [description]
//     * @param array  $a       [description]
//     * @param [type] $bradius [description]
//     * @param [type] $aradius [description]
//     */
//    protected function rotateGlobe(Location $b, Location $a) {
//        // Get modified coordinates of 'b' by rotating the globe so that 'a' is at lat=0, lon=0.
//        $br = array('lat'=> $b["lat"], 'lon'=> ($b["lon"] - $a["lon"]), 'elv'=>$b["elv"]);
//        $brp = $this->getReferencePoint($br);
//
//        // scale all the coordinates based on the original, correct geoid radius...
//        $brp["x"] *= ($bradius / $brp["radius"]);
//        $brp["y"] *= ($bradius / $brp["radius"]);
//        $brp["z"] *= ($bradius / $brp["radius"]);
//        $brp["radius"] = $bradius;   // restore actual geoid-based radius calculation
//
//        // Rotate brp cartesian coordinates around the z-axis by a.lon degrees,
//        // then around the y-axis by a.lat degrees.
//        // Though we are decreasing by a.lat degrees, as seen above the y-axis,
//        // this is a positive (counterclockwise) rotation (if B's longitude is east of A's).
//        // However, from this point of view the x-axis is pointing left.
//        // So we will look the other way making the x-axis pointing right, the z-axis
//        // pointing up, and the rotation treated as negative.
//
//        $alat = -$a["lat"] * pi() / 180.0;
//        $acos = cos($alat);
//        $asin = sin($alat);
//
//        $bx = ($brp["x"] * $acos) - ($brp["z"] * $asin);
//        $by = $brp["y"];
//        $bz = ($brp["x"] * $asin) + ($brp["z"] * $acos);
//
//        return array('x'=>$bx, 'y'=>$by, 'z'=>$bz);
//    }


    /**
     * Calculate
     * ------------------
     * This function returns the azimuth, the distance between two points on the globe and the altitude
     * between 2 points on the globe, based on their latitude (°N), longitude (°E), and elevation (meters).
     *
     * @param array $origin containing at least "lat" for latitude as a float, "lon" for longitude as a float,
     *                      can contains "elv" for elevation in meters from the sea, default elevation fixed to
     *                      0 if not set
     * @param array $target containing at least "lat" for latitude as a float, "lon" for longitude as a float,
     *                      can contains "elv" for elevation in meters from the sea, default elevation fixed to
     *                      0 if not set
     */
    public function calculate(Location $origin, Location $target)
    {
        $originPoint = $this->getReferencePoint($origin);
        $targetPoint = $this->getReferencePoint($target);

        $distKm = 0.001 * round($this->getDistance($origin, $target));

        // Let's use origin trick to calculate azimuth:
        // Rotate the globe so that point A looks like latitude 0, longitude 0.
        // We keep the actual radii calculated based on the oblate geoid,
        // but use angles based on subtraction.
        // Point A will be at x=radius, y=0, z=0.
        // Vector difference B-A will have dz = N/S component, dy = E/W component.

        $rotatedTargetPoint = $this->rotateGlobe($target, $origin);
        $theta = atan2($rotatedTargetPoint->getZ(), $rotatedTargetPoint->getY()) * 180.0 / pi();
        $azimuth = 90.0 - $theta;
        if ($azimuth < 0.0) {
            $azimuth += 360.0;
        }
        if ($azimuth > 360.0) {
            $azimuth -= 360.0;
        }
        // Return rounded azimuth
        $azimuth = round(($azimuth*10)/10);

        // Calculate altitude, which is the angle above the horizon of B as seen from A.
        // Almost always, B will actually be below the horizon, so the altitude will be negative.
        $shadow = sqrt(($rotatedTargetPoint->getY() * $rotatedTargetPoint->getY()) + ($rotatedTargetPoint->getZ() * $rotatedTargetPoint->getZ()));
        $altitude = atan2 ($rotatedTargetPoint->getX() - $originPoint->getRadius(), $shadow) * 180.0 / pi();
        // Returns rounded altitude
        $altitude = round(($altitude * 100)/100);

        return array( "distKm" => $distKm, "azimuth" => $azimuth, "altitude" => $altitude);
    }

    public function calculateDistance(Location $origin, Location $target)
    {
        return $this->calculate($origin, $target)['distKm'];
    }

    public function calculateAltitudeAngle(Location $origin, Location $target)
    {
        return $this->calculate($origin, $target)['altitude'];
    }

    public function calculateAzimuth(Location $origin, Location $target)
    {
        return $this->calculate($origin, $target)['azimuth'];
    }
}