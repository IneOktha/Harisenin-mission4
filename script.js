// Todo App JavaScript
let todos = JSON.parse(localStorage.getItem('todos')) || [];
let nextId = parseInt(localStorage.getItem('nextId')) || 1;

// Initialize app
document.addEventListener('DOMContentLoaded', function() {
    loadTodos();
    setupEventListeners();
    updateDateTime();
    setMinDate();
    setInterval(updateDateTime, 60000);
});

// Set minimum date to today
function setMinDate() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('dueDate').min = today;
}

// Setup event listeners
function setupEventListeners() {
    document.getElementById('todoForm').addEventListener('submit', addTodo);
}

// Update date and time
function updateDateTime() {
    const now = new Date();
    const dateElement = document.getElementById('currentDate');
    const timeElement = document.getElementById('currentTime');

    if (dateElement) {
        const day = now.toLocaleDateString('en-US', { weekday: 'long' });
        const date = now.getDate();
        const month = now.toLocaleDateString('en-US', { month: 'long' });
        const year = now.getFullYear();
        dateElement.textContent = `${day}, ${date} ${month} ${year}`;
    }

    if (timeElement) {
        timeElement.textContent = now.toLocaleTimeString('en-US', {
            hour12: false,
            hour: '2-digit',
            minute: '2-digit'
        });
    }
}

// Add new todo
function addTodo(e) {
    e.preventDefault();

    const taskInput = document.getElementById('task');
    const priorityInput = document.getElementById('priority');
    const dueDateInput = document.getElementById('dueDate');
    const dueTimeInput = document.getElementById('dueTime');

    if (!taskInput.value.trim()) return;

    const todo = {
        id: nextId++,
        task: taskInput.value.trim(),
        priority: priorityInput.value,
        dueDate: dueDateInput.value || null,
        dueTime: dueTimeInput.value || null,
        isCompleted: false,
        createdAt: new Date().toISOString(),
        completedAt: null
    };

    todos.push(todo);
    saveTodos();
    loadTodos();

    // Clear form
    taskInput.value = '';
    priorityInput.value = 'Medium';
    dueDateInput.value = '';
    dueTimeInput.value = '';
}

// Toggle todo completion
function toggleTodo(id) {
    const todo = todos.find(t => t.id === id);
    if (todo) {
        todo.isCompleted = !todo.isCompleted;
        todo.completedAt = todo.isCompleted ? new Date().toISOString() : null;
        saveTodos();
        loadTodos();
    }
}

// Delete single todo
function deleteTodo(id) {
    if (confirm('Are you sure you want to delete this task?')) {
        todos = todos.filter(t => t.id !== id);
        saveTodos();
        loadTodos();
    }
}

// Delete all todos
function deleteAllTodos() {
    if (confirm('Are you sure you want to delete all tasks?')) {
        todos = [];
        saveTodos();
        loadTodos();
    }
}

// Save todos to localStorage
function saveTodos() {
    localStorage.setItem('todos', JSON.stringify(todos));
    localStorage.setItem('nextId', nextId.toString());
}

// Load and display todos
function loadTodos() {
    const todoList = document.getElementById('todoList');
    const doneList = document.getElementById('doneList');
    const deleteAllSection = document.getElementById('deleteAllSection');

    const pendingTodos = todos.filter(t => !t.isCompleted);
    const completedTodos = todos.filter(t => t.isCompleted);

    // Update delete all button visibility
    deleteAllSection.style.display = todos.length > 0 ? 'block' : 'none';

    // Render pending todos
    if (pendingTodos.length === 0) {
        todoList.innerHTML = `
            <div id="emptyTodoState" class="text-center text-gray-500 py-8">
                <i class="fas fa-check-circle text-4xl mb-4 text-gray-300"></i>
                <p>No pending tasks!</p>
            </div>
        `;
    } else {
        todoList.innerHTML = pendingTodos.map(todo => createTodoHTML(todo)).join('');
    }

    // Render completed todos
    if (completedTodos.length === 0) {
        doneList.innerHTML = `
            <div id="emptyDoneState" class="text-center text-gray-500 py-8">
                <i class="fas fa-tasks text-4xl mb-4 text-gray-300"></i>
                <p>No completed tasks yet!</p>
            </div>
        `;
    } else {
        doneList.innerHTML = completedTodos.map(todo => createTodoHTML(todo, true)).join('');
    }
}

// Create todo HTML
function createTodoHTML(todo, isCompleted = false) {
    const priorityClass = `priority-${todo.priority.toLowerCase()}`;
    const createdDate = new Date(todo.createdAt).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });

    const completedDate = todo.completedAt ?
        new Date(todo.completedAt).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        }) : '';

    // Format due date and time
    let dueInfo = '';
    if (todo.dueDate) {
        const dueDate = new Date(todo.dueDate).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric'
        });
        dueInfo = todo.dueTime ? `${dueDate} at ${todo.dueTime}` : dueDate;
    }

    // Check if task is overdue
    const isOverdue = todo.dueDate && !todo.isCompleted && new Date(todo.dueDate + (todo.dueTime ? ' ' + todo.dueTime : '')) < new Date();
    const overdueClass = isOverdue ? 'border-red-300 bg-red-50' : '';

    return `
        <div class="border border-gray-200 rounded-lg p-4 ${isCompleted ? 'bg-gray-50' : 'hover:shadow-sm'} ${overdueClass} transition duration-200">
            <div class="flex items-start space-x-3">
                <!-- Checkbox -->
                <button onclick="toggleTodo(${todo.id})" class="mt-1 flex-shrink-0">
                    ${isCompleted ?
                        `<div class="w-5 h-5 bg-green-500 border-2 border-green-500 rounded flex items-center justify-center">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>` :
                        `<div class="w-5 h-5 border-2 border-gray-300 rounded hover:border-blue-500 transition duration-200"></div>`
                    }
                </button>

                <!-- Task Content -->
                <div class="flex-1 min-w-0">
                    <p class="text-gray-800 break-words ${isCompleted ? 'line-through text-gray-600' : ''}">${escapeHtml(todo.task)}</p>

                    <!-- Due Date and Time -->
                    ${dueInfo ? `
                        <div class="mt-1 flex items-center">
                            <i class="fas fa-clock text-xs text-gray-400 mr-1"></i>
                            <span class="text-xs ${isOverdue ? 'text-red-600 font-medium' : 'text-gray-600'}">
                                ${isOverdue ? '⚠️ Overdue: ' : 'Due: '}${dueInfo}
                            </span>
                        </div>
                    ` : ''}

                    <div class="flex items-center justify-between mt-2">
                        <span class="px-2 py-1 text-xs font-medium rounded-full ${isCompleted ? 'bg-gray-200 text-gray-600' : priorityClass}">
                            ${todo.priority}
                        </span>
                        <span class="text-xs text-gray-500">
                            ${isCompleted ? `Completed: ${completedDate}` : `Created: ${createdDate}`}
                        </span>
                    </div>
                </div>

                <!-- Delete Button -->
                <button onclick="deleteTodo(${todo.id})" class="text-red-500 hover:text-red-700 transition duration-200 flex-shrink-0">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>
        </div>
    `;
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
