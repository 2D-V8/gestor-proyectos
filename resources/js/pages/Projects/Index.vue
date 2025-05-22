<template>
  <div class="bg-blue-600 p-4 md:p-6 min-h-screen">
    <h1 class="text-2xl md:text-3xl font-bold mb-6 text-white">Mis Proyectos</h1>
    <div class="flex overflow-x-auto space-x-4 md:space-x-6 pb-4 custom-scrollbar-container">
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
      />
    </div>
  </div>
</template>

<script setup>
import { useProjectTasks } from '@/composables/useProjectTasks'
import ProjectCreator from '@/components/ProjectCreator.vue'
import ProjectCard from '@/components/ProjectCard.vue'

const props = defineProps({ projects: Array })

const {
  newProject,
  newTasks,
  deleteEntity,
  createProject,
  addTask,
  updateProject,
  updateTask,
} = useProjectTasks(props.projects)
</script>
