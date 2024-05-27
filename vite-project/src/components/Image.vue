<template>
  <div>
    <div class="form-group">
      <label>{{ label }}</label>
      <input type="file" @change="handleImageChange" class="form-control-file">
      <img v-if="imageUrl" :src="imageUrl" alt="" style="max-width: 100px; max-height: 100px;">
    </div>
  </div>
</template>

<script>
export default {
  props: {
    label: String,
    imageUrl: String,
    formData: Object 
  },
  methods: {
    
      handleImageChange(event) {
          this.formData.image = event.target.files[0];
          this.imageUrl = URL.createObjectURL(event.target.files[0]);
      },
    
  }
};
</script>

<style scoped>
/* Your custom styles */
</style>
