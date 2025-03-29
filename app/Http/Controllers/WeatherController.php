<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    public function getWeatherDetails(Request $request)
    {   
        $city = $request->input('city');

        if (empty($city)) {
            $city = "London"; // Default city
        }

        $city    = urlencode($city); // URL-encode the city name
        $api_key = ENV('WEATHER_API_KEY');
        $apiURL  = ENV('WEATHER_API_URL')."?key={$api_key}&q={$city}&aqi=yes";
        $json    = file_get_contents($apiURL);
        $data    = json_decode($json, true);
  
        $weatherData = [
          'name'      => $data["location"]["name"],
          'region'    => $data["location"]["region"],
          'country'   => $data["location"]["country"],
          'localtime' => $data["location"]["tz_id"],
          
          // Current weather variables
          'temperature'    => $data["current"]["temp_c"],
          'feelslike'      => $data["current"]["feelslike_c"],
          'pressure'       => $data["current"]["pressure_mb"],
          'precipitation'  => $data["current"]["precip_mm"],
          'cloudCover'     => $data["current"]["cloud"],
          'condition'      => $data["current"]["condition"]["text"],
          'icon'           => $data["current"]["condition"]["icon"],
          'wind_speed'     => $data["current"]["wind_kph"],  // Fixed this to use wind_kph (or another relevant field)
          'wind_direction' => $data["current"]["wind_dir"],
          'humidity'       => $data["current"]["humidity"],
          'uv'             => $data["current"]["uv"],
          'co2'            => $data["current"]["air_quality"]["co"],
          'no2'            => $data["current"]["air_quality"]["no2"],
          'o3'             => $data["current"]["air_quality"]["o3"],
          'so2'            => $data["current"]["air_quality"]["so2"]
        ];
  
        $uvDescription = self::getUvdescription($weatherData['uv']);
      
        return view('welcome', array_merge($weatherData, ['uvDescription' => $uvDescription]));

   }    

    private function getUvdescription($uv)
    {
        if ($uv <=2){
            $description = "LOW - Wear sunglasses on bright days. If you burn easily, cover up and use broad spectrum SPF 15+ sunscreen.";
          }
          else if ($uv >2 && $uv <= 5 ){
            $description = "MODERATE - Stay in shade near midday when the sun is strongest. If outdoors, wear sun-protective clothing, a wide-brimmed hat, and UV-blocking sunglasses.";
          }
          else if ($uv > 5 && $uv <=7 ){
            $description = "HIGH - Reduce time in the sun between 10 a.m. and 4 p.m. If outdoors, seek shade and wear sun-protective clothing, a wide-brimmed hat, and UV-blocking sunglasses.";
          }
          else if ($uv >7 && $uv <=10){
            $description = "VERY HIGH - Minimize sun exposure between 10 a.m. and 4 p.m. If outdoors, seek shade and wear sun-protective clothing, a wide-brimmed hat, and UV-blocking sunglasses..";
          }
          else if ($uv >=11){
            $description = "EXTREME - Extreme risk of harm from unprotected sun exposure. Take all precautions because unprotected skin and eyes can burn in minutes.";
          }   
        return $description;    
    }

}
