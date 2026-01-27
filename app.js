
  // ELEMENT SELECTORS

const input = document.querySelector('.text');
const btn = document.querySelector('.btn');

const temperature = document.querySelector('.temp-val');
const locationCity = document.querySelector('.location');
const timeEl = document.querySelector('.time');
const subtitle = document.querySelector('.subtitle');

const humidity = document.querySelector('.percent');
const seaLevel = document.querySelector('.sea');
const sunrise = document.querySelector('.sunrise');
const wind = document.querySelector('.wind');

const TempMax = document.querySelector('.TempMax');
const TempMin = document.querySelector('.TempMinVal');

const graphImg = document.querySelector('.graph-img');
const imgText = document.querySelector('.img-text');


   //TIME FORMATTER

function formatTime(unix) {
    const d = new Date(unix * 1000);
    let h = d.getHours();
    let m = d.getMinutes();
    const ampm = h >= 12 ? 'PM' : 'AM';
    h = h % 12 || 12;
    return `${h}:${m < 10 ? '0' + m : m} ${ampm}`;
}


   //RESET UI (ERROR)

function resetUI(message = 'City not found') {
    temperature.textContent = '--';
    locationCity.textContent = '--';
    subtitle.textContent = '--';
    timeEl.textContent = '--';

    humidity.textContent = '--';
    wind.textContent = '--';
    seaLevel.textContent = '--';
    sunrise.textContent = '--';

    TempMax.textContent = '--';
    TempMin.textContent = '--';

    imgText.textContent = message;
    graphImg.src = 'images/404.png';
}


   //FETCH WEATHER (SECURE)

async function getWeather(cityName) {
    try {
        const city = encodeURIComponent(cityName.trim());
        const res = await fetch(`auth/weather_proxy.php?city=${city}`, {
            method: 'GET',
            credentials: 'same-origin'
        });

         // Server responded but error
        if (!res.ok) {
            resetUI('Service unavailable');
            return;
        }

        const data = await res.json();

        if (data.cod === 404 || data.cod === '404') {
            resetUI('City not found');
            return;
        }

        // UPDATE UI (XSS SAFE) 
        temperature.textContent = Math.round(data.main.temp);
        locationCity.textContent = `${data.name}, ${data.sys.country}`;
        subtitle.textContent = `${data.name}, ${data.sys.country}`;

        humidity.textContent = `${data.main.humidity}%`;
        wind.textContent = `${data.wind.speed} km/h`;
        seaLevel.textContent = data.main.sea_level ?? 'N/A';

        TempMax.textContent = `${Math.round(data.main.temp_max)}°C`;
        TempMin.textContent = `${Math.round(data.main.temp_min)}°C`;

        sunrise.textContent = formatTime(data.sys.sunrise);
        timeEl.textContent = formatTime(data.sys.sunset);

        imgText.textContent = data.weather[0].description;

        //  ICON SWITCH 
        switch (data.weather[0].main) {
            case 'Rain':
                graphImg.src = 'images/rain.png';
                break;
            case 'Clear':
                graphImg.src = 'images/clear.png';
                break;
            case 'Clouds':
                graphImg.src = 'images/cloud.png';
                break;
            case 'Mist':
            case 'Haze':
                graphImg.src = 'images/mist.png';
                break;
            default:
                graphImg.src = 'images/cloud.png';
        }

    } catch (e) {
        console.error(e);
        resetUI('City not found');
    }
}


//    DEFAULT LOAD

getWeather('Kolkata');


//    EVENTS

btn.addEventListener('click', () => {
    if (input.value.trim()) {
        getWeather(input.value);
    }
});

input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && input.value.trim()) {
        getWeather(input.value);
    }
});
