# PHP Weather Auth App

A secure weather application built with PHP and MySQL that includes user authentication and a server-side API proxy for fetching weather data safely.

## Features
- User authentication (Login / Register / Logout)
- Weather dashboard with real-time data
- Server-side API proxy to protect API keys
- Environment-based configuration using `.env`

## Security Features
- SQL Injection protection using prepared statements
- Secure password hashing with `password_hash()`
- Session fixation prevention using `session_regenerate_id()`
- CSRF token validation on sensitive actions
- Account lock after multiple failed login attempts
- Secure session cookies (HttpOnly, SameSite)
- XSS protection using output escaping (`htmlspecialchars`) on the server side
- Prevention of DOM-based XSS by avoiding `innerHTML` and using safe DOM updates
- API keys stored securely using environment variables (`.env`)

## Tech Stack
- HTML5
- CSS3
- JavaScript (Fetch API)
- PHP
- MySQL
- XAMPP (Apache + MySQL)
- OpenWeather API

## Project Structure
