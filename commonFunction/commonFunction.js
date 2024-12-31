const apiUrl = "http://127.0.0.1:8000/api/",
appHeader = "application/json";
axios.defaults.baseURL = apiUrl; // Set a common base URL

async function login(url, payload) {
  try {
    const response = await axios.post(apiUrl + url, payload);
    localStorage.setItem('token', response.data.data.token);
    localStorage.setItem('userId', response.data.data.userId);
    localStorage.setItem('role', response.data.data.role);
    localStorage.setItem('name', response.data.data.name);
    localStorage.setItem('email', response.data.data.email);
    localStorage.setItem('status', response.data.data.status);
    localStorage.setItem('profile', response.data.data.profile);
    return response.data;
  } catch (error) {
    console.error('Error with POST request:', error);
    return error.response ? error.response.data : { message: 'Unknown error occurred' };

  }
}

async function logout(url, data) {
  try {
    const response = await axios.post(apiUrl + url, data);
    return response.data; // Handle the response data
  } catch (error) {
    console.error('Error with POST request:', error);
    return error.response.data;
  }
}

axios.interceptors.request.use(function (config) {
  config.headers.Authorization = `Bearer ${localStorage.getItem('token')}`; // Replace with your token
  return config;
});

async function getAllListing(url) {
  try {
    const response = await axios.get(apiUrl + url); // Replace with your API endpoint
    return response.data;
  } catch (error) {
    console.error('Error with API call:', error);
    return error.response.data;
  }
}

async function getDetailsById(url, id) {
  try {
    const response = await axios.get(apiUrl + url + '/' + id); // Replace with your API endpoint
    return response.data;
  } catch (error) {
    console.error('Error with API call:', error);
    return error.response.data;
  }
}

async function addData(url, data) {
  try {
    const response = await axios.post(apiUrl + url, data); // Replace with your API endpoint
      return response.data;
  } catch (error) {
    console.error('Error with API call:', error);
    return error.response.data;
  }
}

async function addDataById(url, id) {
  try {
    const response = await axios.post(apiUrl + url + '/' + id); // Replace with your API endpoint
    return response.data;
  } catch (error) {
    console.error('Error with API call:', error);
    return error.response.data;
  }
}

async function updateData(url, data) {
  try {
    const response = await axios.put(apiUrl + url, data); // Replace with your API endpoint
    return response.data;
  } catch (error) {
    console.error('Error with API call:', error);
    return error.response.data;
  }
}

async function deleteData(url, data) {
  try {
    const response = await axios.delete(apiUrl + url, data); // Replace with your API endpoint
    return response.data;
  } catch (error) {
    console.error('Error with API call:', error);
    return error.response.data;
  }
}

async function deleteDataById(url) {
  try {
    const response = await axios.delete(apiUrl + url); // Replace with your API endpoint
    return response.data;
  } catch (error) {
    console.error('Error with API call:', error);
    return error.response.data;
  }
}