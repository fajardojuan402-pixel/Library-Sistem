import axios from 'axios';

const API_URL = 'http://127.0.0.1:8000'; // evita depender de .env por ahora
const api = axios.create({
  baseURL: API_URL + '/api/v1',
  timeout: 10000,
});

export default api;