<template>
  <div class="bg-gray-100 p-3 md:p-4 rounded-lg shadow-md w-80 md:w-96 flex-shrink-0 flex flex-col max-h-[calc(100vh-10rem)] md:max-h-[calc(100vh-12rem)]">
    <div class="flex justify-between items-start mb-3">
      <div class="flex-grow mr-2">
        <input v-model="project.name" @blur="updateProject(project)" class="text-lg md:text-xl font-semibold bg-transparent border-b-2 border-transparent hover:border-gray-300 focus:outline-none focus:border-blue-500 focus:bg-white p-1 rounded w-full transition-colors" placeholder="Nombre del Proyecto" />
        <textarea v-model="project.description" @blur="updateProject(project)" class="text-xs md:text-sm text-gray-600 bg-transparent border border-transparent hover:border-gray-200 focus:outline-none focus:border-gray-300 rounded w-full mt-1 p-1 h-16 resize-none focus:bg-white transition-colors" placeholder="Descripción..."></textarea>
      </div>
      <button @click="deleteProject(project.id)" class="text-gray-400 hover:text-red-600 p-1 rounded-full hover:bg-red-100 transition-colors flex-shrink-0" title="Eliminar proyecto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
      </button>
    </div>

    <div class="space-y-2 md:space-y-3 overflow-y-auto flex-grow pr-1 tasks-container custom-scrollbar-tasks">
      <TaskItem v-for="task in project.tasks" :key="task.id" :task="task" @update-task="updateTask" @delete-task="deleteTask" />
      <div v-if="!project.tasks || project.tasks.length === 0" class="text-center text-gray-400 text-sm italic py-4">
        No hay tareas en este proyecto.
      </div>
    </div>

    <form @submit.prevent="addTask(project.id)" class="mt-auto pt-3">
      <input v-model="newTasks[project.id]" type="text" :placeholder="'➕ Agregar tarea a ' + project.name.substring(0,15) + (project.name.length > 15 ? '...' : '')" class="border border-gray-300 rounded-md px-3 py-2 w-full text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm" required />
      <button type="submit" class="hidden">Agregar</button>
    </form>
  </div>


  <!-- Compartir proyecto -->
<div class="mt-4">
  <details class="group">
    <summary class="cursor-pointer text-sm text-blue-600 hover:underline">🔗 Compartir proyecto</summary>
    <div class="mt-2">
      <Share :project-id="project.id" />
    </div>
  </details>
</div>

</template>

<script setup>
import TaskItem from './TaskItem.vue'
import Share from './Share.vue' // ajusta el path si lo tienes en otra carpeta

const props = defineProps(['project', 'newTasks'])
const emit = defineEmits(['add-task', 'update-project', 'delete-project', 'update-task', 'delete-task'])

const addTask = (projectId) => emit('add-task', projectId)
const updateProject = (project) => emit('update-project', project)
const deleteProject = (id) => emit('delete-project', id)
const updateTask = (task) => emit('update-task', task)
const deleteTask = (id) => emit('delete-task', id)

</script>
