const apiUrl = '../todo-api/index.php';

const fetchTasks = async () => {
  try {
    const response = await fetch(apiUrl);
    if (!response.ok) {
      throw new Error(`Error al obtener tareas: ${response.status}`);
    }
    const tasks = await response.json();
    const tasksDiv = document.getElementById('tasks');
    tasksDiv.innerHTML = tasks.map(task => `
      <div class="task">
        <span>${task.titulo} - ${task.descripcion}</span>
        <button onclick="deleteTask('${task.id}')">Eliminar</button>
      </div>
    `).join('');
  } catch (error) {
    console.error(error);
    alert('Hubo un problema al cargar las tareas.');
  }
};

document.getElementById('taskForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const title = document.getElementById('title').value;
  const description = document.getElementById('description').value;

  try {
    const response = await fetch(apiUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ titulo: title, descripcion: description })
    });

    if (!response.ok) {
      throw new Error(`Error al agregar tarea: ${response.status}`);
    }

    fetchTasks(); // Actualizar lista
  } catch (error) {
    console.error(error);
    alert('Hubo un problema al agregar la tarea.');
  }
});

const deleteTask = async (id) => {
  try {
    const response = await fetch(`${apiUrl}/${id}`, {
      method: 'DELETE'
    });

    if (!response.ok) {
      throw new Error(`Error al eliminar tarea: ${response.status}`);
    }

    fetchTasks(); // Actualizar lista
  } catch (error) {
    console.error(error);
    alert('Hubo un problema al eliminar la tarea.');
  }
};

fetchTasks(); // Cargar tareas al inicio
