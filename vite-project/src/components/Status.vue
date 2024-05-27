<template>
    <div class="status-toggle">
      <input type="checkbox" class="checkbox" id="checkbox" :checked="isActive" @change="toggleStatus">
      <label class="switch" for="checkbox">
        <span class="slider"></span>
      </label>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      modelValue: {
        type: String,
        default: 'away'
      }
    },
    computed: {
      isActive() {
        return this.modelValue === 'active';
      }
    },
    methods: {
      toggleStatus(event) {
        const newValue = event.target.checked ? 'active' : 'away';
        this.$emit('update:modelValue', newValue);
      }
    }
  };
  </script>
  
  <style scoped>
  .checkbox {
    opacity: 0;
    width: 0;
    height: 0;
    position: absolute;
  }
  
  .status-toggle {
    width: 60px;
    height: 34px;
    position: relative;
  }
  
  .switch {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    background-color: #e9e9eb;
    border-radius: 34px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  .slider {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #FFFFFF;
    box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.15), 0px 3px 1px rgba(0, 0, 0, 0.06);
    transition: transform 0.3s;
    transform: translateX(0);
  }
  
  .checkbox:checked + .switch {
    background-color: #34C759;
  }
  
  .checkbox:checked + .switch .slider {
    transform: translateX(30px);
  }
  </style>
  