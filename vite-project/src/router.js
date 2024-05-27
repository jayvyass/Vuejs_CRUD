// src/router.js
import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue';
import Student from './components/Student.vue';
import Teacher from './components/Teacher.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/students',
    name: 'Student',
    component: Student
  },
  {
    path: '/teachers',
    name: 'teachers',
    component: Teacher
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
