import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

export function useProjectTasks(initialProjects = []) {
  const newProject = ref({ name: '', description: '' })
  const newTasks = ref(Object.fromEntries(initialProjects.map(p => [p.id, ''])))

  const deleteEntity = (type, id) => {
    const messages = {
      project: '¿Estás seguro de que deseas eliminar este proyecto y todas sus tareas?',
      task: '¿Estás seguro de que deseas eliminar esta tarea?'
    }
    if (confirm(messages[type])) {
      router.delete(`/${type}s/${id}`, {
        onSuccess: () => {
          router.reload() // ✅ Recarga tras eliminar
        }
      })
    }
  }

  const createProject = () => {
    router.post('/projects', newProject.value, {
      onSuccess: () => {
        Object.assign(newProject.value, { name: '', description: '' })
        router.reload() // ✅ Recarga tras crear
      }
    })
  }

  const addTask = (projectId) => {
    const title = newTasks.value[projectId]?.trim()
    if (!title) return

    router.post('/tasks', { title, project_id: projectId }, {
      onSuccess: () => {
        newTasks.value[projectId] = ''
        router.reload() // ✅ Recarga tras agregar tarea
      }
    })
  }

  const updateEntity = (type, entity) => {
    let data = {}

    if (type === 'project') {
      data = {
        name: entity.name,
        description: entity.description
      }
    } else if (type === 'task') {
      data = Object.fromEntries(
        Object.entries({
          title: entity.title,
          description: entity.description,
          status: entity.status,
          due_date: entity.due_date,
          user_id: entity.user_id,
          project_id: entity.project_id
        }).filter(([_, v]) => v !== undefined)
      )
    }

    router.put(`/${type}s/${entity.id}`, data, {
      onSuccess: () => {
        router.reload() // ✅ Recarga tras actualizar
      }
    })
  }

  const updateProject = project => updateEntity('project', project)
  const updateTask = task => updateEntity('task', task)

  return {
    newProject,
    newTasks,
    deleteEntity,
    createProject,
    addTask,
    updateProject,
    updateTask,
  }
}
