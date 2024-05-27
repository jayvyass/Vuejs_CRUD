<template>
    <div class="home-container">
      <h1 class="title">Welcome to the Home Page</h1>
      <router-link to="/students" class="link-button">Go to Student List</router-link><br>
      <router-link to="/teachers" class="link-button">Go to Teacher List</router-link>
    </div>
  </template>
  
  <script>
  export default {
    name: 'Home'
  };
  </script>
  
  <style scoped>
  .home-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background-image: linear-gradient(to top, #3f51b1 0%, #5a55ae 13%, #7b5fac 25%, #8f6aae 38%, #a86aa4 50%, #cc6b8e 62%, #f18271 75%, #f3a469 87%, #f7c978 100%);
    text-align: center;
    color: #fff;
  }
  
  .title {
    font-size: 3rem;
    margin-bottom: 20px;
    animation: fadeIn 2s ease-in-out;
  }
  
  .link-button {
    font-size: 1.2rem;
    color: #fff;
    background-color: #40a711;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
    animation: bounce 2s infinite;
  }
  
  .link-button:hover {
    background-color: #030836;
  }
  
  @keyframes fadeIn {
    0% {
      opacity: 0;
      transform: translateY(-20px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  @keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
      transform: translateY(0);
    }
    40% {
      transform: translateY(-10px);
    }
    60% {
      transform: translateY(-5px);
    }
  }
  </style>
  