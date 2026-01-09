<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'

// State
const tasks = ref([])
const pagination = ref({})
const currentPage = ref(1)
const filterStatus = ref('all')

const showForm = ref(false)
const isEdit = ref(false)
const form = ref({
  id: null,
  title: '',
  description: '',
  status: 'pending',
  due_date: ''
})

// Fetch tasks from API
const fetchTasks = async () => {
  let url = `/api/tasks?page=${currentPage.value}`
  if (filterStatus.value !== 'all') {
    url += `&status=${filterStatus.value}`
  }

  const res = await axios.get(url)

  // DATA
  tasks.value = res.data.data || []

  // PAGINATION (THIS IS THE FIX)
  pagination.value = {
    current_page: res.data.meta.current_page,
    last_page: res.data.meta.last_page
  }
}


// Watch pagination & filter
watch(currentPage, fetchTasks)
watch(filterStatus, () => { currentPage.value = 1; fetchTasks() })

// Form functions
const openAddForm = () => { resetForm(); showForm.value = true; isEdit.value = false }
const openEditForm = (task) => {
  form.value = {
    id: task.id,
    title: task.title,
    description: task.description,
    status: task.status,
    due_date: task.due_date
      ? task.due_date.substring(0, 10)
      : ''
  }

  showForm.value = true
  isEdit.value = true
}

const saveTask = async () => {
  if (!form.value.title.trim()) return alert('Title is required')
  if (isEdit.value) await axios.put(`/api/tasks/${form.value.id}`, form.value)
  else await axios.post('/api/tasks', form.value)
  closeForm()
  fetchTasks()
}
const deleteTask = async (id) => {
  if (!confirm('Delete this task?')) return
  await axios.delete(`/api/tasks/${id}`)
  fetchTasks()
}
const resetForm = () => { form.value = { id: null, title: '', description: '', status: 'pending', due_date: '' } }
const closeForm = () => { showForm.value = false; resetForm() }

const formatDate = (date) => {
  if (!date) return '-'
  const d = new Date(date)
  return d.toLocaleDateString('en-GB').replace(/\//g, '-')
}


onMounted(fetchTasks)
</script>

<template>
<div class="container">
  <h2>📋 Task Manager</h2>

  <!-- Top bar: add + filter -->
  <div class="top-bar">
    <button class="add-btn" @click="openAddForm">➕ Add Task</button>
    <select v-model="filterStatus">
      <option value="all">All</option>
      <option value="pending">Pending</option>
      <option value="in_progress">In Progress</option>
      <option value="completed">Completed</option>
    </select>
  </div>

  <!-- Form -->
  <div v-if="showForm" class="form-box">
    <h3>{{ isEdit ? 'Edit Task' : 'Add Task' }}</h3>
    <input v-model="form.title" placeholder="Title *" maxlength="255" />
    <textarea v-model="form.description" placeholder="Description"></textarea>
    <select v-model="form.status">
      <option value="pending">Pending</option>
      <option value="in_progress">In Progress</option>
      <option value="completed">Completed</option>
    </select>
    <input type="date" v-model="form.due_date" />
    <div class="actions">
      <button @click="saveTask">{{ isEdit ? 'Update' : 'Save' }}</button>
      <button class="cancel" @click="closeForm">Cancel</button>
    </div>
  </div>

  <!-- Task Table -->
  <table>
    <thead>
      <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Status</th>
        <th>Due Date</th>
        <th width="120">Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="task in tasks" :key="task.id">
        <td>{{ task.title }}</td>
        <td>{{ task.description || '-' }}</td>
        <td><span :class="task.status">{{ task.status }}</span></td>
        <td>{{ formatDate(task.due_date) }}</td>
        <td>
          <button @click="openEditForm(task)">Edit</button> &nbsp;
          <button class="delete" @click="deleteTask(task.id)">Delete</button>
        </td>
      </tr>
      <tr v-if="tasks.length === 0">
        <td colspan="5" class="empty">No tasks found</td>
      </tr>
    </tbody>
  </table>

  <!-- Pagination -->
  <div class="pagination" v-if="pagination.last_page > 1">
    <button @click="currentPage = Math.max(1, currentPage - 1)">Prev</button>
    <span>{{ pagination.current_page }} / {{ pagination.last_page }}</span>
    <button @click="currentPage = Math.min(pagination.last_page, currentPage + 1)">Next</button>
  </div>
</div>
</template>

<style scoped>
.container { max-width:900px; margin:30px auto; font-family:Arial; }
.top-bar { display:flex; justify-content:space-between; margin-bottom:10px; }
.add-btn { margin-bottom:10px; }
table { width:100%; border-collapse:collapse; }
th, td { border:1px solid #ddd; padding:8px; }
th { background:#f2f2f2; }
.form-box { padding:10px; margin-bottom:20px; background:#f9f9f9; }
input, textarea, select { width:100%; margin-bottom:8px; padding:6px; }
textarea { resize: vertical; }
.actions { display:flex; gap:8px; margin-top:5px; }
.cancel { background:#ccc; }
.delete { color:red; }
.pending { color:orange; }
.in_progress { color:blue; }
.completed { color:green; font-weight:bold; }
.empty { text-align:center; }
.pagination { margin-top:10px; display:flex; justify-content:center; gap:10px; }
button { cursor:pointer; padding:5px 10px; }
</style>
