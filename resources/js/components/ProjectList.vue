<template>
  <div class="flex overflow-x-auto space-x-4 md:space-x-6 pb-4 custom-scrollbar-container">
    <ProjectCreator :newProject="newProject" @create-project="createProject" />
    <ProjectCard
      v-for="project in projects"
      :key="project.id"
      :project="project"
      :newTasks="newTasks"
      @add-task="addTask"
      @update-project="updateProject"
      @delete-project="deleteProject"
      @update-task="updateTask"
      @delete-task="deleteTask"
    />
  </div>
</template>

<script setup>
import ProjectCard from '@/components/ProjectCard.vue'
import ProjectCreator from '@/components/ProjectCreator.vue'

const props = defineProps({
  projects: Array,
  newProject: Object,
  newTasks: Object,
  createProject: Function,
  addTask: Function,
  updateProject: Function,
  deleteProject: Function,
  updateTask: Function,
  deleteTask: Function
})
</script>
