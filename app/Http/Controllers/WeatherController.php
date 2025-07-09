<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Support\Facades\Http;


class WeatherController extends Controller
{
    public function showWeather()
    {
        // Ganti dengan API key yang kamu dapatkan dari OpenWeatherMap
        $apiKey = 'e4eef249a39532aff45411e08ed49442';
        $city = 'Jakarta'; // Ganti dengan kota yang diinginkan
        $units = 'metric'; // Untuk mendapatkan suhu dalam Celsius

        // Mengambil data cuaca dari OpenWeatherMap API
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather?q={$city}&units={$units}&appid={$apiKey}");

        if ($response->successful()) {
            $weatherData = $response->json();
            $weatherDescription = $weatherData['weather'][0]['description'];
            $weatherIcon = $weatherData['weather'][0]['icon']; // Menambahkan ikon cuaca
            $temperature = $weatherData['main']['temp'];
            
            // Mengembalikan data cuaca sebagai array
            return [
                'weatherDescription' => $weatherDescription,
                'temperature' => $temperature,
                'weatherIcon' => $weatherIcon
            ];
        } else {
            return ['error' => 'Gagal mengambil data cuaca']; // Pesan error jika API gagal
        }
    }
}