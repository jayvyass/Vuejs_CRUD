<template>
  <div class="container">
    <!-- Form Section -->
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card mt-4">
          <div class="card-body">
            <form @submit.prevent="save" v-if="showForm" class="w-40">
              <div class="form-group">
                <label for="name">Name:</label>
                <input type="name" id="name" class="form-control" v-model="formData.name">
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
              <div class="form-group">
                <label for="gender">Gender:</label><br>
                <input type="radio" id="male" name="gender" value="male" v-model="formData.gender">
                <label for="male">Male</label>
                <input type="radio" id="female" name="gender" value="female" v-model="formData.gender">
                <label for="female">Female</label><br>
              </div>
              Status:
              <StatusToggle v-model="formData.status" /><br>
              <button type="submit" class="btn btn-primary">Save</button>
              <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
            </form>
          </div>
        </div>
      </div>
    </div>   
    <!-- Student List Section -->
    <div class="row justify-content-center mt-4">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h2 class="text-center font-weight-bold">STUDENT LIST</h2>
            <button class="btn btn-primary float-right" @click="toggleForm">Add Record</button>
          </div>
          <div class="card-body">
            <table id="studentTable" class="table table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Gender</th>
                  <th>Image</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="student in students" :key="student.id">
                  <td>{{ student.name }}</td>
                  <td>{{ student.email }}</td>
                  <td>{{ student.phone }}</td>
                  <td>{{ student.gender }}</td>
                  <td>
                    <img :src="getImageUrl(student.image)" alt="Student Image" class="thumbnail" style="height:60px;width:60px;">
                  </td>
                  <td>{{ student.status }}</td>
                  <td>
                    <button class="btn btn-sm btn-primary" @click="editStudent(student)">Edit</button>
                    <button class="btn btn-sm btn-danger" @click="deleteStudent(student.id)">Delete</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
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
  name: 'Student',
  data() {
    return {
      errorMessage: '', 
      assetPath: 'http://127.0.0.1:8001/', 
      formData: {
        name: '',
        email: '',
        phone: '',
        image: null,
        status: 'away',
        gender: ''
      },
      students: [],
      showForm: false
    };
  },
  computed: {
    getImageUrl() {
      return (imagePath) => `http://127.0.0.1:8001/${imagePath}`;
    }
  },
  created() {
    this.studentLoad();
  },   
  methods: {
    studentLoad() {
      axios.get('http://127.0.0.1:8001/api/student')
        .then(response => {
          this.students = response.data;
          this.$nextTick(() => {
            $('#studentTable').DataTable();
          });
        })
        .catch(error => {
          console.error('Error fetching student data:', error);
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
        formData.append('gender', this.formData.gender); 
        
        axios.post('http://127.0.0.1:8001/api/student', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        .then(response => {
          console.log('Student added successfully:', response.data);
          this.studentLoad();
          this.formData = {
            name: '',
            email: '',
            phone: '',
            image: null,
            status: 'away',
            gender:'',
          };
          alert('Student added successfully:');
          this.showForm = false;
        })
        .catch(error => {
          this.errorMessage = error.response.data.message;
        });
      }
    },
    editStudent(student) {
      this.showForm = true;
      this.formData = {...student};    
      if (this.formData.image) {
        this.imageUrl = this.getImageUrl(this.formData.image);
      } else {                    
        this.imageUrl = '';
      }      
    },
    update() {
      console.log(this.formData);
      axios.post(`http://127.0.0.1:8001/api/student/${this.formData.id}`, this.formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(response => {
        this.studentLoad(); 
        this.formData = { 
          name: '',
          email: '',
          phone: '',
          image: ''
        };
        alert('Student updated successfully:');
      })
      .catch(error => {
        console.error('Error updating student:', error);
      });
    },
    deleteStudent(studentId) {
      if (confirm('Are you sure you want to delete this student?')) {
        axios.delete(`http://127.0.0.1:8001/api/student/${studentId}`)
          .then(response => {
            this.studentLoad();
          })
          .catch(error => {
            console.error('Error deleting student:', error);
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
          status: 'away',
          gender:''
        };
      }
    }
  }
};
</script>

<style scoped>
.error-message {
  background-color: #ffcccc;
  color: #cc0000;
  border: 1px solid #cc0000;
  padding: 10px;
  border-radius: 5px;
  margin-top: 10px;
}
.thumbnail {
  height: 60px;
  width: 60px;
}
</style>
