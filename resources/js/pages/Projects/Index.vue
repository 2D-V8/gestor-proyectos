<template>
  <div class="bg-blue-600 p-4 md:p-6 min-h-screen flex">
    <div class="flex flex-col flex-grow">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-white">Mis Proyectos</h1>
        <button
          @click="goToDashboard"
          class="bg-white text-blue-600 font-semibold px-4 py-2 rounded shadow hover:bg-blue-100 transition"
        >
          Ir al Dashboard
        </button>
      </div>
      <div class="flex overflow-x-auto space-x-4 md:space-x-6 pb-4 custom-scrollbar-container flex-grow">
        <ProjectCreator :newProject="newProject" @create-project="createProject" />
        <ProjectCard
          v-for="project in projects"
          :key="project.id"
          :project="project"
          :newTasks="newTasks"
          @add-task="addTask"
          @update-project="updateProject"
          @delete-project="() => deleteEntity('project', project.id)"
          @update-task="updateTask"
          @delete-task="id => deleteEntity('task', id)"
          :selectTaskForDetails="selectTaskForDetails"
        />
      </div>
    </div>

    <!-- Panel de detalles de la tarea -->
    <transition
      enter-active-class="transition ease-out duration-300 transform"
      enter-from-class="translate-x-full"
      enter-to-class="translate-x-0"
      leave-active-class="transition ease-in duration-300 transform"
      leave-from-class="translate-x-0"
      leave-to-class="translate-x-full"
    >
      <div v-if="selectedTask" class="fixed inset-y-0 right-0 w-full max-w-md bg-white shadow-xl z-50 p-6 overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold">Detalles de la Tarea</h2>
          <button @click="clearSelectedTask" class="text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <DescritpTask 
          :task="selectedTask"
          @update:description="updateTaskDescription"
        />
      </div>
    </transition>

    <!-- Overlay oscuro -->
    <div 
      v-if="selectedTask" 
      class="fixed inset-0 bg-black opacity-50 z-40"
      @click="clearSelectedTask"
    ></div>
  </div>
</template>

<script setup>
import { useProjectTasks } from '@/composables/useProjectTasks'
import ProjectCreator from '@/components/ProjectCreator.vue'
import ProjectCard from '@/components/ProjectCard.vue'
import DescritpTask from '@/components/DescritpTask.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({ projects: Array })

const {
  newProject,
  newTasks,
  selectedTask,
  deleteEntity,
  createProject,
  addTask,
  updateProject,
  updateTask,
  selectTaskForDetails,
  clearSelectedTask,
} = useProjectTasks(props.projects)

function goToDashboard() {
  router.visit('/dashboard')
}

const updateTaskDescription = (newDescription) => {
  if (selectedTask.value) {
    updateTask({ ...selectedTask.value, description: newDescription })
    selectedTask.value.description = newDescription
  }
}
</script>
