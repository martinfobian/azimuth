<?php
# --------------------------------------------------------------------------------------------
#                  __                     /\ \__/\ \
#    __     ____  /\_\    ___ ___   __  __\ \ ,_\ \ \___
#  /'__`\  /\_ ,`\\/\ \ /' __` __`\/\ \/\ \\ \ \/\ \  _ `\
# /\ \L\.\_\/_/  /_\ \ \/\ \/\ \/\ \ \ \_\ \\ \ \_\ \ \ \ \
# \ \__/.\_\ /\____\\ \_\ \_\ \_\ \_\ \____/ \ \__\\ \_\ \_\
#  \/__/\/_/ \/____/ \/_/\/_/\/_/\/_/\/___/   \/__/ \/_/\/_/
#
#               Azimuth : Simple PHP library to compute azimuth (°), distance (km) & sight altitude (°)
#               GNU GPL v3
#               Gautier Michelin, 2015
#               based on Don Cross work, http://cosinekitty.com/compass.html
#
#               Usage example
#
# -------------------------------------------------------------------------------------------

    require_once('lib/azimuth.php');

    $me = array("lat"=> 52.509915, "lon"=>13.506030, "elv"=>41);
    $plane = array("lat"=> 52.4772, "lon"=>13.4841, "elv"=>37000 * 0.3048);

    // Le Mans is 181.149 km from Paris, Paris is at NW from Le Mans (58°), altitude is -1 so you should look
    // just under the horizon from the Tour Eiffel to have a look at Le Mans, but guess what, it's a bit far...

    $result = Calculate($plane, $me);
		print_r($result);
