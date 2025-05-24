// /composables/useProjectTasks.js
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

export function useProjectTasks(initialProjects = []) {
  const newProject = ref({ name: '', description: '' })
  const newTasks = ref(Object.fromEntries(initialProjects.map(p => [p.id, ''])))
  
  // Nuevo estado para la tarea seleccionada para detalles
  const selectedTask = ref(null)

  const deleteEntity = (type, id) => {
    const messages = {
      project: '¿Estás seguro de que deseas eliminar este proyecto y todas sus tareas?',
      task: '¿Estás seguro de que deseas eliminar esta tarea?'
    }
    if (confirm(messages[type])) {
      router.delete(`/${type}s/${id}`)
    }
  }

  const createProject = () => {
    router.post('/projects', newProject.value, {
      onSuccess: () => Object.assign(newProject.value, { name: '', description: '' })
    })
  }

  const addTask = (projectId) => {
    const title = newTasks.value[projectId]?.trim()
    if (!title) return

    router.post('/tasks', { title, project_id: projectId }, {
      onSuccess: () => { newTasks.value[projectId] = '' }
    })
  }

  const updateEntity = (type, entity) => {
    const data = type === 'project'
      ? { name: entity.name, description: entity.description }
      : { 
          title: entity.title, 
          project_id: entity.project_id,
          description: entity.description,
          assigned_time: entity.assigned_time,
          due_date: entity.due_date
        }

    router.put(`/${type}s/${entity.id}`, data)
  }

  const updateProject = project => updateEntity('project', project)
  const updateTask = task => updateEntity('task', task)

  // Nueva función para seleccionar una tarea para detalles
  const selectTaskForDetails = (task) => {
    selectedTask.value = task
  }

  // Nueva función para deseleccionar la tarea (cerrar el panel)
  const clearSelectedTask = () => {
    selectedTask.value = null
  }

  return {
    newProject,
    newTasks,
    selectedTask, // Exponer selectedTask
    deleteEntity,
    createProject,
    addTask,
    updateProject,
    updateTask,
    selectTaskForDetails, // Exponer la función de selección
    clearSelectedTask, // Exponer la función para cerrar
  }
}
