import React from 'react';
import ReactDOM from 'react-dom';
import App from './App';
import * as serviceWorker from './serviceWorker';
import 'bootstrap/dist/css/bootstrap.min.css'
// Установите пакет:
// npm install @vkontakte/vk-bridge
import bridge from '@vkontakte/vk-bridge';

// Отправляет событие нативному клиенту
// Init VK  Mini App обязательно
bridge.send("VKWebAppInit");


ReactDOM.render(<App />, document.getElementById('root'));

// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: https://bit.ly/CRA-PWA
serviceWorker.unregister();
