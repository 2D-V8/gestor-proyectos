<template>
  <div v-if="task" class="bg-white rounded-md shadow p-4 border border-gray-200 w-full max-w-md ml-auto">
    <h2 class="text-xl font-bold mb-2">{{ task.title }}</h2>
    <div class="mb-2">
      <strong class="block text-gray-700 mb-1">Descripción:</strong>
      <textarea
        v-model="localDescription"
        @blur="updateDescription"
        @keydown.enter.prevent="updateDescription"
        class="w-full mt-1 p-2 border rounded-md text-black focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        rows="4"
        placeholder="Escribe la descripción de la tarea..."
      ></textarea>
    </div>

    <!-- Estado Section - Display only, based on dates -->
    <div class="mb-2">
      <strong class="block text-gray-700 mb-1">Estado:</strong>
      <span class="text-gray-700">{{ calculatedStatus }}</span>
    </div>

    <!-- Fecha asignada - Editable por Admin -->
    <div class="mb-2">
      <strong class="block text-gray-700 mb-1">Fecha asignada:</strong>
      <template v-if="isAdmin">
        <input
          type="datetime-local"
          v-model="localAssignedTime"
          @change="updateAssignedTime"
          class="border rounded-md px-2 py-1 text-black w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        />
      </template>
      <template v-else>
        <span class="text-gray-700">{{ formattedAssignedTime }}</span>
      </template>
    </div>

    <!-- Fecha límite - Editable por Admin -->
    <div class="mb-2">
      <strong class="block text-gray-700 mb-1">Fecha límite:</strong>
      <template v-if="isAdmin">
        <input
          type="datetime-local"
          v-model="localDueDate"
          @change="updateDueDate"
          class="border rounded-md px-2 py-1 text-black w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        />
      </template>
      <template v-else>
        <span class="text-gray-700">{{ formattedDueDate }}</span>
      </template>
    </div>
  </div>
  <div v-else class="text-gray-400 italic">Selecciona una tarea para ver los detalles</div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'

const props = defineProps({
  task: {
    type: Object,
    default: null
  },
  isAdmin: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:description', 'update:assignedTime', 'update:dueDate'])

const localDescription = ref('')
const localAssignedTime = ref('')
const localDueDate = ref('')

// Propiedad computada para determinar el estado basado en las fechas
const calculatedStatus = computed(() => {
  if (props.task) {
    if (props.task.due_date) {
      return 'Completada'
    } else if (props.task.assigned_time) {
      return 'En progreso'
    } else {
      return 'Pendiente'
    }
  }
  return ''
})

// Formatear fechas para mostrar (solo fecha)
const formattedAssignedTime = computed(() => {
  if (!props.task?.assigned_time) return 'No asignada'
  return formatDateForDisplay(props.task.assigned_time)
})

const formattedDueDate = computed(() => {
  if (!props.task?.due_date) return 'No definida'
  return formatDateForDisplay(props.task.due_date)
})

// Helper para formatear fechas para mostrar
function formatDateForDisplay(dateString) {
  if (!dateString) return ''
  const date = new Date(dateString)
  if (isNaN(date.getTime())) return ''

  return new Intl.DateTimeFormat('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  }).format(date)
}

// Helper para formatear fechas para input datetime-local (incluye hora)
function formatForDatetimeLocal(dateString) {
  if (!dateString) return ''
  const date = new Date(dateString)
  if (isNaN(date.getTime())) return ''

  const year = date.getFullYear()
  const month = ('0' + (date.getMonth() + 1)).slice(-2)
  const day = ('0' + date.getDate()).slice(-2)
  const hours = ('0' + date.getHours()).slice(-2)
  const minutes = ('0' + date.getMinutes()).slice(-2)

  return `${year}-${month}-${day}T${hours}:${minutes}`
}

// Sincronizar estados locales con la prop task
watch(() => props.task, (newTask) => {
  if (newTask) {
    localDescription.value = newTask.description || ''
    localAssignedTime.value = newTask.assigned_time ? formatForDatetimeLocal(newTask.assigned_time) : ''
    localDueDate.value = newTask.due_date ? formatForDatetimeLocal(newTask.due_date) : ''
  }
}, { immediate: true, deep: true })

// Función para emitir la descripción actualizada y establecer fecha de asignación si no existe
const updateDescription = () => {
  if (props.task && localDescription.value !== props.task.description) {
    // Si no hay fecha asignada, establecerla ahora y emitir el evento
    if (!props.task.assigned_time) {
      const now = new Date().toISOString()
      emit('update:assignedTime', now)
      // Actualizar el estado local y la prop para que se refleje inmediatamente
      localAssignedTime.value = formatForDatetimeLocal(now)
      // También actualizamos la prop directamente para que formattedAssignedTime reaccione
      // Esto es una mutación directa de la prop y puede no ser ideal en todos los casos,
      // pero ayuda a la reactividad inmediata en este escenario simple.
      // Una alternativa más robusta sería actualizar la tarea en el store/composable y que la prop se derive de ahí.
       if (props.task) {
           props.task.assigned_time = now;
       }
    }
    emit('update:description', localDescription.value)
  }
}

// Funciones para emitir fechas actualizadas
const updateAssignedTime = () => {
  if (props.task) {
    const newTime = localAssignedTime.value ? new Date(localAssignedTime.value).toISOString() : null;
    emit('update:assignedTime', newTime)
     // Actualizar la prop directamente para reactividad
    if (props.task) {
        props.task.assigned_time = newTime;
    }
  }
}

const updateDueDate = () => {
  if (props.task) {
    const newTime = localDueDate.value ? new Date(localDueDate.value).toISOString() : null;
    emit('update:dueDate', newTime)
    // Actualizar la prop directamente para reactividad
    if (props.task) {
        props.task.due_date = newTime;
    }
  }
}
</script>