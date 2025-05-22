// src/composables/useProjectActions.js
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

export default function useProjectActions(projects) {
  const newProject = ref({ name: '', description: '' })
  const newTasks = ref({})
  projects.forEach(p => (newTasks.value[p.id] ??= ''))

  const createProject = () => {
    router.post('/projects', newProject.value, {
      onSuccess: () => {
        newProject.value.name = ''
        newProject.value.description = ''
      }
    })
  }

  const addTask = (projectId) => {
    const title = newTasks.value[projectId]
    if (!title || !title.trim()) return

    router.post('/tasks', {
      title: title.trim(),
      project_id: projectId,
    }, {
      onSuccess: () => { newTasks.value[projectId] = '' }
    })
  }

  const updateProject = (project) => {
    router.put(`/projects/${project.id}`, {
      name: project.name,
      description: project.description,
    })
  }

  const deleteProject = (id) => {
    if (confirm('¿Estás seguro de que deseas eliminar este proyecto y todas sus tareas?')) {
      router.delete(`/projects/${id}`)
    }
  }

  const updateTask = (task) => {
    router.put(`/tasks/${task.id}`, {
      title: task.title,
      project_id: task.project_id,
    })
  }

  const deleteTask = (id) => {
    if (confirm('¿Estás seguro de que deseas eliminar esta tarea?')) {
      router.delete(`/tasks/${id}`)
    }
  }

  return {
    newProject,
    newTasks,
    createProject,
    addTask,
    updateProject,
    deleteProject,
    updateTask,
    deleteTask
  }
}
