<template>
  <div class="container">
    <!-- Form Section -->
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card mt-4">
          <div class="card-body">
            <form @submit.prevent="save" v-if="showForm" class="w-100">
              <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" class="form-control" v-model="formData.name">
              </div>
              <ImageUpload :label="'Image'" :imageUrl="formData.image" :formData="formData" @image-selected="handleImageSelected" />
              <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" class="form-control" v-model="formData.email">
              </div>
              <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" class="form-control" v-model="formData.phone">
              </div>
              <div class="form-group form-check">
                <StatusToggle v-model="formData.status" />
              </div>
              <button type="submit" class="btn btn-primary">Save</button>
              <div v-if="errorMessage" class="error-message mt-3">{{ errorMessage }}</div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Teacher List Section -->
    <div class="row justify-content-center mt-4">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h2 class="text-center font-weight-bold">TEACHER LIST</h2>
            <button class="btn btn-primary float-right" @click="toggleForm">Add Record</button>
          </div>
            <table id="teacherTable" class="table table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Image</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="teacher in filteredTeachers" :key="teacher.id">
                  <td>{{ teacher.name }}</td>
                  <td>{{ teacher.email }}</td>
                  <td>{{ teacher.phone }}</td>
                  <td>
                    <img :src="getImageUrl(teacher.image)" alt="Teacher Image" class="thumbnail" style="height:60px;width:60px;">
                  </td>
                  <td>{{ teacher.status }}</td>
                  <td>
                    <button class="btn btn-sm btn-primary" @click="edit(teacher)">Edit</button>
                    <button class="btn btn-sm btn-danger" @click="deleteTeacher(teacher.id)">Delete</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
import axios from 'axios';
import ImageUpload from './Image.vue';
import StatusToggle from './Status.vue';

export default {
  components: {
    ImageUpload,
    StatusToggle
  },
  name: 'Teacher',
  data() {
    return {
      errorMessage: '',
      assetPath: 'http://127.0.0.1:8001/',
      formData: {
        name: '',
        email: '',
        phone: '',
        image: null,
        status: 'away'
      },
      teachers: [],
      showForm: false,
      searchQuery: '',
      currentPage: 1,
      perPage: 5
    };
  },
  computed: {
    filteredTeachers() {
      const filtered = this.teachers.filter(teacher =>
        teacher.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
        teacher.email.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
        teacher.phone.includes(this.searchQuery)
      );

      const start = (this.currentPage - 1) * this.perPage;
      const end = start + this.perPage;

      return filtered.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.teachers.length / this.perPage);
    }
  },
  created() {
    this.teacherLoad();
  },
  mounted() {
    this.$nextTick(() => {
      $('#teacherTable').DataTable();
    });
  },
  methods: {
    getImageUrl(imagePath) {
      return `http://127.0.0.1:8001/${imagePath}`;
    },
    teacherLoad() {
      axios.get('http://127.0.0.1:8001/api/teacher')
        .then(response => {
          this.teachers = response.data;
          this.$nextTick(() => {
            $('#teacherTable').DataTable();
          });
        })
        .catch(error => {
          console.error('Error fetching teacher data:', error);
        });
    },
    handleImageSelected(file) {
      this.formData.image = file;
      this.imageUrl = URL.createObjectURL(file);
    },
    save() {
      if (this.formData.id) {
        this.update();
      } else {
        let formData = new FormData();
        formData.append('name', this.formData.name);
        formData.append('email', this.formData.email);
        formData.append('phone', this.formData.phone);
        formData.append('image', this.formData.image);
        formData.append('status', this.formData.status);
        
        axios.post('http://127.0.0.1:8001/api/teacher', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        .then(response => {
          console.log('Teacher added successfully:', response.data);
          this.teacherLoad();
          this.formData = {
            name: '',
            email: '',
            phone: '',
            image: null,
            status: 'away'
          };
          alert('Teacher added successfully:');
          this.showForm = false;
        })
        .catch(error => {
          this.errorMessage = error.response.data.message;
        });
      }
    },
    edit(teacher) {
      this.showForm = true;
      this.formData = { ...teacher };
      if (this.formData.image) {
        this.imageUrl = this.getImageUrl(this.formData.image);
      } else {
        this.imageUrl = '';
      }
    },
    update() {
      console.log(this.formData);
      axios.post(`http://127.0.0.1:8001/api/teacher/${this.formData.id}`, this.formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(response => {
        this.teacherLoad();
        this.formData = {
          name: '',
          email: '',
          phone: '',
          image: null,
          status: 'away'
        };
        alert('Teacher updated successfully:');
        this.showForm = false;
      })
      .catch(error => {
        console.error('Error updating teacher:', error);
      });
    },
    deleteTeacher(teacherId) {
      if (confirm('Are you sure you want to delete this teacher?')) {
        axios.delete(`http://127.0.0.1:8001/api/teacher/${teacherId}`)
          .then(response => {
            this.teacherLoad();
          })
          .catch(error => {
            console.error('Error deleting teacher:', error);
          });
      }
    },
    toggleForm() {
      this.showForm = !this.showForm;
      if (!this.showForm) {
        this.formData = {
          name: '',
          email: '',
          phone: '',
          image: null,
          status: 'away'
        };
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    }
  }
};
</script>

<style scoped>
.error-message {
  color: red;
  margin-top: 10px;
}
.thumbnail {
  height: 60px;
  width: 60px;
}
.pagination-controls {
  margin-top: 20px;
}
</style>
